<?php

namespace Database\Factories;

use App\Models\ClothesType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClothesType>
 */
class ClothesTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->unique()->randomElement([
                'Chemise', 'Pantalon', 'Robe', 'Costume', 'veste'
            ]),
        ];
    }
}
