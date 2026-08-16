@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Sales Report</h1>
    @include('reports._export-buttons', ['routeName' => 'reports.sales'])
</div>

@include('reports._date-filter', ['routeName' => 'reports.sales'])

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Total Revenue ({{ $rangeLabel }})</p>
        <p class="text-2xl font-bold">₱{{ number_format($totalRevenue, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Total Orders ({{ $rangeLabel }})</p>
        <p class="text-2xl font-bold">{{ $totalOrders }}</p>
    </div>
    <div class="bg-amber-50 border border-amber-100 rounded-lg p-4">
        <p class="text-sm text-gray-500">Outstanding (Unpaid)</p>
        <p class="text-2xl font-bold text-amber-700">₱{{ number_format($outstandingAmount, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $outstandingCount }} order(s)</p>
    </div>
</div>

<h2 class="text-lg font-semibold mb-3">Top Products</h2>
<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Product</th>
                <th class="px-4 py-3">Units Sold</th>
                <th class="px-4 py-3 text-right">Revenue</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($topProducts as $item)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $item->product_name }}</td>
                    <td class="px-4 py-3">{{ $item->total_qty }}</td>
                    <td class="px-4 py-3 text-right">₱{{ number_format($item->total_revenue, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">No sales in this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<h2 class="text-lg font-semibold mb-3">All Orders</h2>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Order #</th>
                <th class="px-4 py-3">Customer</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Total</th>
                <th class="px-4 py-3">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($orders as $order)
                <tr>
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:underline">{{ $order->order_number }}</a>
                    </td>
                    <td class="px-4 py-3">{{ $order->customer->name }}</td>
                    <td class="px-4 py-3 capitalize">{{ $order->status }}</td>
                    <td class="px-4 py-3">₱{{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No orders in this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection