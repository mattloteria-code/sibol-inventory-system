<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Auditable;
    
    protected $fillable = [
        'order_number',
        'customer_id',
        'status',
        'payment_method',
        'payment_status',
        'paid_at',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }
}
