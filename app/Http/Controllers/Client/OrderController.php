<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\ClothesType;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceClothesPrice;
use App\Services\PendingOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\PendingOrder;

class OrderController extends Controller
{
    public function create(): View
    {
        $services = Service::where('is_active', true)->get();
        return view('client.orders.create', compact('services'));
    }

    public function clothesTypesForService(Service $service)
    {
        $clothesTypes = ClothesType::where('is_active', true)
            ->whereHas('servicePrices', fn($q) => $q->where('service_id', $service->id))
            ->get(['id', 'name']);

        $prices = ServiceClothesPrice::where('service_id', $service->id)
            ->pluck('price', 'clothes_type_id');

        return response()->json(['clothes_types' => $clothesTypes, 'prices' => $prices]);
    }

    public function store(StoreOrderRequest $request, PendingOrderService $pending): RedirectResponse
    {
        $data = $request->validated();

        if (! auth()->check()) {
            session([
                'pending_order'       => $data,
                'post_login_redirect' => route('client.orders.resume'),
            ]);
            return redirect()->route('login')->with('status', 'Connectez-vous pour finaliser votre commande.');
        }

        $pending->createFor(auth()->user(), $data);
        return redirect()->route('client.orders.pending');
    }

    public function resume(PendingOrderService $pending): RedirectResponse
    {
        if ($data = session()->pull('pending_order')) {
            $pending->createFor(auth()->user(), $data);
            // On indique que cette commande vient d'une session restaurée
            session()->flash('session_restored', true);
        }
        return redirect()->route('client.orders.pending');
    }

    public function pending(): View
    {
        $pendingOrder = PendingOrder::where('user_id', auth()->id())
            ->where('status', 'en_attente')
            ->latest('id')
            ->first();

        $lines = collect();
        if ($pendingOrder) {
            $items    = collect($pendingOrder->payload['items']);
            $services = Service::whereIn('id', $items->pluck('service_id'))->pluck('name', 'id');
            $clothes  = ClothesType::whereIn('id', $items->pluck('clothes_type_id'))->pluck('name', 'id');

            $lines = $items->map(fn($i) => [
                'service'  => $services[$i['service_id']] ?? '—',
                'clothes'  => $clothes[$i['clothes_type_id']] ?? '—',
                'quantity' => $i['quantity'],
                'subtotal' => $i['unit_price'] * $i['quantity'],
            ]);
        }

        return view('client.orders.pending', compact('pendingOrder', 'lines'));
    }


    public function show(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load(['items.service', 'items.clothesType', 'payments']);
        return view('client.orders.show', compact('order'));
    }

    public function index(): View
    {
        $ordersQuery = auth()->user()->orders()->with('items.service');
        $latestOrder = (clone $ordersQuery)->latest()->first();
        $orders = $ordersQuery->latest()->paginate(10);

        return view('client.orders.index', compact('orders', 'latestOrder'));
    }
}
