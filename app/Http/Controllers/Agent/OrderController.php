<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\UpdateOrderStatutRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with(['user', 'items'])
            ->when(! $request->filled('statut'), function ($query) {
                $query->whereNotIn('statut', ['livree', 'annulee']);
            })
            ->when($request->filled('statut'), function ($query) use ($request) {
                $query->where('statut', $request->statut);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('tracking_code', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('agent.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.service', 'items.clothesType']);

        return view('agent.orders.show', compact('order'));
    }

    public function updateStatut(UpdateOrderStatutRequest $request, Order $order): RedirectResponse
    {
        $newStatus = $request->validated('statut');

        if (! $order->canTransitionTo($newStatus)) {
            return back()->with('error', 'Transition de statut non autorisée.');
        }

        $order->update(['statut' => $newStatus]);

        if ($newStatus === 'recue') {
            $order->update(['deposited_at' => now()]);
        }

        return back()->with('success', 'Statut mis à jour : ' . $newStatus);
    }
}
