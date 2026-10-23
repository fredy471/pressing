<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Enums\OrderStatus;
use App\Http\Requests\Agent\UpdateOrderStatutRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        // Statistiques globales de l'activité
        $stats = [
            'en_attente' => Order::where('status', 'en_attente')->count(),
            'recue' => Order::where('status', 'recue')->count(),
            'en_lavage' => Order::where('status', 'en_lavage')->count(),
            'urgentes' => Order::where('status', 'en_attente')
                ->where('deposit_deadline', '<=', now()->addDay())
                ->count(),
        ];

        // Requête de base
        $ordersQuery = Order::with(['user', 'items']);

        // Filtrer par statut
        $ordersQuery->when(
            $request->filled('status'),
            fn($query) => $query->where('status', $request->input('status')),
            fn($query) => $query->whereNotIn('status', ['livree', 'annulee'])
        );

        // Filtrer par nom 
        $ordersQuery->when($request->filled('name'), function ($query) use ($request) {
            $name = $request->input('name');

            $query->whereHas('user', function ($userQuery) use ($name) {
                $userQuery->where('name', 'like', "%{$name}%");
            });
        });

        // Filtrer par email 
        $ordersQuery->when($request->filled('email'), function ($query) use ($request) {
            $email = $request->input('email');

            $query->whereHas('user', function ($userQuery) use ($email) {
                $userQuery->where('email', 'like', "%{$email}%");
            });
        });

        // Filtrer par code de suivi
        $ordersQuery->when(
            $request->filled('tracking_code'),
            fn($query) => $query->where(
                'tracking_code',
                'like',
                '%' . $request->input('tracking_code') . '%'
            )
        );

        // Résultats
        $orders = $ordersQuery
            ->latest()
            ->paginate($request->filled('name') ||
                $request->filled('email') ||
                $request->filled('tracking_code') ||
                $request->filled('status') ? 10 : 10)
            ->withQueryString();

        return view('agent.orders.index', compact('stats', 'orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.service', 'items.clothesType']);

        return view('agent.orders.show', compact('order'));
    }

    public function updateStatus(
        UpdateOrderStatutRequest $request,
        Order $order
    ): RedirectResponse {
        $data = $request->validated();

        $newStatus = OrderStatus::from($data['status']);

        if (! $order->status->canTransitionTo($newStatus)) {
            return back()->with('error', 'Transition de statut non autorisée.');
        }

        $updates = [
            'status' => $newStatus,
            'agent_id' => auth()->id(),
        ];

        if ($newStatus === OrderStatus::RECUE) {
            $updates['deposited_at'] = now();
        }

        $order->update($updates);

        return back()->with(
            'success',
            'Statut mis à jour : ' . $newStatus->label()
        );
    }
}
