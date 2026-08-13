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
    <h1>Inventory Report</h1>
    <p class="subtitle">Generated {{ now()->format('M d, Y h:i A') }}</p>
    <p><strong>Finished Goods Value:</strong> ₱{{ number_format($totalProductValue, 2) }}</p>
    <p><strong>Ingredient Stock Value:</strong> ₱{{ number_format($totalIngredientValue, 2) }}</p>

    <h3>Products</h3>
    <table>
        <thead><tr><th>Product</th><th>Stock</th><th>Selling Price</th><th>Stock Value</th></tr></thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>₱{{ number_format($product->selling_price, 2) }}</td>
                    <td>₱{{ number_format($product->stock_quantity * $product->selling_price, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No products.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h3>Ingredients</h3>
    <table>
        <thead><tr><th>Ingredient</th><th>Stock</th><th>Stock Value</th></tr></thead>
        <tbody>
            @forelse ($ingredients as $ingredient)
                <tr>
                    <td>{{ $ingredient->name }}</td>
                    <td>{{ number_format($ingredient->current_stock, 2) }} {{ $ingredient->base_unit }}</td>
                    <td>₱{{ number_format($ingredient->total_stock_value, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No ingredients.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>