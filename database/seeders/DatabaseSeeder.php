<?php

namespace Database\Seeders;

use App\Models\ClothesType;
use App\Models\User;
use App\Models\Service;
use App\Models\ServiceClothesPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
        ]);

        Service::factory()->count(4)->create();
        ClothesType::factory()->count(5)->create();

        $user = User::create([
            "name" => "user1",
            "email" => "user@example.com",
            "phone" => "0190909090",
            "password" => Hash::make('1234'),
        ]);
        $user->assignRole('client');

        $admin = User::create([
            "name" => "admin",
            "email" => "admin@example.com",
            "phone" => "0194949494",
            "password" => hash::make('1234'),
        ]);
        $admin->assignRole('admin');


        foreach (Service::all() as $service) {
            foreach (ClothesType::all() as $clothesType) {
                ServiceClothesPrice::create([
                    'service_id' => $service->id,
                    'clothes_type_id' => $clothesType->id,
                    'price' => fake()->numberBetween(300, 2000),
                ]);
            }
        }
    }
}
