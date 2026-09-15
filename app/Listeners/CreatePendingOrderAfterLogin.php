<?php

namespace App\Listeners;

use App\Http\Controllers\Client\OrderController;
use Illuminate\Auth\Events\Login;

class CreatePendingOrderAfterLogin
{
    public function handle(Login $event): void
    {
        if (! session()->has('pending_order')) {
            return;
        }

        $validated = session()->pull('pending_order');

        $pendingOrder = app(OrderController::class)
            ->createPendingOrder($event->user, $validated);

        session(['post_login_redirect' => route('client.payments.initiate', $pendingOrder->reference)]);
    }
}