<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'en_attente' => Order::where('status', 'en_attente')->count(),
            'recue' => Order::where('status', 'recue')->count(),
            'en_lavage' => Order::where('status', 'en_lavage')->count(),
            'urgentes' => Order::where('status', 'en_attente')
                ->where('deposit_deadline', '<=', now()->addDay())
                ->count(),
        ];

        $recentOrders = Order::with(['user', 'items'])
            ->whereNotIn('status', ['livree', 'annulee'])
            ->latest()
            ->limit(6)
            ->get();

        return view('agent.dashboard', compact('stats', 'recentOrders'));
    }
}