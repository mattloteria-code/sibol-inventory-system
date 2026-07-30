@extends('layouts.app')

@section('title', 'Stock Movement History')

@section('content')
<h1 class="text-2xl font-bold mb-6">Stock Movement History</h1>

<form action="{{ route('inventory.movements') }}" method="GET" class="mb-4 flex gap-3">
    <select name="product_id" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2">
        <option value="">All products</option>
        @foreach ($products as $product)
            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                {{ $product->name }}
            </option>
        @endforeach
    </select>

    <select name="type" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2">
        <option value="">All types</option>
        @foreach (['sale', 'restock', 'adjustment'] as $type)
            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
        @endforeach
    </select>
</form>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Product</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Change</th>
                <th class="px-4 py-3">Stock After</th>
                <th class="px-4 py-3">Reason</th>
                <th class="px-4 py-3">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($movements as $movement)
                <tr>
                    <td class="px-4 py-3">{{ $movement->product->name ?? 'Deleted product' }}</td>
                    <td class="px-4 py-3 capitalize">{{ $movement->type }}</td>
                    <td class="px-4 py-3 {{ $movement->quantity_change < 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}
                    </td>
                    <td class="px-4 py-3">{{ $movement->quantity_after }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $movement->reason ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ $movement->created_at->format('M d, Y h:i A') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No movements found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $movements->links() }}</div>
@endsection