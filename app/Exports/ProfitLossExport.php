<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfitLossExport implements FromArray, WithHeadings
{
    protected $summary;
    protected $breakdown;

    public function __construct($summary, $breakdown)
    {
        $this->summary = $summary;
        $this->breakdown = $breakdown;
    }

    public function headings(): array
    {
        return ['Period', 'Revenue', 'COGS', 'Gross Profit', 'Operating Expenses', 'Net Profit'];
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = [
            'TOTAL', $this->summary['revenue'], $this->summary['cogs'],
            $this->summary['gross_profit'], $this->summary['operating_expenses'], $this->summary['net_profit'],
        ];

        $rows[] = [
            'Outstanding (Unpaid)', $this->summary['outstanding_amount'], '', '', '', $this->summary['outstanding_count'] . ' orders',
        ];

        foreach ($this->breakdown as $row) {
            $rows[] = [
                $row['label'], $row['summary']['revenue'], $row['summary']['cogs'],
                $row['summary']['gross_profit'], $row['summary']['operating_expenses'], $row['summary']['net_profit'],
            ];
        }

        return $rows;
    }
}