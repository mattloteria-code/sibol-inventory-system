<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientPriceHistory extends Model
{
    protected $fillable = [
        'ingredient_id',
        'old_price',
        'new_price',
        'reason',
        'changed_at',
    ];

    protected $casts = [
        'old_price' => 'decimal:4',
        'new_price' => 'decimal:4',
        'changed_at' => 'datetime',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
