<?php

namespace Database\Seeders;

use App\Models\ClothesType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceClothesPrice;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Créer les rôles
        $this->call([
            RoleSeeder::class,
        ]);

        // Créer les utilisateurs fictifs
        User::factory()->client()->count(50)->create();
        User::factory()->agent()->count(10)->create();

        // Créer les services et types de vêtements
        Service::factory()->count(2)->create();
        ClothesType::factory()->count(5)->create();

        // Identifiants configurables pour les comptes de démonstration
        $agentPassword = env('DEMO_AGENT_PASSWORD') ?: Str::random(32);
        $adminPassword = env('DEMO_ADMIN_PASSWORD') ?: Str::random(32);

        // Créer le compte agent de démonstration
        $agent = User::create([
            'name' => 'cololo',
            'email' => 'cololo@example.com',
            'phone' => '0188888888',
            'domicile' => 'cotonou',
            'password' => Hash::make($agentPassword),
        ]);

        $agent->assignRole('agent');

        // Créer le compte administrateur
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'phone' => '0177777777',
            'domicile' => 'calavi',
            'password' => Hash::make($adminPassword),
        ]);

        $admin->assignRole('admin');

        // Créer les prix pour chaque combinaison service / vêtement
        foreach (Service::all() as $service) {
            foreach (ClothesType::all() as $clothesType) {
                ServiceClothesPrice::create([
                    'service_id' => $service->id,
                    'clothes_type_id' => $clothesType->id,
                    'price' => fake()->numberBetween(550, 7200),
                ]);
            }
        }

        // Créer les commandes avec leurs articles
        Order::factory()->count(30)->create()->each(
            function ($order) {
                $itemCount = fake()->numberBetween(1, 3);
                $total = 0;

                for ($i = 0; $i < $itemCount; $i++) {
                    $service = Service::inRandomOrder()->first();

                    if (!$service) {
                        continue;
                    }

                    $priceEntry = ServiceClothesPrice::where(
                        'service_id',
                        $service->id
                    )->inRandomOrder()->first();

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
                    'total_amount' => $total,
                ]);
            }
        );

        // Créer les paiements fictifs
        Payment::factory()->count(30)->create();
    }
}
