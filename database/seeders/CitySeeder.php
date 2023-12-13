<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\city;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            'Damascus',
            'RefDamascus',
            'Quneitra',
            'Daraa',
            'Al-Suwayda',
            'Homs',
            'Tartus',
            'Latakia',
            'Hama',
            'Idlib',
            'Aleppo',
            'Raqqa',
            'Deir ez-Zor',
            'Al-Hasakah',
        ];

        foreach ($cities as $cityName) {
            city::create(['City_name' => $cityName]);
        }
    }
}
//php artisan db:seed --class=CitySeeder