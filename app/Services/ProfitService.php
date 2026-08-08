<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Expense;
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

        return [
            'revenue' => round($revenue, 2),
            'cogs' => round($cogs, 2),
            'gross_profit' => round($grossProfit, 2),
            'operating_expenses' => round($operatingExpenses, 2),
            'net_profit' => round($netProfit, 2),
            'order_count' => $orderItems->pluck('order_id')->unique()->count(),
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
            default => [Carbon::createFromTimestamp(0), $today->copy()->endOfDay()],
        };
    }
}