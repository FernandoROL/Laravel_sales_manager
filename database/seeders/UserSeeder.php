<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{

    public function run() {
        User::create([
            "name"=> "Fernando",
            "email"=> "terrariando@hotmail.com",
            "password"=> Hash::make('@password'),
        ]);
    }
}
