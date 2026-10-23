<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PendingOrder;
use App\Models\ServiceClothesPrice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PendingOrderService
{
    public function createFor(User $user, array $data): PendingOrder
    {
        return DB::transaction(function () use ($user, $data) {
            $priced = $this->priceItems($data['items']);

            $pending = PendingOrder::where('user_id', $user->id)
                ->where('status', 'en_attente')
                ->whereNull('fedapay_transaction_id')
                ->latest('id')
                ->lockForUpdate()
                ->first();

            if (! $pending) {
                $pending = new PendingOrder();
                $pending->user_id = $user->id;
                $pending->reference = (string) Str::uuid();
                $pending->status = 'en_attente';
            }

            $pending->payload = [
                ...$data,
                'items' => $priced['items'],
            ];

            $pending->total_amount = $priced['total'];
            $pending->save();

            return $pending;
        });
    }

    public function reprice(PendingOrder $pending): PendingOrder
    {
        $priced = $this->priceItems($pending->payload['items']);

        $pending->payload      = [...$pending->payload, 'items' => $priced['items']];
        $pending->total_amount = $priced['total'];
        $pending->save();

        return $pending;
    }


    private function priceItems(array $items): array
    {
        $merged = collect($items)
            ->groupBy(fn($i) => $i['service_id'] . '-' . $i['clothes_type_id'])
            ->map(fn($g) => [
                'service_id'      => $g->first()['service_id'],
                'clothes_type_id' => $g->first()['clothes_type_id'],
                'quantity'        => $g->sum('quantity'),
            ])->values();

        $prices = ServiceClothesPrice::whereIn('service_id', $merged->pluck('service_id'))
            ->get()->keyBy(fn($p) => $p->service_id . '-' . $p->clothes_type_id);

        $total = 0;
        $priced = $merged->map(function ($i) use ($prices, &$total) {
            $row = $prices->get($i['service_id'] . '-' . $i['clothes_type_id']);
            if (! $row) {
                throw ValidationException::withMessages(['items' => 'Un des articles n\'est plus disponible.']);
            }
            $total += $row->price * $i['quantity'];
            return $i + ['unit_price' => $row->price];
        })->all();

        return ['items' => $priced, 'total' => $total];
    }
}
