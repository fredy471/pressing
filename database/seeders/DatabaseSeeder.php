<?php

namespace Database\Seeders;

use App\Models\ClothesType;
use App\Models\User;
use App\Models\Service;
use App\Models\ServiceClothesPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\OrderItem;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        User::factory()->client()->count(50)->create();
        User::factory()->agent()->count(10)->create();
        Service::factory()->count(2)->create();
        ClothesType::factory()->count(5)->create();

        $agents = User::create([
            "name" => "cololo",
            "email" => "cololo@example.com",
            "phone" => "0188888888",
            "password" => Hash::make('1234'),
        ]);

        $agents->assignRole('agent');

        $admin = User::create([
            "name" => "admin",
            "email" => "admin@example.com",
            "phone" => "0177777777",
            "password" => Hash::make('12345'),
        ]);

        $admin->assignRole('admin');

        // Création des prix pour chaque combinaison service / vêtement
        foreach (Service::all() as $service) {
            foreach (ClothesType::all() as $clothesType) {
                ServiceClothesPrice::create([
                    'service_id' => $service->id,
                    'clothes_type_id' => $clothesType->id,
                    'price' => fake()->numberBetween(550, 7200),
                ]);
            }
        }

        // Création des commandes
        Order::factory()->count(30)->create()->each(function ($order) {

            $itemCount = fake()->numberBetween(1, 3);
            $total = 0;

            for ($i = 0; $i < $itemCount; $i++) {

                // Chaque article peut avoir un service différent
                $service = Service::inRandomOrder()->first();

                $priceEntry = ServiceClothesPrice::where('service_id', $service->id)
                    ->inRandomOrder()
                    ->first();

                if (!$priceEntry) {
                    continue;
                }

                $quantity = fake()->numberBetween(1, 5);
                $subtotal = $priceEntry->price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'service_id' => $service->id,
                    'clothes_type_id' => $priceEntry->clothes_type_id,
                    'quantity' => $quantity,
                    'unit_price' => $priceEntry->price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update([
                'total_amount' => $total
            ]);
        });
    }
}