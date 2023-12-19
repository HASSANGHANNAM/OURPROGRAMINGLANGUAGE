<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\location;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\phatmacist;

class phatmacistSeeder extends Seeder
{
    public function run(): void
    {
        $locationData = [
            'city_id' => 1,
            'address' => "8 march",
        ];
        $location = location::create($locationData);
        $user['type'] = 2;
        $user['wallet'] = 10000;
        $user['password'] = Hash::make("123456789");
        $user = User::create($user);
        $phatmacistData = [
            'Full_name' => "omar",
            'Pharmacy_name' => "alnoor",
            'location_id' => $location['id'],
            'user_id' => $user['id']
        ];
        $phatmacist = phatmacist::create($phatmacistData);
        $accessToken = $user->createToken('auth_token')->plainTextToken;
    }
}
