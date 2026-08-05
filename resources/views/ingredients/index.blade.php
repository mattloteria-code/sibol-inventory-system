@extends('layouts.app')

@section('title', 'Ingredients')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Ingredients</h1>
    <div class="space-x-2">
        <a href="{{ route('ingredients.purchase.form') }}"
           class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">
            + Record Purchase
        </a>
        <a href="{{ route('ingredients.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
            + Add Ingredient
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Unit Type</th>
                <th class="px-4 py-3">Current Stock</th>
                <th class="px-4 py-3">Total Cost of Stock</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($ingredients as $ingredient)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('ingredients.show', $ingredient) }}" class="text-indigo-600 hover:underline">
                            {{ $ingredient->name }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $ingredient->preferred_unit }}</td>
                    <td class="px-4 py-3">
                        {{ number_format($ingredient->current_stock) }} {{ $ingredient->base_unit }}
                        @if ($ingredient->isLowStock())
                            <span class="ml-1 text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Low</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">₱{{ number_format($ingredient->current_stock * $ingredient->current_price_per_base_unit, 2) }}</td>
                    <td class="px-4 py-3">
                        @if ($ingredient->is_active)
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Active</span>
                        @else
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('ingredients.edit', $ingredient) }}" class="text-indigo-600 hover:underline">Edit</a>
                        <form action="{{ route('ingredients.destroy', $ingredient) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete this ingredient?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No ingredients yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $ingredients->links() }}</div>
@endsection