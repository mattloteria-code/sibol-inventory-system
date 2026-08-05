@extends('layouts.app')

@section('title', $ingredient->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">{{ $ingredient->name }}</h1>
        <p class="text-gray-500 text-sm mt-1">
            {{ number_format($ingredient->current_stock, 2) }} {{ $ingredient->base_unit }} in stock ·
            ₱{{ number_format($ingredient->current_stock * $ingredient->current_price_per_base_unit, 2) }} total value
        </p>
    </div>
    <a href="{{ route('ingredients.edit', $ingredient) }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
        Edit
    </a>
</div>

<h2 class="text-lg font-semibold mb-3">Purchase History (FIFO order)</h2>
<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Purchased</th>
                <th class="px-4 py-3">Total Price Paid</th>
                <th class="px-4 py-3">Remaining</th>
                <th class="px-4 py-3">Supplier</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($purchases as $purchase)
                <tr>
                    <td class="px-4 py-3">{{ $purchase->purchase_date->format('M d, Y') }}</td>
                    <td class="px-4 py-3">{{ $purchase->purchase_unit_quantity }} {{ $purchase->purchase_unit }} ({{ number_format($purchase->base_units_added) }} {{ $ingredient->base_unit }})</td>
                    <td class="px-4 py-3">₱{{ number_format($purchase->total_cost, 2) }}</td>
                    <td class="px-4 py-3 {{ $purchase->remaining_quantity <= 0 ? 'text-gray-400' : 'text-green-600 font-medium' }}">
                        {{ number_format($purchase->remaining_quantity) }} {{ $ingredient->base_unit }}
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $purchase->supplier ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No purchases yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mb-6">{{ $purchases->links() }}</div>

<h2 class="text-lg font-semibold mb-3">Price History</h2>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Old Price</th>
                <th class="px-4 py-3">New Price</th>
                <th class="px-4 py-3">Reason</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($priceHistory as $history)
                <tr>
                    <td class="px-4 py-3">{{ $history->changed_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3">₱{{ number_format($history->old_price, 2) }}</td>
                    <td class="px-4 py-3">₱{{ number_format($history->new_price, 2) }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $history->reason ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No price changes recorded.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection