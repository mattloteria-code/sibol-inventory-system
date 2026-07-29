<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium mb-1">Product Name</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Category</label>
            <select name="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">— None —</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">SKU</label>
            <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
            @error('sku') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" rows="3"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Cost Price</label>
            <input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $product->cost_price ?? '') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
            @error('cost_price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Selling Price</label>
            <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price', $product->selling_price ?? '') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
            @error('selling_price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Stock Quantity</label>
            <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
            @error('stock_quantity') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Low Stock Threshold</label>
            <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 10) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
            @error('low_stock_threshold') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>
</div>