@extends('layouts.app')

@section('title', 'Add Expense Category')

@section('content')
<h1 class="text-2xl font-bold mb-6">Add Expense Category</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <form action="{{ route('expense-categories.store') }}" method="POST">
        @csrf
        @include('expense-categories._form')

        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-indigo-700">
                Save Category
            </button>
            <a href="{{ route('expense-categories.index') }}" class="ml-3 text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection