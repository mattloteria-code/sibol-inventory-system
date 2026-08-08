@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-6">Dashboard</h1>

{{-- Top metric cards --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Revenue (This Month)</p>
        <p class="text-2xl font-bold">₱{{ number_format($monthlySummary['revenue'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Expenses (This Month)</p>
        <p class="text-2xl font-bold">₱{{ number_format($monthlySummary['operating_expenses'], 2) }}</p>
        <p class="text-sm text-gray-500">Ingredient Purchases</p>
        <p class="text-2xl font-bold text-amber-700">₱{{ number_format($ingredientSpendThisMonth, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Net Profit (This Month)</p>
        <p class="text-2xl font-bold {{ $monthlySummary['net_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
            ₱{{ number_format($monthlySummary['net_profit'], 2) }}
        </p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Orders (This Month)</p>
        <p class="text-2xl font-bold">{{ $monthlySummary['order_count'] }}</p>
    </div>
</div>

{{-- Secondary status cards --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="bg-white rounded-lg shadow p-4 hover:bg-yellow-50">
        <p class="text-sm text-gray-500">Pending / Processing</p>
        <p class="text-xl font-bold text-yellow-600">{{ $pendingOrders }}</p>
    </a>
    <a href="{{ route('orders.index', ['status' => 'completed']) }}" class="bg-white rounded-lg shadow p-4 hover:bg-green-50">
        <p class="text-sm text-gray-500">Completed Orders</p>
        <p class="text-xl font-bold text-green-600">{{ $completedOrders }}</p>
    </a>
    <a href="{{ route('products.index') }}" class="bg-white rounded-lg shadow p-4 hover:bg-red-50">
        <p class="text-sm text-gray-500">Low Stock Products</p>
        <p class="text-xl font-bold {{ $lowStockProducts > 0 ? 'text-red-600' : '' }}">{{ $lowStockProducts }}</p>
    </a>
    <a href="{{ route('ingredients.index') }}" class="bg-white rounded-lg shadow p-4 hover:bg-red-50">
        <p class="text-sm text-gray-500">Low Stock Ingredients</p>
        <p class="text-xl font-bold {{ $lowStockIngredients > 0 ? 'text-red-600' : '' }}">{{ $lowStockIngredients }}</p>
    </a>
</div>

{{-- Chart + recent orders --}}
<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Sales — Last 7 Days</h2>
        <canvas id="salesChart" height="100"></canvas>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Recent Orders</h2>
        <div class="space-y-3">
            @forelse ($recentOrders as $order)
                <a href="{{ route('orders.show', $order) }}" class="block hover:bg-gray-50 p-2 -mx-2 rounded">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium">{{ $order->order_number }}</span>
                        <span class="text-sm text-gray-500">₱{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <p class="text-xs text-gray-400">{{ $order->customer->name }} · {{ $order->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <p class="text-gray-400 text-sm">No orders yet.</p>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('salesChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($salesTrend)->pluck('label')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode(collect($salesTrend)->pluck('value')) !!},
                backgroundColor: '#4f46e5',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
@endsection