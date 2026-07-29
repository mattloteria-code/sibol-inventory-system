@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Categories</h1>
    <a href="{{ route('categories.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
        + Add Category
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Slug</th>
                <th class="px-4 py-3">Products</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $category->slug }}</td>
                    <td class="px-4 py-3">{{ $category->products_count }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('categories.edit', $category) }}" class="text-indigo-600 hover:underline">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-400">No categories yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $categories->links() }}
</div>
@endsection