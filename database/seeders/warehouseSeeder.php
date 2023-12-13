<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\location;
use App\Models\warehouse;

class warehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locationData = [
            'city_id' => 1,
            'address' => "alaa",
        ];
        $location = location::create($locationData);
        $warehouseData = [
            'Warehouse_name' => "hassan",
            'location_id' => $location['id'],
            'owner_id' => 1
        ];
        $warehouse = warehouse::create($warehouseData);
    }
}
