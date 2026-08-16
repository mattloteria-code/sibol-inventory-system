<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\IngredientPurchase;
use App\Services\ProfitService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(ProfitService $profitService)
    {
        [$monthStart, $monthEnd] = ProfitService::resolveDateRange('monthly');
        $monthlySummary = $profitService->summaryForRange($monthStart, $monthEnd);
        $ingredientSpendThisMonth = IngredientPurchase::whereBetween('purchase_date', [$monthStart, $monthEnd])->sum('total_cost');

        $pendingOrders = Order::whereIn('status', ['pending', 'processing'])->count();
        $completedOrders = Order::where('status', 'completed')->count();

        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')
        ->where('is_active', true)
        ->count();

        $lowStockIngredients = \App\Models\Ingredient::whereColumn('current_stock', '<=', 'low_stock_threshold')
        ->where('is_active', true)
        ->count();

        $salesTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $dayRevenue = OrderItem::whereHas('order', function ($query) use ($day) {
                $query->where('status', '!=', 'cancelled')
                ->whereDate('created_at', $day);
            })
            ->get()
            ->sum(fn ($item) => $item->unit_price * $item->quantity);

            $salesTrend[] = [
                'label' => $day->format('M d'),
                'value' => round($dayRevenue, 2),
            ];
        }

        $recentOrders = Order::with('customer')->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'monthlySummary',
            'pendingOrders',
            'completedOrders',
            'lowStockProducts',
            'lowStockIngredients',
            'salesTrend',
            'recentOrders',
            'ingredientSpendThisMonth'
        ));
    }
}
