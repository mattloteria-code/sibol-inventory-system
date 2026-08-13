@extends('layouts.app')

@section('title', 'Inventory Report')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Inventory Report</h1>
    @include('reports._export-buttons', ['routeName' => 'reports.inventory'])
</div>

<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Finished Goods Value (at selling price)</p>
        <p class="text-2xl font-bold">₱{{ number_format($totalProductValue, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Ingredient Stock Value</p>
        <p class="text-2xl font-bold">₱{{ number_format($totalIngredientValue, 2) }}</p>
    </div>
</div>

<h2 class="text-lg font-semibold mb-3">Products</h2>
<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Product</th>
                <th class="px-4 py-3">Stock</th>
                <th class="px-4 py-3">Selling Price</th>
                <th class="px-4 py-3 text-right">Stock Value</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($products as $product)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                    <td class="px-4 py-3 {{ $product->isLowStock() ? 'text-red-600 font-medium' : '' }}">{{ $product->stock_quantity }}</td>
                    <td class="px-4 py-3">₱{{ number_format($product->selling_price, 2) }}</td>
                    <td class="px-4 py-3 text-right">₱{{ number_format($product->stock_quantity * $product->selling_price, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No products.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<h2 class="text-lg font-semibold mb-3">Ingredients</h2>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Ingredient</th>
                <th class="px-4 py-3">Stock</th>
                <th class="px-4 py-3 text-right">Stock Value</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($ingredients as $ingredient)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $ingredient->name }}</td>
                    <td class="px-4 py-3 {{ $ingredient->isLowStock() ? 'text-red-600 font-medium' : '' }}">
                        {{ number_format($ingredient->current_stock) }} {{ $ingredient->base_unit }}
                    </td>
                    <td class="px-4 py-3 text-right">₱{{ number_format($ingredient->total_stock_value, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">No ingredients.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection