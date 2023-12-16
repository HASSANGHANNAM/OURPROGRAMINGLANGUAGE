<?php

namespace Database\Seeders;

use App\Models\super_admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = ([
            "Email_address" => "admin@admin@gmail.com",
            "Phone_number" => "0934519102",
            "password" => "admin12345"
        ]);
        $user['type'] = 3;
        $user['wallet'] = 10000000;
        $user['password'] = Hash::make($user['password']);
        $user = User::create($user);
        $superAdmin = [
            'user_id' => $user['id']
        ];
        $superAdmin = super_admin::create($superAdmin);
        $accessToken = $user->createToken('authToken')->accessToken;
    }
}
