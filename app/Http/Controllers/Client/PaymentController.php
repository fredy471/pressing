<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\FedaPayService;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function __construct(private FedapayService $fedapayService) {}

    /**
     * Déclenche le paiement : crée la transaction FedaPay, redirige vers la page de paiement.
     */
    public function initiate(Order $order): RedirectResponse
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if ($order->payments()->where('statut', 'reussi')->exists()) {
            return redirect()->route('client.orders.show', $order)
                ->with('error', 'Cette commande a déjà été payée.');
        }

        $paymentUrl = $this->fedapayService->initiatePayment($order);

        return redirect($paymentUrl);
    }

    /**
     * L'utilisateur revient ici après le paiement (callback_url) — affichage seulement,
     * la confirmation réelle du statut se fait via le webhook, jamais ici.
     */
    public function callback(Order $order): RedirectResponse
    {
        return redirect()->route('client.orders.show', $order)
            ->with('success', 'Paiement en cours de vérification. Le statut sera mis à jour sous peu.');
    }

    /**
     * Webhook FedaPay — get the real status of the payment to update the informations into BD and following.
     */
    public function webhook(\Illuminate\Http\Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('X-FEDAPAY-SIGNATURE');
        $endpointSecret = config('services.fedapay.webhook_secret');

        try {
            $event = \FedaPay\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            \Illuminate\Support\Facades\Log::warning('Webhook FedaPay — payload invalide');
            return response()->json(['message' => 'Payload invalide'], 400);
        } catch (\FedaPay\Error\SignatureVerification $e) {
            \Illuminate\Support\Facades\Log::warning('Webhook FedaPay — signature invalide');
            return response()->json(['message' => 'Signature invalide'], 400);
        }

        $transactionId = $event->object->id ?? null;

        if (! $transactionId) {
            return response()->json(['message' => 'Événement invalide'], 400);
        }

        $payment = Payment::where('provider_reference', $transactionId)->first();

        if (! $payment) {
            return response()->json(['message' => 'Transaction inconnue'], 404);
        }


        match ($event->name) {
            'transaction.approved' => $payment->update(['statut' => 'reussi', 'paid_at' => now()]),
            'transaction.canceled' => $payment->update(['statut' => 'echoue']),
            default => null,
        };

        return response()->json(['message' => 'OK']);
    }
}
