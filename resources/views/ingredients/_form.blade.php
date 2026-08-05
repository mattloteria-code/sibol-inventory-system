<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium mb-1">Ingredient Name</label>
        <input type="text" name="name" value="{{ old('name', $ingredient->name ?? '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2">
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Unit</label>
        <select name="unit" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            <option value="">Select a unit...</option>
            @foreach (config('units.types') as $typeKey => $typeLabel)
                <optgroup label="{{ $typeLabel }}">
                    @foreach (config('units.units') as $unitKey => $unitConfig)
                        @if ($unitConfig['type'] === $typeKey)
                            <option value="{{ $unitKey }}" {{ old('unit', $ingredient->preferred_unit ?? '') == $unitKey ? 'selected' : '' }}>
                                {{ $unitConfig['label'] }}
                            </option>
                        @endif
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        <p class="text-xs text-gray-400 mt-1">Stock will always be tracked internally in {{ $ingredient->base_unit ?? 'the matching base unit' }}.</p>
        @error('unit') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Low Stock Threshold</label>
        <input type="number" step="0.001" name="low_stock_threshold" value="{{ old('low_stock_threshold', $ingredient->low_stock_threshold ?? 0) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2">
        @error('low_stock_threshold') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
</div>