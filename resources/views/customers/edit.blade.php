@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Customer</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')
        @include('customers._form')

        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-indigo-700">
                Update Customer
            </button>
            <a href="{{ route('customers.index') }}" class="ml-3 text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection