<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::role('client')->inRandomOrder()->first()?->id,
            'tracking_code' => 'PRS-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)),
            'statut' => fake()->randomElement(['en_attente', 'reçue', 'en_lavage', 'terminee', 'livree', 'annulee']),
            'delivery_mode' => fake()->randomElement(['collecte_livraison', 'depot_physique']),
            'total_amount' =>  fake()->numberBetween(3000, 30000), // recalculé après création des items
            'deposit_deadline' => now()->addDays(7),
        ];
    }
}
