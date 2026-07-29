@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<h1 class="text-2xl font-bold mb-6">Add Product</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        @include('products._form_create')

        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-indigo-700">
                Save Product
            </button>
            <a href="{{ route('products.index') }}" class="ml-3 text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection