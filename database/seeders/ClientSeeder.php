<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::create(
            [
                'name' => 'SeederTest',
                'email'=> 'seeder@seed.com',
                'adress' => 'Rd. seedertest',
                'road'=> 'Rd. seedertest',
                'zip'=> '0000000',
                'neighborhood'=> 'Test Seeder',
            ]
            );
    }
}
