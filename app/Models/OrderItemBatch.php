<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemBatch extends Model
{
    protected $fillable = [
        'order_item_id',
        'production_batch_id',
        'quantity',
        'unit_cost_at_time',
    ];

    protected $casts = [
        'unit_cost_at_time' => 'decimal:2',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function batch()
    {
        return $this->belongsTo(ProductionBatch::class, 'production_batch_id');
    }
}
