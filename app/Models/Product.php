<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Auditable;
    
    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'description',
        'selling_price',
        'stock_quantity',
        'low_stock_threshold',
        'is_active',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function recipeItems()
    {
        return $this->hasMany(ProductIngredient::class);
    }

    public function productionBatches()
    {
        return $this->hasMany(ProductionBatch::class);
    }
}
