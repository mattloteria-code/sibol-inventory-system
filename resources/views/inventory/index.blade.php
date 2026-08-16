@extends('layouts.app')

@section('title', 'Inventory')

@section('content')
<h1 class="text-2xl font-bold mb-6">Inventory</h1>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Active Products</p>
        <p class="text-2xl font-bold">{{ $totalProducts }}</p>
    </div>
    <a href="{{ route('inventory.low-stock') }}" class="bg-white rounded-lg shadow p-4 hover:bg-red-50 transition">
        <p class="text-sm text-gray-500">Low Stock Items</p>
        <p class="text-2xl font-bold {{ $lowStockCount > 0 ? 'text-red-600' : '' }}">{{ $lowStockCount }}</p>
    </a>
</div>

<div class="bg-white rounded-lg shadow p-4">
    <div class="flex items-center justify-between mb-3">
        <h2 class="font-semibold">Recent Stock Movements</h2>
        <a href="{{ route('inventory.movements') }}" class="text-sm text-indigo-600 hover:underline">View all →</a>
    </div>

    <table class="w-full text-sm text-left">
        <thead class="text-gray-500 text-xs uppercase">
            <tr>
                <th class="py-2">Product</th>
                <th class="py-2">Type</th>
                <th class="py-2">Change</th>
                <th class="py-2">Stock After</th>
                <th class="py-2">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($recentMovements as $movement)
                <tr>
                    <td class="py-2">{{ $movement->product->name ?? 'Deleted product' }}</td>
                    <td class="py-2 capitalize">{{ $movement->type }}</td>
                    <td class="py-2 {{ $movement->quantity_change < 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}
                    </td>
                    <td class="py-2">{{ $movement->quantity_after }}</td>
                    <td class="py-2 text-gray-400">{{ $movement->created_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-6 text-center text-gray-400">No movements yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection