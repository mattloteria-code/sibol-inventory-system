<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium mb-1">Title</label>
        <input type="text" name="title" value="{{ old('title', $expense->title ?? '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Category</label>
            <select name="expense_category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">— None —</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('expense_category_id', $expense->expense_category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('expense_category_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Amount</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $expense->amount ?? '') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
            @error('amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Expense Date</label>
        <input type="date" name="expense_date"
               value="{{ old('expense_date', isset($expense) ? $expense->expense_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2">
        @error('expense_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Notes (optional)</label>
        <textarea name="notes" rows="2"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('notes', $expense->notes ?? '') }}</textarea>
        @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
</div>