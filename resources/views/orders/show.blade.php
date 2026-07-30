@extends('layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Order {{ $order->order_number }}</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $order->created_at->format('M d, Y - h:i A') }}</p>
    </div>

    @if ($order->status !== 'cancelled' && $order->status !== 'completed')
        <form action="{{ route('orders.update-status', $order) }}" method="POST" class="flex items-center gap-2">
            @csrf
            @method('PATCH')
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                @foreach (['pending', 'processing', 'completed', 'cancelled'] as $status)
                    <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                    onclick="return event.target.form.status.value === 'cancelled' ? confirm('Cancel this order and restore stock?') : true"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                Update Status
            </button>
        </form>
    @else
        <span class="text-xs px-3 py-1 rounded-full {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} capitalize">
            {{ $order->status }}
        </span>
    @endif
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="font-semibold mb-2">Customer</h2>
    <p>{{ $order->customer->name }}</p>
    <p class="text-sm text-gray-500">{{ $order->customer->phone ?? $order->customer->email ?? '' }}</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Product</th>
                <th class="px-4 py-3">Qty</th>
                <th class="px-4 py-3">Unit Price</th>
                <th class="px-4 py-3 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($order->items as $item)
                <tr>
                    <td class="px-4 py-3">{{ $item->product_name }}</td>
                    <td class="px-4 py-3">{{ $item->quantity }}</td>
                    <td class="px-4 py-3">₱{{ number_format($item->unit_price, 2) }}</td>
                    <td class="px-4 py-3 text-right">₱{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="border-t border-gray-200 font-semibold">
                <td colspan="3" class="px-4 py-3 text-right">Total</td>
                <td class="px-4 py-3 text-right">₱{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<a href="{{ route('orders.cart.index') }}" class="text-indigo-600 hover:underline text-sm">← Create another order</a>
@endsection