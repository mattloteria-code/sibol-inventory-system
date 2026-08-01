<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name',
        'base_unit',
        'current_stock',
        'current_price_per_base_unit',
        'low_stock_threshold',
        'is_active',
    ];

    protected $casts = [
        'current_stock' => 'decimal;3',
        'current_price_per_base_unit' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function priceHistory()
    {
        return $this->hasMany(IngredientPriceHistory::class);
    }

    public function purchases()
    {
        return $this->hasMany(IngredientPurchase::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->low_stock_threshold;
    }
}
