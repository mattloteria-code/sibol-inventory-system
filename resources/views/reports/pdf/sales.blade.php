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
        .summary { margin-top: 16px; }
        .summary td { border: none; padding: 4px 8px; }
    </style>
</head>
<body>
    <h1>Sales Report</h1>
    <p class="subtitle">{{ $rangeLabel }} · Generated {{ now()->format('M d, Y h:i A') }}</p>

    <table class="summary">
        <tr><td><strong>Total Revenue:</strong></td><td>₱{{ number_format($totalRevenue, 2) }}</td></tr>
        <tr><td><strong>Total Orders:</strong></td><td>{{ $totalOrders }}</td></tr>
        <tr><td><strong>Outstanding (Unpaid):</strong></td><td>₱{{ number_format($outstandingAmount, 2) }} ({{ $outstandingCount }} orders)</td></tr>
    </table>

    <h3>Top Products</h3>
    <table>
        <thead>
            <tr><th>Product</th><th>Units Sold</th><th>Revenue</th></tr>
        </thead>
        <tbody>
            @forelse ($topProducts as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->total_qty }}</td>
                    <td>₱{{ number_format($item->total_revenue, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No sales in this period.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h3>All Orders</h3>
    <table>
        <thead>
            <tr><th>Order #</th><th>Customer</th><th>Status</th><th>Total</th><th>Date</th></tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer->name }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>₱{{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No orders in this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>