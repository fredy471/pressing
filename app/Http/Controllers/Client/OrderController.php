<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClothesType;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\PendingOrder;
use App\Models\Service;
use App\Models\ServiceClothesPrice;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function create(): View
    {
        $services = Service::where('is_active', true)->get();
        return view('client.orders.create', compact('services'));
    }

    /**
     * Récupère, pour un service donné, les vêtements réellement tarifés.
     */
    public function clothesTypesForService(Service $service)
    {
        $clothesTypes = ClothesType::where('is_active', true)
            ->whereHas('servicePrices', fn($q) => $q->where('service_id', $service->id))
            ->get(['id', 'name']);

        $prices = ServiceClothesPrice::where('service_id', $service->id)
            ->pluck('price', 'clothes_type_id');

        return response()->json([
            'clothes_types' => $clothesTypes,
            'prices' => $prices,
        ]);
    }


    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'delivery_mode' => 'required|in:collecte_livraison,depot_physique',
            'pickup_address' => 'required_if:delivery_mode,collecte_livraison|nullable|string|max:255',
            'pickup_phone' => 'required_if:delivery_mode,collecte_livraison|nullable|string|max:10',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.clothes_type_id' => 'required|exists:clothes_types,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if (! auth()->check()) {
            session(['pending_order' => $validated]);
            return redirect()->route('login')->with('status', 'Connectez-vous pour finaliser votre commande.');
        }

        $pendingOrder = $this->createPendingOrder(auth()->user(), $validated);
        return redirect()->route('client.payments.initiate', $pendingOrder->reference);
    }


    /**
     * create pendingOrder that will be used to create orders after payment
     */
    public function createPendingOrder($user, array $validated): PendingOrder
    {
        $total = 0;

        foreach ($validated['items'] as &$item) {
            $unitPrice = ServiceClothesPrice::where('service_id', $item['service_id'])
                ->where('clothes_type_id', $item['clothes_type_id'])
                ->value('price');

            if (! $unitPrice) {
                abort(422, 'Un des articles sélectionnés n\'est plus disponible.');
            }

            $item['unit_price'] = $unitPrice;
            $total += $unitPrice * $item['quantity'];
        }

        return PendingOrder::create([
            'reference' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'payload' => $validated,
            'total_amount' => $total,
        ]);
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
