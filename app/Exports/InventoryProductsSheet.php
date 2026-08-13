<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class InventoryProductsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products;
    }

    public function title(): string
    {
        return 'Products';
    }

    public function headings(): array
    {
        return ['Product', 'Stock', 'Selling Price', 'Stock Value'];
    }

    public function map($product): array
    {
        return [
            $product->name,
            $product->stock_quantity,
            $product->selling_price,
            $product->stock_quantity * $product->selling_price,
        ];
    }
}