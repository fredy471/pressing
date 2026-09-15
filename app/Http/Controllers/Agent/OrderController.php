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
            ->when(! $request->filled('status'), function ($query) {
                $query->whereNotIn('status', ['livree', 'annulee']);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
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

    public function updateStatus(UpdateOrderStatutRequest $request, Order $order): RedirectResponse
{
    $newStatus = $request->validated('status');

    if (! $order->canTransitionTo($newStatus)) {
        return back()->with('error', 'Transition de statut non autorisée.');
    }

    $updates = [
        'status' => $newStatus,
        'agent_id' => auth()->id(),
    ];

    if ($newStatus === 'recue') {
        $updates['deposited_at'] = now();
    }

    $order->update($updates);

    return back()->with('success', 'Statut mis à jour : ' . $newStatus);
}
}
