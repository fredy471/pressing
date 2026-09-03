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
        $order = app(OrderController::class)->createOrderForUser($event->user, $validated);
        session(['post_login_redirect' => route('commander', $order)]);
    }
}
