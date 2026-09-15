<?php

namespace App\Services;

use App\Models\PendingOrder;
use FedaPay\FedaPay;
use FedaPay\Transaction;

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
    public function initiatePayment(PendingOrder $pendingOrder): string
    {
        $transaction = Transaction::create([
            'description' => 'Commande Presso — Réf. ' . substr($pendingOrder->reference, 0, 8),
            'amount' => (int) $pendingOrder->total_amount,
            'currency' => ['iso' => 'XOF'],
            'callback_url' => route('client.payments.callback', $pendingOrder->reference),
            'customer' => [
                'firstname' => $pendingOrder->user->name,
                'lastname' => '—',
                'email' => $pendingOrder->user->email,
                'phone_number' => ['number' => $pendingOrder->user->phone, 'country' => 'bj'],
            ],
        ]);

        $pendingOrder->update(['fedapay_transaction_id' => $transaction->id]);

        return $transaction->generateToken()->url;
    }

}
