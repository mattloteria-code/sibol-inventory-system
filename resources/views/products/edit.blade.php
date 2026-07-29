@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Product</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')
        @include('products._form_edit')

        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-indigo-700">
                Update Product
            </button>
            <a href="{{ route('products.index') }}" class="ml-3 text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection