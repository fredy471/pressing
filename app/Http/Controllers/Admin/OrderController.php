<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request): View
    {
        $orders = Order::with(['user', 'agent', 'items.service'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('tracking_code', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'agent', 'items.service', 'items.clothesType', 'payments']);
        $successfulPayment = $order->payments->firstWhere('statut', 'reussi');

        return view('admin.orders.show', compact('order', 'successfulPayment'));
    }

}
