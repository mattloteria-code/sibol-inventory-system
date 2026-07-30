@extends('layouts.app')

@section('title', 'Low Stock Alerts')

@section('content')
<h1 class="text-2xl font-bold mb-6">Low Stock Alerts</h1>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Product</th>
                <th class="px-4 py-3">Current Stock</th>
                <th class="px-4 py-3">Threshold</th>
                <th class="px-4 py-3 text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($products as $product)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                    <td class="px-4 py-3 text-red-600 font-semibold">{{ $product->stock_quantity }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $product->low_stock_threshold }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('inventory.restock.form') }}" class="text-indigo-600 hover:underline">Restock</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nothing low on stock 🎉</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $products->links() }}</div>
@endsection