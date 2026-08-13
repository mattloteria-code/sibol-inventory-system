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
    <h1>Profit &amp; Loss Report</h1>
    <p class="subtitle">{{ $rangeLabel }} · Generated {{ now()->format('M d, Y h:i A') }}</p>

    <table>
        <thead>
            <tr><th>Revenue</th><th>COGS</th><th>Gross Profit</th><th>Operating Expenses</th><th>Net Profit</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>₱{{ number_format($summary['revenue'], 2) }}</td>
                <td>₱{{ number_format($summary['cogs'], 2) }}</td>
                <td>₱{{ number_format($summary['gross_profit'], 2) }}</td>
                <td>₱{{ number_format($summary['operating_expenses'], 2) }}</td>
                <td>₱{{ number_format($summary['net_profit'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <h3>Breakdown</h3>
    <table>
        <thead>
            <tr><th>Period</th><th>Revenue</th><th>COGS</th><th>Gross Profit</th><th>Expenses</th><th>Net Profit</th></tr>
        </thead>
        <tbody>
            @foreach ($breakdown as $row)
                <tr>
                    <td>{{ $row['label'] }}</td>
                    <td>₱{{ number_format($row['summary']['revenue'], 2) }}</td>
                    <td>₱{{ number_format($row['summary']['cogs'], 2) }}</td>
                    <td>₱{{ number_format($row['summary']['gross_profit'], 2) }}</td>
                    <td>₱{{ number_format($row['summary']['operating_expenses'], 2) }}</td>
                    <td>₱{{ number_format($row['summary']['net_profit'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Outstanding (Unpaid):</strong> ₱{{ number_format($summary['outstanding_amount'], 2) }} ({{ $summary['outstanding_count'] }} orders)</p>
</body>
</html>