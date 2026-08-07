@extends('layouts.app')

@section('title', 'Produce: ' . $product->name)

@section('content')
<h1 class="text-2xl font-bold mb-2">Produce: {{ $product->name }}</h1>
<p class="text-gray-500 text-sm mb-6">Adjust ingredient quantities if this batch differs from the standard recipe.</p>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form action="{{ route('production.store') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <h2 class="font-semibold mb-3">Ingredients Used</h2>
        <div class="space-y-3 mb-6">
            @foreach ($product->recipeItems as $item)
                <div class="flex items-center gap-3">
                    <label class="flex-1 text-sm">{{ $item->ingredient->name }}</label>
                    <input type="number" step="0.001"
                           name="ingredients[{{ $item->ingredient_id }}]"
                           value="{{ old('ingredients.' . $item->ingredient_id, $item->quantity_required) }}"
                           class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <span class="text-gray-400 text-xs w-10">{{ $item->ingredient->base_unit }}</span>
                    <span class="text-gray-400 text-xs w-32">available: {{ number_format($item->ingredient->current_stock, 2) }}</span>
                </div>
            @endforeach
        </div>
        @error('ingredients') <p class="text-red-600 text-sm mb-4">{{ $message }}</p> @enderror

        <div class="mb-4 pt-4 border-t border-gray-200">
            <label class="block text-sm font-medium mb-1">Actual Quantity Produced</label>
            <input type="number" name="quantity_produced" min="1" value="{{ old('quantity_produced') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
            <p class="text-xs text-gray-400 mt-1">How many usable {{ $product->name }} did this batch actually yield?</p>
            @error('quantity_produced') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Notes (optional)</label>
            <textarea name="notes" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-indigo-700">
            Confirm Production
        </button>
        <a href="{{ route('production.create') }}" class="ml-3 text-gray-500 hover:underline text-sm">Choose different product</a>
    </form>
</div>
@endsection