<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Salles;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Client;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = $this->searchClients()[0];
        $lastClient = $this->searchClients()[1];

        $totalProducts = $this->searchProduct()[0];
        $highestValueProduct = $this->searchProduct()[1];
        $productMean = $this->searchProduct()[2];

        $totalSales = $this->searchSales()[0];
        $lastSale = $this->searchSales()[1];
        $mostSold = $this->searchSales()[2];

        $totalUsers = $this->searchUsers()[0];
        $lastRegistered = $this->searchUsers()[1];

        return view("pages.dashboard.dashboard", compact('totalClients', 'lastClient', 'totalProducts', 'highestValueProduct', 'productMean', 'totalSales', 'lastSale', 'mostSold', 'totalUsers', 'lastRegistered'));
    }

    public function searchClients()
    {
        $totalClients = Client::all()->count();
        $lastClient = Client::orderBy('created_at', 'desc')->first()['name'];

        return [$totalClients, $lastClient];
    }

    public function searchProduct()
    {
        $totalProducts = Product::all()->count();
        $highestValueProduct = Product::max('price');
        $productMean = Product::sum('price') / $totalProducts;

        return [$totalProducts, $highestValueProduct, $productMean];
    }

    public function searchSales()
    {
        $totalSales = Salles::all()->count();
        $lastSale = Salles::orderBy('created_at', 'desc')->first()['salleNumber'];
        $mostPopular = Salles::select('productId')
            ->groupBy('productId')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(1)
            ->value('productId');
        $sale = Product::find($mostPopular)->name;

        return [$totalSales, $lastSale, $sale];
    }

    public function searchUsers()
    {
        $totalUsers = User::all()->count();
        $lastRegistered = User::orderBy('created_at', 'desc')->first()['name'];

        return [$totalUsers, $lastRegistered];
    }
}
