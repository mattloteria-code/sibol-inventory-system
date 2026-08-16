<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $expenses;

    public function __construct($expenses)
    {
        $this->expenses = $expenses;
    }

    public function collection()
    {
        return $this->expenses;
    }

    public function headings(): array
    {
        return ['Title', 'Category', 'Amount', 'Date', 'Notes'];
    }

    public function map($expense): array
    {
        return [
            $expense->title,
            $expense->category->name ?? '—',
            $expense->amount,
            $expense->expense_date->format('Y-m-d'),
            $expense->notes ?? '',
        ];
    }
}