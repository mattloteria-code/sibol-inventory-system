<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium mb-1">Full Name</label>
        <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? '') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
            @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
            @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Address</label>
        <textarea name="address" rows="3"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('address', $customer->address ?? '') }}</textarea>
        @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
</div>