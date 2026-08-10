<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class ProductIngredient extends Model
{
    use Auditable;
    
    protected $fillable = [
        'product_id',
        'ingredient_id',
        'quantity_required',
    ];

    protected $casts = [
        'quantity_required' => 'decimal:0',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function getEstimatedCostAttribute()
    {
        return $this->quantity_required * $this->ingredient->current_price_per_base_unit;
    }
}
