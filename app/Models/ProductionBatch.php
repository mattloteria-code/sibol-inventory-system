<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class ProductionBatch extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'product_id',
        'quantity_produced',
        'remaining_quantity',
        'total_cost',
        'cost_per_unit',
        'produced_at',
        'notes',
    ];

    protected $casts = [
        'total_cost' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
        'produced_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batchIngredients()
    {
        return $this->hasMany(ProductionBatchIngredient::class);
    }

    public function saleConsumptions()
    {
        return $this->hasMany(OrderItemBatch::class);
    }
}