<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $recentOrders = $user->orders()->latest()->take(5)->get();

        $stats = [
            'total_orders' => $user->orders()->count(),
            'pending_orders' => $user->orders()->where('statut', 'en_lavage')->count(),
        ];
        
        return view('client.dashboard', compact('recentOrders', 'stats'));
    }
}
