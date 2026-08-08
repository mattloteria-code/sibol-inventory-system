<?php

namespace App\Services;

use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ProductionBatch;
use App\Models\OrderItemBatch;
use Illuminate\Validation\ValidationException;

class SaleCostService
{
    public function consumeForsale(Product $product, OrderItem $orderItem, int $quantity): float
    {
        $remainingToConsume = $quantity;
        $totalCost = 0;

        $batches = ProductionBatch::where('product_id', $product->id)
        ->where('remaining_quantity', '>', 0)
        ->orderBy('produced_at')
        ->orderBy('id')
        ->lockForUpdate()
        ->get();

        foreach ($batches as $batch) {
            if ($remainingToConsume <= 0) {
                break;
            }

            $consumeFromThis = min($batch->remaining_quantity, $remainingToConsume);
            $cost = $consumeFromThis * $batch->cost_per_unit;

            OrderItemBatch::create([
                'order_item_id' => $orderItem->id,
                'production_batch_id' => $batch->id,
                'quantity' => $consumeFromThis,
                'unit_cost_at_time' => $batch->cost_per_unit,
            ]);

            $batch->decrement('remaining_quantity', $consumeFromThis);

            $totalCost += $cost;
            $remainingToConsume -= $consumeFromThis;
        }

        if ($remainingToConsume > 0) {

            $totalCost += 0;
        }
        
        return $totalCost;
    }

    public function reverseForCancellation(OrderItem $orderItem):void
    {
        foreach ($orderItem->batchConsumptions as $consumption) {
            $consumption->batch->increment('remaining_quantity', $consumption->quantity);
            $consumption->delete();
        }
    }
}