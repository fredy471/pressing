<?php

namespace Database\Factories;

use App\Models\ServiceClothesPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceClothesPrice>
 */
class ServiceClothesPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => fake()->numberBetween(300, 2000),
        ];
    }
}
