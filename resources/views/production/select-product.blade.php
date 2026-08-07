@extends('layouts.app')

@section('title', 'Produce Batch — Select Product')

@section('content')
<h1 class="text-2xl font-bold mb-6">Produce a Batch</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    @if ($products->isEmpty())
        <p class="text-gray-500">No products have a recipe defined yet. <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline">Set up a recipe first</a>.</p>
    @else
        <form action="{{ route('production.create') }}" method="GET" class="flex items-end gap-3">
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Product</label>
                <select name="product" required class="w-full border border-gray-300 rounded-lg px-3 py-2"
                        onchange="window.location.href = '{{ url('production/create') }}/' + this.value">
                    <option value="">Select a product...</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    @endif
</div>
@endsection