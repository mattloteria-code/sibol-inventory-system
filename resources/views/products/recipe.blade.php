@extends('layouts.app')

@section('title', 'Recipe: ' . $product->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
        <p class="text-gray-500 text-sm mt-1">Define how much of each ingredient is needed to make 1 unit.</p>
    </div>
    <a href="{{ route('products.index') }}" class="text-gray-500 hover:underline text-sm">← Back to Products</a>
</div>

<div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 mb-6">
    <p class="text-sm text-gray-600">Estimated Cost per Unit (based on current ingredient prices)</p>
    <p class="text-2xl font-bold text-indigo-700">₱{{ number_format($estimatedCostPerUnit, 2) }}</p>
    <p class="text-xs text-gray-400 mt-1">Actual cost is locked in per batch when production happens.</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Ingredient</th>
                <th class="px-4 py-3">Quantity Required</th>
                <th class="px-4 py-3">Est. Cost</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($product->recipeItems as $item)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $item->ingredient->name }}</td>
                    <td class="px-4 py-3">
                        <form action="{{ route('products.recipe.update', [$product, $item]) }}" method="POST" class="flex items-center gap-1">
                            @csrf
                            @method('PATCH')
                            <input type="number" step="0.001" name="quantity_required" value="{{ $item->quantity_required }}"
                                   class="w-24 border border-gray-300 rounded px-2 py-1 text-sm">
                            <span class="text-gray-400 text-xs">{{ $item->ingredient->base_unit }}</span>
                            <button type="submit" class="text-indigo-600 text-xs hover:underline ml-1">Update</button>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-gray-500">₱{{ number_format($item->estimated_cost, 2) }}</td>
                    <td class="px-4 py-3 text-right">
                        <form action="{{ route('products.recipe.destroy', [$product, $item]) }}" method="POST"
                              onsubmit="return confirm('Remove this ingredient from the recipe?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm">Remove</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No ingredients added yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <h2 class="font-semibold mb-3">Add Ingredient to Recipe</h2>
    <form action="{{ route('products.recipe.store', $product) }}" method="POST" class="flex items-end gap-3">
        @csrf
        <div class="flex-1">
            <label class="block text-sm font-medium mb-1">Ingredient</label>
            <select name="ingredient_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">Select...</option>
                @foreach ($availableIngredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }} ({{ $ingredient->base_unit }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Quantity</label>
            <input type="number" step="0.001" name="quantity_required" required
                   class="w-28 border border-gray-300 rounded-lg px-3 py-2">
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
            Add
        </button>
    </form>
    @if ($availableIngredients->isEmpty())
        <p class="text-sm text-gray-400 mt-3">All active ingredients are already in this recipe.</p>
    @endif
</div>
@endsection