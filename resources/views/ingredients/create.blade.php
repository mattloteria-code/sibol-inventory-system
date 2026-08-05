@extends('layouts.app')

@section('title', 'Add Ingredient')

@section('content')
<h1 class="text-2xl font-bold mb-6">Add Ingredient</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <form action="{{ route('ingredients.store') }}" method="POST">
        @csrf
        @include('ingredients._form')

        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-indigo-700">
                Save Ingredient
            </button>
            <a href="{{ route('ingredients.index') }}" class="ml-3 text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
    <p class="text-xs text-gray-400 mt-4">Stock and price start at zero — record a purchase afterward to add stock.</p>
</div>
@endsection