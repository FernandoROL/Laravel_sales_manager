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
                'email' => 'seeder@seed.com',
                'adress' => 'Rd. seedertest',
                'road' => 'Rd. seedertest',
                'zip' => '0000000',
                'neighborhood' => 'Test Seeder',
            ]
        );
        Client::create(
            [
                'name' => 'Test22',
                'email' => 'jjerdf@mail.com',
                'adress' => 'Rd. jsjsjsj',
                'road' => 'Rd. lklklk',
                'zip' => '4434343',
                'neighborhood' => 'Test djwajdawj',
            ]
        );
    }
}
