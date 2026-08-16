<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use Auditable;
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
