<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
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
                'Lavage & Repassage',
                'Repassage',
            ]),
            "description" => fake()->sentence(),
            "is_active" =>true,
        ];
    }
}
