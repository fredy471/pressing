<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PendingOrder;
use App\Services\FedaPayService;
use App\Services\PaymentConfirmationService;
use App\Services\PendingOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private FedaPayService $fedapayService,
        private PendingOrderService $pendingOrderService,
        private PaymentConfirmationService $confirmation,
    ) {}

    public function initiate(string $reference): RedirectResponse
    {
        $pendingOrder = PendingOrder::where('reference', $reference)
            ->where('user_id', auth()->id())
            ->where('status', 'en_attente')
            ->firstOrFail();

        // Le montant est figé dès que la transaction FedaPay existe : on recalcule avant.
        if (! $pendingOrder->fedapay_transaction_id) {
            $pendingOrder = $this->pendingOrderService->reprice($pendingOrder);
        }

        return redirect($this->fedapayService->initiatePayment($pendingOrder));
    }

    /**
     * Page affichée au retour du client — la confirmation se fait via le webhook
     * ou via la vérification directe de status().
     */
    public function callback(string $reference): View
    {
        $pendingOrder = PendingOrder::where('reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('client.payments.callback', compact('pendingOrder'));
    }

    /**
     * Endpoint interrogé en polling par la page callback.
     * Si le webhook n'a pas confirmé, on vérifie directement auprès de FedaPay (au plus toutes les 8 s).
     */
    public function status(string $reference)
    {
        $pendingOrder = PendingOrder::where('reference', $reference)
            ->where('user_id', auth()->id())      // le polling est réservé au propriétaire
            ->firstOrFail();

        if ($pendingOrder->status === 'en_attente' && $pendingOrder->fedapay_transaction_id) {
            try {
                if (Cache::add('fedapay-sync:' . $pendingOrder->id, 1, 8)) {
                    $pendingOrder = $this->confirmation->syncFromFedapay($pendingOrder);
                }
            } catch (\Throwable $e) {
                Log::warning('Vérification directe FedaPay impossible', ['error' => $e->getMessage()]);
            }
        }

        return response()->json([
            'status' => $pendingOrder->status,
            'redirect_url' => $pendingOrder->status === 'confirmed'
                ? route('client.orders.show', $pendingOrder->order_id)
                : null,
        ]);
    }

    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('X-FEDAPAY-SIGNATURE');

        try {
            $event = \FedaPay\Webhook::constructEvent($payload, $sigHeader, config('services.fedapay.webhook_secret'));
        } catch (\Exception $e) {
            Log::warning('Webhook FedaPay rejeté', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Invalide'], 400);
        }

        $eventName     = $event->name ?? $event->type ?? null;
        $transactionId = $event->entity->id ?? $event->object_id ?? null;

        // Journal sans données personnelles : uniquement le nom, l'identifiant et les clés reçues
        Log::info('Webhook FedaPay reçu', [
            'event'          => $eventName,
            'transaction_id' => $transactionId,
            'cles'           => array_keys($request->all()),
        ]);

        if (! $transactionId) {
            return response()->json(['message' => 'Ignoré'], 200);
        }

        if ($eventName === 'transaction.approved') {
            $paid = isset($event->entity->amount) ? (int) $event->entity->amount : null;
            $this->confirmation->confirmPaid($transactionId, $paid);
        } elseif (in_array($eventName, ['transaction.canceled', 'transaction.declined'], true)) {
            $this->confirmation->markFailed($transactionId);
        }
        return response()->json(['message' => 'OK']);
    }
}