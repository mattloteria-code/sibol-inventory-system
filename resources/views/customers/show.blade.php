@extends('layouts.app')

@section('title', $customer->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">{{ $customer->name }}</h1>
        <p class="text-gray-500 text-sm mt-1">
            {{ $customer->phone ?? '—' }} · {{ $customer->email ?? '—' }}
        </p>
    </div>
    <a href="{{ route('customers.edit', $customer) }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
        Edit Customer
    </a>
</div>

<h2 class="text-lg font-semibold mb-3">Order History</h2>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Order #</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Total</th>
                <th class="px-4 py-3">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $order->order_number }}</td>
                    <td class="px-4 py-3 capitalize">{{ $order->status }}</td>
                    <td class="px-4 py-3">₱{{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-400">No orders yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $orders->links() }}
</div>
@endsection