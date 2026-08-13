<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InventoryExport implements WithMultipleSheets
{
    protected $products;
    protected $ingredients;

    public function __construct($products, $ingredients)
    {
        $this->products = $products;
        $this->ingredients = $ingredients;
    }

    public function sheets(): array
    {
        return [
            'Products' => new InventoryProductsSheet($this->products),
            'Ingredients' => new InventoryIngredientsSheet($this->ingredients),
        ];
    }
}