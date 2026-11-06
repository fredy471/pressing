<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PendingOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


/**
 * Point unique de confirmation d'un paiement.
 *
 * Il est appelé par le webhook FedaPay ET par la vérification directe (page de retour) :
 * la logique n'existe qu'à un seul endroit, et le verrou en base empêche toute double création.
 */
class PaymentConfirmationService
{
    public function __construct(private FedaPayService $fedapay) {}

    /**
     * Confirme un paiement approuvé : crée la commande et le paiement, une seule fois.
     */
    public function confirmPaid(int|string $transactionId, ?int $paidAmount = null): ?PendingOrder
    {
        return DB::transaction(function () use ($transactionId, $paidAmount) {
            $pending = PendingOrder::where('fedapay_transaction_id', (string) $transactionId)
                ->lockForUpdate()
                ->first();

            if (! $pending) {
                Log::warning('Paiement approuvé sans commande en attente correspondante', [
                    'transaction_id' => $transactionId,
                ]);
                return null;
            }

            if ($pending->status !== 'en_attente') {
                return $pending; // déjà traité (relivraison du webhook ou vérification concurrente)
            }

            if ($paidAmount !== null && $paidAmount !== (int) $pending->total_amount) {
                Log::error('Montant FedaPay différent du montant attendu', [
                    'pending' => $pending->id,
                    'attendu' => $pending->total_amount,
                    'recu'    => $paidAmount,
                ]);
                return $pending; // à rapprocher manuellement
            }

            $order = $this->materializeOrder($pending);
            $order->payments()->create([
                'amount'             => $pending->total_amount,
                'method'             => 'mobile_money',
                'provider'           => 'fedapay',
                'status'             => 'reussi',
                'provider_reference' => $pending->fedapay_transaction_id,
                'paid_at'            => now(),
            ]);
            $pending->update(['status' => 'confirmed', 'order_id' => $order->id]);
            return $pending;
        });
    }

    /**
     * Marque une commande en attente comme annulée (paiement annulé ou refusé).
     */
    public function markFailed(int|string $transactionId, string $status = 'annulee'): void
    {
        DB::transaction(function () use ($transactionId, $status) {
            $pending = PendingOrder::where('fedapay_transaction_id', (string) $transactionId)
                ->lockForUpdate()
                ->first();

            if ($pending && $pending->status === 'en_attente') {
                $pending->update(['status' => $status]);
            }
        });
    }

    /**
     * Interroge FedaPay directement (de serveur à serveur) et applique le résultat.
     * Sert de filet de sécurité quand le webhook est en retard, perdu ou désactivé.
     */
    public function syncFromFedapay(PendingOrder $pending): PendingOrder
    {
        if ($pending->status !== 'en_attente' || ! $pending->fedapay_transaction_id) {
            return $pending;
        }

        $transaction = $this->fedapay->retrieveTransaction($pending->fedapay_transaction_id);

        if ($transaction->wasPaid()) {
            $this->confirmPaid($pending->fedapay_transaction_id, (int) $transaction->amount);
        } elseif (in_array($transaction->status, ['declined', 'canceled'], true)) {
            $this->markFailed($pending->fedapay_transaction_id);
        }

        return $pending->fresh();
    }

    /**
     * Transforme le PendingOrder en vraie commande — seul endroit où Order::create() est appelé.
     */
    private function materializeOrder(PendingOrder $pendingOrder): Order
    {
        $payload = $pendingOrder->payload;

        $order = Order::create([
            'user_id'          => $pendingOrder->user_id,
            'delivery_mode'    => $payload['delivery_mode'],
            'pickup_adress'   => $payload['pickup_adress'] ?? null,
            'pickup_phone'     => $payload['pickup_phone'] ?? null,
            'status'           => 'en_attente',
            'deposit_deadline' => now()->addDays(7),
            'total_amount'     => $pendingOrder->total_amount,
        ]);

        foreach ($payload['items'] as $item) {
            $order->items()->create([
                'service_id'      => $item['service_id'],
                'clothes_type_id' => $item['clothes_type_id'],
                'quantity'        => $item['quantity'],
                'unit_price'      => $item['unit_price'],
                'subtotal'        => $item['unit_price'] * $item['quantity'],
            ]);
        }

        return $order;
    }
}
