<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\owner;

class ownerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user['type'] = 1;
        $user['wallet'] = 10000;
        $user['password'] = Hash::make("12345");
        $user = User::create($user);
        $ownerData = [
            'status' => "acceptable",
            'user_id' => $user['id']
        ];
        $owner = owner::create($ownerData);
    }
}
