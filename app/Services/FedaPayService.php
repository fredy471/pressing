<?php

namespace App\Services;

use App\Models\Order;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use Illuminate\Support\Facades\Log;

class FedapayService
{
    public function __construct()
    {
        FedaPay::setApiKey(config('services.fedapay.secret_key'));
        FedaPay::setEnvironment(config('services.fedapay.environment'));
    }

    /**
     * Crée une transaction FedaPay et retourne l'URL de paiement à laquelle rediriger le client.
     */
    public function initiatePayment(Order $order): string
    {
        $transaction = Transaction::create([
            'description' => 'Commande ' . $order->tracking_code . ' — Presso',
            'amount' => (int) $order->total_amount,
            'currency' => ['iso' => 'XOF'],
            'callback_url' => route('client.payments.callback', $order),
            'customer' => [
                'firstname' => $order->user->name,
                'lastname' => '—',
                'email' => $order->user->email,
                'phone_number' => [
                    'number' => $order->user->phone,
                    'country' => 'bj',
                ],
            ],
        ]);
        Log::info('FedaPay paiement lancé', [ 'transaction_id' => $transaction->id, 'order_id' => $order->id,]);

        // register the payment infos before success
        $order->payments()->create([
            'amount' => $order->total_amount,
            'method' => 'mobile_money',
            'provider' => 'fedapay',
            'statut' => 'en_attente',
            'provider_reference' => $transaction->id,
        ]);

        $token = $transaction->generateToken();

        return $token->url;
    }
}
