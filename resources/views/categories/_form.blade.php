<div>
    <label class="block text-sm font-medium mb-1">Category Name</label>
    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>