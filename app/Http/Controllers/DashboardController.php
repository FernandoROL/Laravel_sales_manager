<?php

namespace App\Http\Controllers;

use App\Models\Salles;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $start = now()->startOfMonth();
        $monthly = Salles::query()
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $start->copy()->addMonth());

        $summary = (clone $monthly)
            ->selectRaw('COUNT(*) AS sales, COUNT(DISTINCT clientId) AS buyers')
            ->first();

        $topProducts = (clone $monthly)
            ->select('productId')
            ->selectRaw('COUNT(*) AS sales')
            ->with('product:id,name')
            ->groupBy('productId')
            ->orderByDesc('sales')
            ->orderBy('productId')
            ->limit(3)
            ->get();

        $recentSales = Salles::with(['client:id,name', 'product:id,name'])
            ->latest('created_at')->orderByDesc('id')->limit(5)->get();

        return view('pages.dashboard.dashboard', [
            'totalSales' => Salles::count(),
            'summary' => $summary,
            'monthLabel' => $start->locale('pt_BR')->translatedFormat('F \\d\\e Y'),
            'topProducts' => $topProducts,
            'recentSales' => $recentSales,
        ]);
    }
}
