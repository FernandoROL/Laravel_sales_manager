<?php

namespace Database\Seeders;

use App\Models\Salles;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    public function run(): void
    {
        Salles::create(
            [
                'salleNumber' => 123456,
                'productId' => 36,
                'clientId' => 11,
            ]
        );

        Salles::create(
            [
                'salleNumber' => 123456,
                'productId' => 36,
                'clientId' => 10,
            ]
        );
    }
}
