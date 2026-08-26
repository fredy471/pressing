<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_orders' => Order::count(),
            'orders_this_month' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'revenue' => Payment::where('status', 'reussi')->sum('amount'),
            'total_clients' => User::role('client')->count(),
            'total_agents' => User::role('agent')->count(),
        ];

        $ordersByStatus = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $topService = Order::selectRaw('service_id, count(*) as total')
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->with('service')
            ->first();

        $recentOrders = Order::with(['user', 'service'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'ordersByStatus', 'topService', 'recentOrders'));
    }
}
