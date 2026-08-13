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
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;
use App\Exports\ExpensesExport;
use App\Exports\ProfitLossExport;
use App\Exports\InventoryExport;
use App\Exports\CustomersExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    private function getRange(Request $request): array
    {
        return ProfitService::resolveRange($request);
    }

    // ── SALES ──────────────────────────────────────────────

    private function buildSalesData(Request $request): array
    {
        [$start, $end, $rangeLabel] = $this->getRange($request);

        $ordersQuery = Order::with('customer')
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$start, $end]);

        $totalRevenue = (clone $ordersQuery)->sum('total_amount');
        $totalOrders = (clone $ordersQuery)->count();

        $outstandingAmount = (clone $ordersQuery)->where('payment_status', 'unpaid')->sum('total_amount');
        $outstandingCount = (clone $ordersQuery)->where('payment_status', 'unpaid')->count();

        $topProducts = OrderItem::whereHas('order', function ($query) use ($start, $end) {
                $query->where('status', '!=', 'cancelled')
                      ->whereBetween('created_at', [$start, $end]);
            })
            ->selectRaw('product_name, SUM(quantity) as total_qty, SUM(unit_price * quantity) as total_revenue')
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return compact('ordersQuery', 'totalRevenue', 'totalOrders', 'topProducts', 'rangeLabel', 'outstandingAmount', 'outstandingCount');
    }

    public function sales(Request $request)
    {
        $data = $this->buildSalesData($request);

        $orders = $data['ordersQuery']->latest()->paginate(20)->withQueryString();

        return view('reports.sales', [
            'orders' => $orders,
            'totalRevenue' => $data['totalRevenue'],
            'totalOrders' => $data['totalOrders'],
            'topProducts' => $data['topProducts'],
            'rangeLabel' => $data['rangeLabel'],
            'outstandingAmount' => $data['outstandingAmount'],
            'outstandingCount' => $data['outstandingCount'],
        ]);
    }

    public function salesPdf(Request $request)
    {
        $data = $this->buildSalesData($request);
        $orders = $data['ordersQuery']->latest()->get();

        $pdf = Pdf::loadView('reports.pdf.sales', [
            'orders' => $orders,
            'totalRevenue' => $data['totalRevenue'],
            'totalOrders' => $data['totalOrders'],
            'topProducts' => $data['topProducts'],
            'rangeLabel' => $data['rangeLabel'],
            'outstandingAmount' => $data['outstandingAmount'],
            'outstandingCount' => $data['outstandingCount'],
        ]);

        return $pdf->download('sales-report.pdf');
    }

    public function salesExcel(Request $request)
    {
        $data = $this->buildSalesData($request);
        $orders = $data['ordersQuery']->latest()->get();

        return Excel::download(new SalesExport($orders), 'sales-report.xlsx');
    }

    // ── EXPENSES ───────────────────────────────────────────

    private function buildExpensesData(Request $request): array
    {
        [$start, $end, $rangeLabel] = $this->getRange($request);

        $expensesQuery = Expense::with('expenseCategory')->whereBetween('expense_date', [$start, $end]);

        $totalExpenses = (clone $expensesQuery)->sum('amount');

        $byCategory = (clone $expensesQuery)
            ->selectRaw('expense_category_id, SUM(amount) as total')
            ->with('expenseCategory')
            ->groupBy('expense_category_id')
            ->orderByDesc('total')
            ->get();

        return compact('expensesQuery', 'totalExpenses', 'byCategory', 'rangeLabel');
    }

    public function expenses(Request $request)
    {
        $data = $this->buildExpensesData($request);

        $expenses = $data['expensesQuery']->orderByDesc('expense_date')->paginate(20)->withQueryString();

        return view('reports.expenses', [
            'expenses' => $expenses,
            'totalExpenses' => $data['totalExpenses'],
            'byCategory' => $data['byCategory'],
            'rangeLabel' => $data['rangeLabel'],
        ]);
    }

    public function expensesPdf(Request $request)
    {
        $data = $this->buildExpensesData($request);
        $expenses = $data['expensesQuery']->orderByDesc('expense_date')->get();

        $pdf = Pdf::loadView('reports.pdf.expenses', [
            'expenses' => $expenses,
            'totalExpenses' => $data['totalExpenses'],
            'byCategory' => $data['byCategory'],
            'rangeLabel' => $data['rangeLabel'],
        ]);

        return $pdf->download('expense-report.pdf');
    }

    public function expensesExcel(Request $request)
    {
        $data = $this->buildExpensesData($request);
        $expenses = $data['expensesQuery']->orderByDesc('expense_date')->get();

        return Excel::download(new ExpensesExport($expenses), 'expense-report.xlsx');
    }

    // ── PROFIT & LOSS ──────────────────────────────────────

    private function buildProfitLossData(Request $request, ProfitService $profitService): array
    {
        [$start, $end, $rangeLabel] = $this->getRange($request);

        $summary = $profitService->summaryForRange($start, $end);

        $daySpan = $start->diffInDays($end);
        $breakdown = [];
        $truncated = false;

        if ($daySpan <= 31) {
            $cursor = $start->copy();
            while ($cursor <= $end) {
                $daySummary = $profitService->summaryForRange($cursor->copy()->startOfDay(), $cursor->copy()->endOfDay());
                $breakdown[] = ['label' => $cursor->format('M d'), 'summary' => $daySummary];
                $cursor->addDay();
            }
        } else {
            $cursor = $start->copy()->startOfMonth();
            $endMonth = $end->copy()->startOfMonth();
            $monthCount = 0;

            while ($cursor <= $endMonth) {
                $monthCount++;
                if ($monthCount > 24) {
                    $truncated = true;
                    break;
                }
                $monthSummary = $profitService->summaryForRange($cursor->copy()->startOfMonth(), $cursor->copy()->endOfMonth());
                $breakdown[] = ['label' => $cursor->format('M Y'), 'summary' => $monthSummary];
                $cursor->addMonth();
            }

            $breakdown = array_reverse($breakdown);
        }

        return compact('summary', 'breakdown', 'rangeLabel', 'truncated');
    }

    public function profitLoss(Request $request, ProfitService $profitService)
    {
        $data = $this->buildProfitLossData($request, $profitService);

        return view('reports.profit-loss', $data);
    }

    public function profitLossPdf(Request $request, ProfitService $profitService)
    {
        $data = $this->buildProfitLossData($request, $profitService);

        $pdf = Pdf::loadView('reports.pdf.profit-loss', $data);

        return $pdf->download('profit-loss-report.pdf');
    }

    public function profitLossExcel(Request $request, ProfitService $profitService)
    {
        $data = $this->buildProfitLossData($request, $profitService);

        return Excel::download(new ProfitLossExport($data['summary'], $data['breakdown']), 'profit-loss-report.xlsx');
    }

    // ── INVENTORY ──────────────────────────────────────────

    private function buildInventoryData(): array
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $ingredients = Ingredient::where('is_active', true)->orderBy('name')->get();

        $totalProductValue = $products->sum(fn ($p) => $p->stock_quantity * $p->selling_price);
        $totalIngredientValue = $ingredients->sum('total_stock_value');

        return compact('products', 'ingredients', 'totalProductValue', 'totalIngredientValue');
    }

    public function inventory()
    {
        return view('reports.inventory', $this->buildInventoryData());
    }

    public function inventoryPdf()
    {
        $pdf = Pdf::loadView('reports.pdf.inventory', $this->buildInventoryData());

        return $pdf->download('inventory-report.pdf');
    }

    public function inventoryExcel()
    {
        $data = $this->buildInventoryData();

        return Excel::download(new InventoryExport($data['products'], $data['ingredients']), 'inventory-report.xlsx');
    }

    // ── CUSTOMERS ──────────────────────────────────────────

    private function buildCustomersData(Request $request)
    {
        [$start, $end, $rangeLabel] = $this->getRange($request);

        $customers = Customer::withCount(['orders' => function ($query) use ($start, $end) {
                $query->where('status', '!=', 'cancelled')->whereBetween('created_at', [$start, $end]);
            }])
            ->withSum(['orders' => function ($query) use ($start, $end) {
                $query->where('status', '!=', 'cancelled')->whereBetween('created_at', [$start, $end]);
            }], 'total_amount')
            ->having('orders_count', '>', 0)
            ->orderByDesc('orders_sum_total_amount');

        return [$customers, $rangeLabel];
    }

    public function customers(Request $request)
    {
        [$customersQuery, $rangeLabel] = $this->buildCustomersData($request);

        $customers = $customersQuery->paginate(20)->withQueryString();

        return view('reports.customers', compact('customers', 'rangeLabel'));
    }

    public function customersPdf(Request $request)
    {
        [$customersQuery, $rangeLabel] = $this->buildCustomersData($request);
        $customers = $customersQuery->get();

        $pdf = Pdf::loadView('reports.pdf.customers', compact('customers', 'rangeLabel'));

        return $pdf->download('customer-purchase-report.pdf');
    }

    public function customersExcel(Request $request)
    {
        [$customersQuery, $rangeLabel] = $this->buildCustomersData($request);
        $customers = $customersQuery->get();

        return Excel::download(new CustomersExport($customers), 'customer-purchase-report.xlsx');
    }
}