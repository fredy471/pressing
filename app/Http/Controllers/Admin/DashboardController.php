<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $stats = [
            // Global stats (clés harmonisées avec la vue)
            'globalRevenue' => Payment::where('status', 'reussi')->sum('amount'),
            'total_orders' => Order::count(),
            'total_clients' => User::role('client')->count(),
            'total_agents' => User::role('agent')->count(),

            // Monthly stats
            'orders_this_month' => Order::whereBetween('created_at', [
                $startOfMonth,
                $endOfMonth
            ])->count(),

            'revenue_this_month' => Payment::where('status', 'reussi')
                ->whereBetween('created_at', [
                    $startOfMonth,
                    $endOfMonth
                ])
                ->sum('amount'),

            'clients_this_month' => User::role('client')
                ->whereBetween('created_at', [
                    $startOfMonth,
                    $endOfMonth
                ])
                ->count(),
        ];

        // Répartitions par statut
        $ordersByStatus = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Service le plus populaire
        $topService = OrderItem::selectRaw('service_id, count(*) as total')
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->with('service')
            ->first();

        // Commandes récentes
        $recentOrders = Order::with(['user', 'items.service'])
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'ordersByStatus', 'topService', 'recentOrders'));
    }
}