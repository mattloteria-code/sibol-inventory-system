<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Expense;
use App\Models\Order;
use Illuminate\Support\Carbon;

class ProfitService
{
    public function summaryForRange(Carbon $start, Carbon $end): array
    {
        $orderItems = OrderItem::whereHas('order', function ($query) use ($start, $end) {
            $query->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$start, $end]);
        })
        ->get();

        $revenue = $orderItems->sum(fn ($item) => $item->unit_price * $item->quantity);
        $cogs = $orderItems->sum(fn ($item) => $item->unit_cost * $item->quantity);
        $grossProfit = $revenue - $cogs;

        $operatingExpenses = Expense::whereBetween('expense_date', [$start, $end])->sum('amount');

        $netProfit = $grossProfit - $operatingExpenses;

        $outstandingAmount = Order::where('status', '!=', 'cancelled')
            ->where('payment_status', 'unpaid')
            ->whereBetween('created_at', [$start, $end])
            ->sum('total_amount');

        $outstandingCount = Order::where('status', '!=', 'cancelled')
            ->where('payment_status', 'unpaid')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        return [
            'revenue' => round($revenue, 2),
            'cogs' => round($cogs, 2),
            'gross_profit' => round($grossProfit, 2),
            'operating_expenses' => round($operatingExpenses, 2),
            'net_profit' => round($netProfit, 2),
            'order_count' => $orderItems->pluck('order_id')->unique()->count(),
            'outstanding_amount' => round($outstandingAmount, 2),
            'outstanding_count' => $outstandingCount,
        ];
    }

    public static function resolveDateRange(string $period): array
    {
        $today = Carbon::today();

        return match ($period) {
            'daily' => [$today->copy()->startOfDay(), $today->copy()->endOfDay()],
            'weekly' => [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()],
            'monthly' => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()],
            'yearly' => [$today->copy()->startOfYear(), $today->copy()->endOfYear()],
            default => [self::earliestActivityDate(), $today->copy()->endOfDay()],
        };
    }

    public static function resolveRange(\Illuminate\Http\Request $request): array
    {
        if ($request->filled('date')) {
            $day = Carbon::parse($request->date);
            return [$day->copy()->startOfDay(), $day->copy()->endOfDay(), $day->format('M d, Y')];
        }

        if ($request->filled('month')) {
            $month = Carbon::createFromFormat('Y-m', $request->month);
            return [$month->copy()->startOfMonth(), $month->copy()->endOfMonth(), $month->format('F Y')];
        }

        if ($request->filled('year')) {
            $year = Carbon::createFromFormat('Y', $request->year);
            return [$year->copy()->startOfYear(), $year->copy()->endOfYear(), $year->format('Y')];
        }

        $period = $request->get('period', 'monthly');
        [$start, $end] = self::resolveDateRange($period);

        $labels = [
            'daily' => 'Today', 
            'weekly' => 'This Week',
            'monthly' => 'This Month', 
            'yearly' => 'This Year', 
            'all' => 'All Time',
        ];

        return [$start, $end, $labels[$period] ?? 'All Time'];
    }

    public static function earliestActivityDate(): Carbon
    {
        $earliestOrder = Order::oldest('created_at')->value('created_at');
        $earliestExpense = Expense::oldest('expense_date')->value('expense_date');

        $dates = collect([$earliestOrder, $earliestExpense])->filter();

        return $dates->isEmpty() ? Carbon::today() : Carbon::parse($dates->min());
    }
}