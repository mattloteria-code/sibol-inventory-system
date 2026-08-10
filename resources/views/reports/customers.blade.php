@extends('layouts.app')

@section('title', 'Customer Purchase Report')

@section('content')
<h1 class="text-2xl font-bold mb-6">Customer Purchase Report</h1>

@include('reports._period-tabs', ['routeName' => 'reports.customers'])

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Customer</th>
                <th class="px-4 py-3">Orders ({{ $rangeLabel }})</th>
                <th class="px-4 py-3 text-right">Total Spent</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($customers as $customer)
                <tr>
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('customers.show', $customer) }}" class="text-indigo-600 hover:underline">{{ $customer->name }}</a>
                    </td>
                    <td class="px-4 py-3">{{ $customer->orders_count }}</td>
                    <td class="px-4 py-3 text-right">₱{{ number_format($customer->orders_sum_total_amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">No customer purchases in this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $customers->links() }}</div>
@endsection