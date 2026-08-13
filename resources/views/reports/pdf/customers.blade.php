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
    <h1>Customer Purchase Report</h1>
    <p class="subtitle">{{ $rangeLabel }} · Generated {{ now()->format('M d, Y h:i A') }}</p>

    <table>
        <thead><tr><th>Customer</th><th>Orders</th><th>Total Spent</th></tr></thead>
        <tbody>
            @forelse ($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->orders_count }}</td>
                    <td>₱{{ number_format($customer->orders_sum_total_amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No customer purchases in this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>