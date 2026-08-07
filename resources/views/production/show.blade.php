@extends('layouts.app')

@section('title', 'Production Batch #' . $batch->id)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Batch #{{ $batch->id }} — {{ $batch->product->name }}</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $batch->produced_at->format('M d, Y - h:i A') }}</p>
    </div>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Quantity Produced</p>
        <p class="text-2xl font-bold">{{ $batch->quantity_produced }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Total Cost</p>
        <p class="text-2xl font-bold">₱{{ number_format($batch->total_cost, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Cost per Unit</p>
        <p class="text-2xl font-bold">₱{{ number_format($batch->cost_per_unit, 2) }}</p>
    </div>
</div>

<h2 class="text-lg font-semibold mb-3">Ingredients Consumed (FIFO)</h2>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Ingredient</th>
                <th class="px-4 py-3">Quantity Used</th>
                <th class="px-4 py-3">Price/Unit</th>
                <th class="px-4 py-3">From Purchase</th>
                <th class="px-4 py-3 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($batch->batchIngredients as $bi)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $bi->ingredient->name }}</td>
                    <td class="px-4 py-3">{{ number_format($bi->quantity_used) }} {{ $bi->ingredient->base_unit }}</td>
                    <td class="px-4 py-3">₱{{ number_format($bi->unit_price_at_time, 2) }}</td>
                    <td class="px-4 py-3 text-gray-500">
                        {{ $bi->purchase ? $bi->purchase->purchase_date->format('M d, Y') : '—' }}
                    </td>
                    <td class="px-4 py-3 text-right">₱{{ number_format($bi->subtotal_cost, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if ($batch->notes)
    <div class="mt-4 bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
        <strong>Notes:</strong> {{ $batch->notes }}
    </div>
@endif

<a href="{{ route('production.index') }}" class="inline-block mt-6 text-indigo-600 hover:underline text-sm">← All Production Batches</a>
@endsection