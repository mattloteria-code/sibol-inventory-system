<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class IngredientPurchase extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'ingredient_id',
        'purchase_unit',
        'purchase_unit_quantity',
        'conversion_to_base',
        'unit_price',
        'total_cost',
        'base_units_added',
        'price_per_base_unit',
        'remaining_quantity',
        'supplier',
        'purchase_date',
        'notes',
    ];

    protected $casts = [
        'purchase_unit_quantity' => 'decimal:0',
        'conversion_to_base' => 'decimal:4',
        'unit_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'base_units_added' => 'decimal:3',
        'price_per_base_unit' => 'decimal:4',
        'remaining_quantity' => 'decimal:3',
        'purchase_date' => 'date',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}