<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\ProductionBatchIngredient;
use App\Models\IngredientPurchase;
use App\Models\IngredientPriceHistory;
use App\Support\AuditContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductionService
{
    /**
     * @param array $ingredientQuantities  [ingredient_id => quantity_used, ...]
     */
    public function produceBatch(Product $product, array $ingredientQuantities, int $actualQuantityProduced, ?string $notes = null): ProductionBatch
    {
        return DB::transaction(function () use ($product, $ingredientQuantities, $actualQuantityProduced, $notes) {

            // Step 1: Verify sufficient stock for every ingredient before writing anything
            foreach ($ingredientQuantities as $ingredientId => $quantityNeeded) {
                $ingredient = \App\Models\Ingredient::find($ingredientId);

                if (!$ingredient || $ingredient->current_stock < $quantityNeeded) {
                    $name = $ingredient->name ?? "Ingredient #{$ingredientId}";
                    $available = $ingredient->current_stock ?? 0;

                    throw ValidationException::withMessages([
                        'ingredients' => "Not enough {$name}. Need {$quantityNeeded}, only {$available} available.",
                    ]);
                }
            }

            // Step 2: Create the batch shell
            $batch = ProductionBatch::create([
                'product_id' => $product->id,
                'quantity_produced' => $actualQuantityProduced,
                'remaining_quantity' => $actualQuantityProduced,
                'total_cost' => 0,
                'cost_per_unit' => 0,
                'produced_at' => now(),
                'notes' => $notes,
            ]);

            $batchTotalCost = 0;

            // Step 3: FIFO consumption per ingredient, using the ACTUAL quantities entered
            foreach ($ingredientQuantities as $ingredientId => $quantityNeeded) {
                $ingredient = \App\Models\Ingredient::find($ingredientId);
                $remainingToConsume = $quantityNeeded;

                $purchases = IngredientPurchase::where('ingredient_id', $ingredient->id)
                    ->where('remaining_quantity', '>', 0)
                    ->orderBy('purchase_date')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                foreach ($purchases as $purchase) {
                    if ($remainingToConsume <= 0) {
                        break;
                    }

                    $consumeFromThis = min($purchase->remaining_quantity, $remainingToConsume);
                    $subtotal = $consumeFromThis * $purchase->price_per_base_unit;

                    ProductionBatchIngredient::create([
                        'production_batch_id' => $batch->id,
                        'ingredient_id' => $ingredient->id,
                        'ingredient_purchase_id' => $purchase->id,
                        'quantity_used' => $consumeFromThis,
                        'unit_price_at_time' => $purchase->price_per_base_unit,
                        'subtotal_cost' => $subtotal,
                    ]);

                    AuditContext::without(fn () => $purchase->decrement('remaining_quantity', $consumeFromThis));

                    $batchTotalCost += $subtotal;
                    $remainingToConsume -= $consumeFromThis;
                }

                AuditContext::without(fn () => $ingredient->decrement('current_stock', $quantityNeeded));
                

                $newFront = IngredientPurchase::where('ingredient_id', $ingredient->id)
                    ->where('remaining_quantity', '>', 0)
                    ->orderBy('purchase_date')
                    ->orderBy('id')
                    ->first();

                $newFrontPrice = $newFront ? $newFront->price_per_base_unit : 0;

                if (bccomp($ingredient->current_price_per_base_unit, $newFrontPrice, 4) !== 0) {
                    IngredientPriceHistory::create([
                        'ingredient_id' => $ingredient->id,
                        'old_price' => $ingredient->current_price_per_base_unit,
                        'new_price' => $newFrontPrice,
                        'reason' => "FIFO front shifted after production batch #{$batch->id}",
                        'changed_at' => now(),
                    ]);
                }
                
                AuditContext::without(fn () => $ingredient->update(['current_price_per_base_unit' => $newFrontPrice]));
            }

            // Step 4: Finalize totals — cost per unit uses ACTUAL output, capturing any yield variance
            $batch->update([
                'total_cost' => $batchTotalCost,
                'cost_per_unit' => $batchTotalCost / $actualQuantityProduced,
            ]);

            // Step 5: Add actual finished goods to product stock
            AuditContext::without(fn () => $product->increment('stock_quantity', $actualQuantityProduced));

            return $batch->fresh('batchIngredients.ingredient', 'batchIngredients.purchase');
        });
    }
}