<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use App\Models\PendingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\FedaPayService;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function __construct(private FedapayService $fedapayService) {}

    public function initiate(string $reference): RedirectResponse
    {
        $pendingOrder = PendingOrder::where('reference', $reference)
            ->where('user_id', auth()->id())
            ->where('status', 'en_attente')
            ->firstOrFail();

        $paymentUrl = $this->fedapayService->initiatePayment($pendingOrder);
        return redirect($paymentUrl);
    }

    /**
     * Page affichée au retour du client — jamais de confirmation ici, juste un état d'attente.
     */
    public function callback(string $reference): View
    {
        $pendingOrder = PendingOrder::where('reference', $reference)->firstOrFail();

        return view('client.payments.callback', compact('pendingOrder'));
    }

    /**
     * Endpoint interrogé en polling par la page callback, pour savoir si le webhook a confirmé.
     */
    public function status(string $reference)
    {
        $pendingOrder = PendingOrder::where('reference', $reference)->firstOrFail();

        return response()->json([
            'status' => $pendingOrder->status,
            'redirect_url' => $pendingOrder->status === 'confirmed'
                ? route('client.orders.show', $pendingOrder->order_id)
                : null,
        ]);
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('X-FEDAPAY-SIGNATURE');

        try {
            $event = \FedaPay\Webhook::constructEvent($payload, $sigHeader, config('services.fedapay.webhook_secret'));
        } catch (\Exception $e) {
            Log::warning('Webhook FedaPay rejeté', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Invalide'], 400);
        }

        $transactionId = $event->object->id ?? null;
        $pendingOrder = PendingOrder::where('fedapay_transaction_id', $transactionId)->first();

        if (! $pendingOrder || $pendingOrder->status !== 'en_attente') {
            return response()->json(['message' => 'Déjà traité ou introuvable'], 200);
        }

        if ($event->name === 'transaction.approved') {
            DB::transaction(function () use ($pendingOrder) {
                $order = $this->materializeOrder($pendingOrder);

                $order->payments()->create([
                    'amount' => $pendingOrder->total_amount,
                    'method' => 'mobile_money',
                    'provider' => 'fedapay',
                    'status' => 'reussi',
                    'provider_reference' => $pendingOrder->fedapay_transaction_id,
                    'paid_at' => now(),
                ]);

                $pendingOrder->update(['status' => 'confirmed', 'order_id' => $order->id]);
            });
        } elseif ($event->name === 'transaction.canceled') {
            $pendingOrder->update(['status' => 'annulee']);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Transforme enfin le PendingOrder en vraie commande — seul moment où Order::create() est appelé.
     */
    private function materializeOrder(PendingOrder $pendingOrder): Order
    {
        $payload = $pendingOrder->payload;

        $order = Order::create([
            'user_id' => $pendingOrder->user_id,
            'delivery_mode' => $payload['delivery_mode'],
            'pickup_address' => $payload['pickup_address'] ?? null,
            'pickup_phone' => $payload['pickup_phone'] ?? null,
            'status' => 'en_attente',
            'deposit_deadline' => now()->addDays(7),
            'total_amount' => $pendingOrder->total_amount,
        ]);

        foreach ($payload['items'] as $item) {
            $order->items()->create([
                'service_id' => $item['service_id'],
                'clothes_type_id' => $item['clothes_type_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['unit_price'] * $item['quantity'],
            ]);
        }

        return $order;
    }
}
