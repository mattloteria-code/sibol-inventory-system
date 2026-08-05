@extends('layouts.app')

@section('title', 'Record Purchase')

@section('content')
<h1 class="text-2xl font-bold mb-6">Record Ingredient Purchase</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-xl">
    <form action="{{ route('ingredients.purchase.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Ingredient</label>
            <select name="ingredient_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">Select an ingredient...</option>
                @foreach ($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}" {{ old('ingredient_id') == $ingredient->id ? 'selected' : '' }}>
                        {{ $ingredient->name }} ({{ ucfirst($ingredient->unit_type) }})
                    </option>
                @endforeach
            </select>
            @error('ingredient_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">Unit</label>
                <select name="unit" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Select a unit...</option>
                    @foreach (config('units.types') as $typeKey => $typeLabel)
                        <optgroup label="{{ $typeLabel }}">
                            @foreach (config('units.units') as $unitKey => $unitConfig)
                                @if ($unitConfig['type'] === $typeKey)
                                    <option value="{{ $unitKey }}" {{ old('unit') == $unitKey ? 'selected' : '' }}>
                                        {{ $unitConfig['label'] }}
                                    </option>
                                @endif
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Must match the ingredient's type.</p>
                @error('unit') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Quantity</label>
                <input type="number" step="0.001" name="quantity" value="{{ old('quantity') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @error('quantity') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Total Price Paid</label>
            <input type="number" step="0.01" name="total_price" value="{{ old('total_price') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
            <p class="text-xs text-gray-400 mt-1">The full amount paid for this purchase, not per unit.</p>
            @error('total_price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">Supplier (optional)</label>
                <input type="text" name="supplier" value="{{ old('supplier') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @error('supplier') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Purchase Date</label>
                <input type="date" name="purchase_date" value="{{ old('purchase_date', now()->format('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @error('purchase_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Notes (optional)</label>
            <textarea name="notes" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('notes') }}</textarea>
            @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-green-700">
            Record Purchase
        </button>
    </form>
</div>
@endsection