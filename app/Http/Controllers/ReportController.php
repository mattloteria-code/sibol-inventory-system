<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Customer;
use App\Services\ProfitService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    private function getRange(Request $request): array
    {
        $period = $request->get('period', 'monthly');
        [$start, $end] = ProfitService::resolveDateRange($period);

        $labels = [
            'daily' => 'Today',
            'weekly' => 'This Week',
            'monthly' => 'This Month',
            'yearly' => 'This Year',
            'all' => 'All Time',
        ];

        return [$period, $start, $end, $labels[$period] ?? 'All Time'];
    }

    public function sales(Request $request)
    {
        [$period, $start, $end, $rangeLabel] = $this->getRange($request);

        $orders = Order::with('customer')
        ->where('status', '!=', 'cancelled')
        ->whereBetween('created_at', [$start, $end])
        ->latest()
        ->paginate(20)
        ->withQueryString();

        $totalRevenue = Order::where('status', '!=', 'cancelled')
        ->whereBetween('created_at', [$start, $end])
        ->sum('total_amount');

        $totalOrders = Order::where('status', '!=', 'cancelled')
        ->whereBetween('created_at', [$start, $end])
        ->count();

        $topProducts = OrderItem::whereHas('order', function ($query) use ($start, $end) {
            $query->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$start, $end]);
        })
        ->selectRaw('product_name, SUM(quantity) as total_qty, SUM(unit_price * quantity) as total_revenue')
        ->groupBy('product_name')
        ->orderByDesc('total_qty')
        ->take(5)
        ->get();
        
        return view('reports.sales', compact('orders', 'totalRevenue', 'totalOrders', 'topProducts', 'period', 'rangeLabel'));
    }

    public function expenses(Request $request)
    {
        [$period, $start, $end, $rangeLabel] = $this->getRange($request);

        $expenses = Expense::with('expenseCategory')
        ->whereBetween('expense_date', [$start, $end])
        ->orderByDesc('expense_date')
        ->paginate(20)
        ->withQueryString();

        $totalExpenses = Expense::whereBetween('expense_date', [$start, $end])->sum('amount');

        $byCategory = Expense::whereBetween('expense_date', [$start, $end])
        ->selectRaw('expense_category_id, SUM(amount) as total')
        ->with('expenseCategory')
        ->groupBy('expense_category_id')
        ->orderByDesc('total')
        ->get();

        return view('reports.expenses', compact('expenses', 'totalExpenses', 'byCategory', 'period', 'rangeLabel'));
    }

    public function profitLoss(Request $request, ProfitService $profitService)
    {
        [$period, $start, $end, $rangeLabel] = $this->getRange($request);

        $summary = $profitService->summaryForRange($start, $end);

        $breakdown = [];
        $truncated = false;

        if (in_array($period, ['daily', 'weekly'])) {
            $cursor = $start->copy();
            while ($cursor <= $end) {
                $daySummary = $profitService->summaryForRange($cursor->copy()->startOfDay(), $cursor->copy()->endOfDay());
                $breakdown[] = ['label' => $cursor->format('M d'), 'summary' => $daySummary];
                $cursor->addDay();
            }
        } else {
            $cursor = $start->copy()->startOfMonth();
            $endMonth = $end->copy()->startOfMonth();
            while ($cursor <= $endMonth) {
                $monthSummary = $profitService->summaryForRange($cursor->copy()->startOfMonth(), $cursor->copy()->endOfMonth());
                $breakdown[] = ['label' => $cursor->format('M Y'), 'summary' => $monthSummary];
                $cursor->addMonth();
            }

            $breakdown = array_reverse($breakdown);
        }

        return view('reports.profit-loss', compact('summary', 'breakdown', 'period', 'rangeLabel', 'truncated'));
    }

    public function inventory()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $ingredients = Ingredient::where('is_active', true)->orderBy('name')->get();

        $totalProductValue = $products->sum(fn ($p) => $p->stock_quantity * $p->selling_price);
        $totalIngredientValue = $ingredients->sum('total_stock_value');

        return view('reports.inventory', compact('products', 'ingredients', 'totalProductValue', 'totalIngredientValue'));
    }

    public function customers(Request $request)
    {
        [$period, $start, $end, $rangeLabel] = $this->getRange($request);

        $customers = Customer::withCount(['orders' => function ($query) use ($start, $end) {
            $query->where('status', '!=', 'cancelled')->whereBetween('created_at', [$start, $end]);
        }])
        ->withSum(['orders' => function ($query) use ($start, $end) {
            $query->where('status', '!=', 'cancelled')->whereBetween('created_at', [$start, $end]);
        }], 'total_amount')
        ->having('orders_count', '>', 0)
        ->orderByDesc('orders_sum_total_amount')
        ->paginate(20)
        ->withQueryString();

        return view('reports.customers', compact('customers', 'period', 'rangeLabel'));
    }
}
