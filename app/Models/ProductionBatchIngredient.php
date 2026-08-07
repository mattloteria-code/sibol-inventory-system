<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionBatchIngredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_batch_id',
        'ingredient_id',
        'ingredient_purchase_id',
        'quantity_used',
        'unit_price_at_time',
        'subtotal_cost',
    ];

    protected $casts = [
        'quantity_used' => 'decimal:3',
        'unit_price_at_time' => 'decimal:4',
        'subtotal_cost' => 'decimal:2',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function purchase()
    {
        return $this->belongsTo(IngredientPurchase::class, 'ingredient_purchase_id');
    }
}