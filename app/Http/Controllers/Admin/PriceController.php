<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceClothesPrice;
use App\Models\Service;
use App\Models\ClothesType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\Admin\UpdatePricesRequest;

class PriceController extends Controller
{
    public function index(): View
    {
        $services = Service::where('is_active', true)->get();
        $clothesTypes = ClothesType::where('is_active', true)->get();

        $prices = ServiceClothesPrice::all()
            ->keyBy(fn($item) => $item->service_id . '_' . $item->clothes_type_id);

        return view('admin.prices.index', compact('services', 'clothesTypes', 'prices'));
    }

    public function update(UpdatePricesRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        foreach ($validated['prices'] as $entry) {
            ServiceClothesPrice::updateOrCreate(
                [
                    'service_id' => $entry['service_id'],
                    'clothes_type_id' => $entry['clothes_type_id'],
                ],
                ['price' => $entry['price']]
            );
        }

        return back()->with('success', 'Tarifs mis à jour avec succès.');
    }
}
