<div>
    <label class="block text-sm font-medium mb-1">Category Name</label>
    <input type="text" name="name" value="{{ old('name', $expenseCategory->name ?? '') }}"
           class="w-full border border-gray-300 rounded-lg px-3 py-2">
    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>