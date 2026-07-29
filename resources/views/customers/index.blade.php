@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Customers</h1>
    <a href="{{ route('customers.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
        + Add Customer
    </a>
</div>

<form action="{{ route('customers.index') }}" method="GET" class="mb-4">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone, or email..."
           class="w-full max-w-md border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
</form>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Phone</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Orders</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('customers.show', $customer) }}" class="text-indigo-600 hover:underline">
                            {{ $customer->name }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $customer->phone ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $customer->email ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $customer->orders_count }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('customers.edit', $customer) }}" class="text-indigo-600 hover:underline">Edit</a>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete this customer?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">No customers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $customers->links() }}
</div>
@endsection