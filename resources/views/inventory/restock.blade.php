@extends('layouts.app')

@section('title', 'Restock Product')

@section('content')
<h1 class="text-2xl font-bold mb-6">Restock Product</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <form action="{{ route('inventory.restock') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Product</label>
            <select name="product_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">Select a product...</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} (current: {{ $product->stock_quantity }})
                    </option>
                @endforeach
            </select>
            @error('product_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Quantity to Add</label>
            <input type="number" name="quantity" min="1" value="{{ old('quantity') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
            @error('quantity') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Reason (optional)</label>
            <input type="text" name="reason" placeholder="e.g. Supplier delivery, correction..." value="{{ old('reason') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
            @error('reason') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-indigo-700">
            Add Stock
        </button>
    </form>
</div>
@endsection