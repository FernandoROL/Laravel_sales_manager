<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Product;
use App\Models\Salles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_install_opens_the_empty_dashboard(): void
    {
        $this->get('/')->assertRedirect('/dashboard');
        $this->get('/dashboard')->assertOk()
            ->assertSee('Nenhuma venda registrada')
            ->assertSee('Nenhum produto vendido neste mês')
            ->assertViewHas('totalSales', 0);
    }

    public function test_monthly_metrics_and_recent_sales_use_the_recorded_sales(): void
    {
        $this->travelTo(Carbon::parse('2026-05-15 12:00:00'));
        $client = Client::create(['name' => 'Cliente de teste']);
        $product = Product::create(['name' => 'Caderno', 'price' => 20]);

        for ($number = 1; $number <= 6; $number++) {
            $sale = new Salles(['salleNumber' => $number, 'productId' => $product->id, 'clientId' => $client->id]);
            $sale->created_at = $number === 1 ? now()->subMonth() : now();
            $sale->save();
        }

        $this->get('/dashboard')->assertOk()
            ->assertViewHas('totalSales', 6)
            ->assertViewHas('summary', fn ($s) => (int) $s->sales === 5 && (int) $s->buyers === 1)
            ->assertViewHas('topProducts', fn ($rows) => $rows->count() === 1 && (int) $rows->first()->sales === 5)
            ->assertViewHas('recentSales', fn ($rows) => $rows->pluck('salleNumber')->all() === [6, 5, 4, 3, 2])
            ->assertSee('Cliente de teste')->assertSee('Caderno');
    }
}
