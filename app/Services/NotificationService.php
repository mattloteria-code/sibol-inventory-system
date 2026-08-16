<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Notification;

class NotificationService
{
    public function checkProductLowStock(Product $product): void
    {
        if ($product->stock_quantity > $product->low_stock_threshold || !$product->is_active) {
            return;
        }

        if (Notification::existsUnreadFor('low_stock_product', Product::class, $product->id)) {
            return;
        }

        Notification::create([
            'type' => 'low_stock_product',
            'title' => 'Low Stock: ' . $product->name,
            'message' => "{$product->name} is down to {$product->stock_quantity} units (threshold: {$product->low_stock_threshold}).",
            'link' => route('products.index'),
            'related_type' => Product::class,
            'related_id' => $product->id,
            'created_at' => now(),
        ]);
    }

    public function checkIngredientLowStock(Ingredient $ingredient): void
    {
        if ($ingredient->current_stock > $ingredient->low_stock_threshold || !$ingredient->is_active) {
            return;
        }

        if (Notification::existsUnreadFor('low_stock_ingredient', Ingredient::class, $ingredient->id)) {
            return;
        }

        Notification::create([
            'type' => 'low_stock_ingredient',
            'title' => 'Low Stock: ' . $ingredient->name,
            'message' => "{$ingredient->name} is down to " . number_format($ingredient->current_stock) . " {$ingredient->base_unit}.",
            'link' => route('ingredients.index'),
            'related_type' => Ingredient::class,
            'related_id' => $ingredient->id,
            'created_at' => now(),
        ]);
    }
}