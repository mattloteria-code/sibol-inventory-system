@extends('layouts.app')

@section('title', 'Production Batches')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Production Batches</h1>
    <a href="{{ route('production.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
        + Produce Batch
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Product</th>
                <th class="px-4 py-3">Quantity</th>
                <th class="px-4 py-3">Remaining</th>
                <th class="px-4 py-3">Total Cost</th>
                <th class="px-4 py-3">Cost/Unit</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($batches as $batch)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $batch->product->name }}</td>
                    <td class="px-4 py-3">{{ $batch->quantity_produced }}</td>
                    <td class="px-4 py-3 {{ $batch->remaining_quantity <= 0 ? 'text-gray-400' : 'text-green-600 font-medium' }}">
                        {{ $batch->remaining_quantity }}
                    </td>
                    <td class="px-4 py-3">₱{{ number_format($batch->total_cost, 2) }}</td>
                    <td class="px-4 py-3">₱{{ number_format($batch->cost_per_unit, 2) }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $batch->produced_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('production.show', $batch) }}" class="text-indigo-600 hover:underline">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No batches produced yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $batches->links() }}</div>
@endsection