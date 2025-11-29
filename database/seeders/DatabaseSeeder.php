<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@boltbite.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        $merchant = User::firstOrCreate(
            ['email' => 'merchant@boltbite.com'],
            ['name' => 'Merchant User', 'password' => bcrypt('password'), 'role' => 'merchant']
        );

        User::firstOrCreate(
            ['email' => 'user@boltbite.com'],
            ['name' => 'Regular User', 'password' => bcrypt('password'), 'role' => 'user']
        );

        $this->callWith(\Database\Seeders\RestaurantSeeder::class, ['ownerId' => $merchant->id]);
    }
}
