@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Products</h1>
    <a href="{{ route('products.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
        + Add Product
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Category</th>
                <th class="px-4 py-3">SKU</th>
                <th class="px-4 py-3">Price</th>
                <th class="px-4 py-3">Stock</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $product->category->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $product->sku ?? '—' }}</td>
                    <td class="px-4 py-3">₱{{ number_format($product->selling_price, 2) }}</td>
                    <td class="px-4 py-3">
                        {{ $product->stock_quantity }}
                        @if ($product->isLowStock())
                            <span class="ml-1 text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Low</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if ($product->is_active)
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Active</span>
                        @else
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('products.edit', $product) }}" class="text-indigo-600 hover:underline">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">No products yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection