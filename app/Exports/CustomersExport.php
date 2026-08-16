<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $customers;

    public function __construct($customers)
    {
        $this->customers = $customers;
    }

    public function collection()
    {
        return $this->customers;
    }

    public function headings(): array
    {
        return ['Customer', 'Orders', 'Total Spent'];
    }

    public function map($customer): array
    {
        return [
            $customer->name,
            $customer->orders_count,
            $customer->orders_sum_total_amount ?? 0,
        ];
    }
}