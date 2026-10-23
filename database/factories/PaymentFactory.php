<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $order = Order::inRandomOrder()->first();
        return [
            'order_id' => $order->id,
            'amount' => (int) round($order->total_amount),
            'currency' => 'XOF',
            'method' => fake()->randomElement([
                'mobile_money',
                'carte',
            ]),
            'provider' => 'fedapay',
            'status' => fake()->randomElement([
                'en_attente',
                'en_cours',
                'reussi',
                'echoue',
                'annule',
            ]),
            'provider_reference' => 'TEST-' . fake()->unique()->bothify('##########'),
            'paid_at' => null,
        ];
    }
}
