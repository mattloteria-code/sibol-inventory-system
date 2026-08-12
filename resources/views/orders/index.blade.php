@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Orders</h1>
    <a href="{{ route('orders.cart.index') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
        + New Order
    </a>
</div>

<form action="{{ route('orders.index') }}" method="GET" class="mb-4 flex gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order # or customer..."
           class="flex-1 border border-gray-300 rounded-lg px-3 py-2">

    <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2">
        <option value="">All statuses</option>
        @foreach (['pending', 'processing', 'shipped', 'delivered', 'completed', 'cancelled'] as $status)
            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                {{ ucfirst($status) }}
            </option>
        @endforeach
    </select>

    <button type="submit" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">Search</button>
</form>

<form method="GET" action="{{ route('orders.index') }}" class="mb-4 flex items-center gap-3">
    <label for="payment_status" class="text-sm font-medium text-gray-700">
        Payment:
    </label>

    <select
        name="payment_status"
        id="payment_status"
        onchange="this.form.submit()"
        class="border border-gray-300 rounded-lg px-3 py-2"
    >
        <option value="">All</option>
        <option value="paid" @selected(request('payment_status') === 'paid')>
            Paid
        </option>
        <option value="unpaid" @selected(request('payment_status') === 'unpaid')>
            Unpaid
        </option>
    </select>
</form>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Order #</th>
                <th class="px-4 py-3">Customer</th>
                <th class="px-4 py-3">Total</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Payment</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:underline">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td class="px-4 py-3">{{ $order->customer->name }}</td>
                    <td class="px-4 py-3">₱{{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-4 py-3">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'processing' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full {{ $statusColors[$order->status] }} capitalize">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $paymentColors = [
                                'paid' => 'bg-green-100 text-green-800',
                                'unpaid' => 'bg-red-100 text-red-800',
                            ];
                        @endphp

                        <span class="text-xs px-2 py-1 rounded-full {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }} capitalize">
                            {{ $order->payment_status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:underline text-sm">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $orders->links() }}
</div>
@endsection