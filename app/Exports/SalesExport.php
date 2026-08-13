<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return ['Order #', 'Customer', 'Status', 'Payment Status', 'Total Amount', 'Date'];
    }

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->customer->name,
            ucfirst($order->status),
            ucfirst($order->payment_status),
            $order->total_amount,
            $order->created_at->format('Y-m-d'),
        ];
    }
}