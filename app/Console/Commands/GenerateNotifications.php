<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Notification;
use App\Services\ProfitService;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GenerateNotifications extends Command
{
    protected $signature = 'notifications:generate';
    protected $description = 'Check for low stock, pending orders, and generate daily summary notifications';

    public function handle(NotificationService $notificationService)
    {
        $this->checkLowStockProducts($notificationService);
        $this->checkLowStockIngredients($notificationService);
        $this->checkPendingOrders();
        $this->generateDailySummary();

        $this->info('Notifications generated.');
    }

    private function checkLowStockProducts(NotificationService $notificationService): void
    {
        Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->where('is_active', true)
            ->each(fn ($product) => $notificationService->checkProductLowStock($product));
    }

    private function checkLowStockIngredients(NotificationService $notificationService): void
    {
        Ingredient::whereColumn('current_stock', '<=', 'low_stock_threshold')
            ->where('is_active', true)
            ->each(fn ($ingredient) => $notificationService->checkIngredientLowStock($ingredient));
    }

    private function checkPendingOrders(): void
    {
        $cutoff = Carbon::now()->subHours(12);

        $staleOrders = Order::where('status', 'pending')
            ->where('created_at', '<=', $cutoff)
            ->get();

        foreach ($staleOrders as $order) {
            if (Notification::existsUnreadFor('pending_order', Order::class, $order->id)) {
                continue;
            }

            Notification::create([
                'type' => 'pending_order',
                'title' => 'Pending Order: ' . $order->order_number,
                'message' => "Order {$order->order_number} has been pending for over 12 hours.",
                'link' => route('orders.show', $order),
                'related_type' => Order::class,
                'related_id' => $order->id,
                'created_at' => now(),
            ]);
        }
    }

    private function generateDailySummary(): void
    {
        $today = Carbon::today();

        $alreadyExists = Notification::where('type', 'daily_summary')
            ->whereDate('created_at', $today)
            ->exists();

        if ($alreadyExists) {
            return;
        }

        $profitService = app(ProfitService::class);
        $summary = $profitService->summaryForRange($today->copy()->startOfDay(), $today->copy()->endOfDay());

        Notification::create([
            'type' => 'daily_summary',
            'title' => 'Daily Sales Summary',
            'message' => "Today: ₱" . number_format($summary['revenue'], 2) . " revenue from {$summary['order_count']} order(s). Net profit so far: ₱" . number_format($summary['net_profit'], 2) . ".",
            'link' => route('dashboard'),
            'related_type' => null,
            'related_id' => null,
            'created_at' => now(),
        ]);
    }
}