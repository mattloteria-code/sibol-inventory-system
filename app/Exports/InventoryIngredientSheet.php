<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class InventoryIngredientsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $ingredients;

    public function __construct($ingredients)
    {
        $this->ingredients = $ingredients;
    }

    public function collection()
    {
        return $this->ingredients;
    }

    public function title(): string
    {
        return 'Ingredients';
    }

    public function headings(): array
    {
        return ['Ingredient', 'Stock', 'Base Unit', 'Stock Value'];
    }

    public function map($ingredient): array
    {
        return [
            $ingredient->name,
            number_format($ingredient->current_stock, 2),
            $ingredient->base_unit,
            $ingredient->total_stock_value,
        ];
    }
}