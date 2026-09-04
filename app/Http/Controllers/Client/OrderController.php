<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClothesType;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceClothesPrice;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
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
            'service_id' => 'required|exists:services,id',
            'delivery_mode' => 'required|in:collecte_livraison,depot_physique',
            'pickup_address' => 'required_if:delivery_mode,collecte_livraison|nullable|string|max:255',
            'pickup_phone' => 'required_if:delivery_mode,collecte_livraison|nullable|string|max:10',
            'items' => 'required|array|min:1',
            'items.*.clothes_type_id' => 'required|exists:clothes_types,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Pas connecté : on garde la commande en attente, et on redirige vers l'auth
        if (! auth()->check()) {
            session(['pending_order' => $validated]);

            return redirect()->route('login')
                ->with('status', 'Connectez-vous ou créez un compte pour finaliser votre commande.');
        }

        $order = $this->createOrderForUser(auth()->user(), $validated);

        return redirect()->route('client.orders.show', $order)
            ->with('success', 'Commande créée avec succès. Code de suivi : ' . $order->tracking_code);
    }

    /**
     * Logique de création, réutilisée ici et après connexion (voir listener plus bas).
     */
    public function createOrderForUser($user, array $validated): Order
    {
        return DB::transaction(function () use ($user, $validated) {
            $order = Order::create([
                'user_id' => $user->id,
                'service_id' => $validated['service_id'],
                'delivery_mode' => $validated['delivery_mode'],
                'statut' => 'en_attente',
                'pickup_phone' => $validated['pickup_phone'] ?? null,
                'pickup_adress' => $validated['pickup_address'] ?? null,
                'deposit_deadline' => now()->addDays(7),
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $unitPrice = ServiceClothesPrice::where('service_id', $validated['service_id'])
                    ->where('clothes_type_id', $item['clothes_type_id'])
                    ->value('price');

                // Garde-fou : si le prix a disparu entre-temps (désactivé, etc.), on ignore cet item
                if (! $unitPrice) {
                    continue;
                }

                $subtotal = $unitPrice * $item['quantity'];
                $total += $subtotal;

                $order->items()->create([
                    'service_id' => $validated['service_id'],
                    'clothes_type_id' => $item['clothes_type_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_amount' => $total]);

            return $order;
        });
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
