<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 0; }
        p.subtitle { color: #777; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Expense Report</h1>
    <p class="subtitle">{{ $rangeLabel }} · Generated {{ now()->format('M d, Y h:i A') }}</p>
    <p><strong>Total Expenses:</strong> ₱{{ number_format($totalExpenses, 2) }}</p>

    <h3>By Category</h3>
    <table>
        <thead><tr><th>Category</th><th>Total</th></tr></thead>
        <tbody>
            @forelse ($byCategory as $row)
                <tr><td>{{ $row->category->name ?? 'Uncategorized' }}</td><td>₱{{ number_format($row->total, 2) }}</td></tr>
            @empty
                <tr><td colspan="2">No expenses in this period.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h3>All Expenses</h3>
    <table>
        <thead><tr><th>Title</th><th>Category</th><th>Amount</th><th>Date</th></tr></thead>
        <tbody>
            @forelse ($expenses as $expense)
                <tr>
                    <td>{{ $expense->title }}</td>
                    <td>{{ $expense->category->name ?? '—' }}</td>
                    <td>₱{{ number_format($expense->amount, 2) }}</td>
                    <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No expenses in this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>