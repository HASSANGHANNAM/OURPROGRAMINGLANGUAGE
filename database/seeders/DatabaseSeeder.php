<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(categorySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(madeBySeeder::class);
        $this->call(ownerSeeder::class);
        $this->call(warehouseSeeder::class);
        $this->call(productsSeeder::class);
        $this->call(SuperAdminSeeder::class);
    }
}
