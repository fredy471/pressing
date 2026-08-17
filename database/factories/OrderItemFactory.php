<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\ClothesType;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $clothesType = ClothesType::inRandomOrder()->first();
        $quantity = fake()->numberBetween(1, 5);

        return [

            'clothes_type_id' => $clothesType->id,
            'service_id' => Service::inRandomOrder()->first()->id,
            'quantity' => $quantity,
            'unit_price' => 0, // sera écrasé dans le seeder, car dépend du service de la commande parente
            'subtotal' => 0,
        ];
    }
}
