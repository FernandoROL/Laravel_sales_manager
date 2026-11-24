<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Product;
use App\Models\Salles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $client = Client::firstOrCreate(
                ['email' => 'demo@example.test'],
                ['name' => 'Cliente de demonstração']
            );
            $product = Product::firstOrCreate(['name' => 'Caderno de demonstração'], ['price' => 24.90]);

            Salles::firstOrCreate(
                ['clientId' => $client->id, 'productId' => $product->id],
                ['salleNumber' => Salles::max('salleNumber') + 1]
            );
        });
    }
}
