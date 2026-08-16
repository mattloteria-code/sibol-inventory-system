@extends('layouts.app')

@section('title', 'Expenses')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Expenses</h1>
    <a href="{{ route('expenses.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
        + Add Expense
    </a>
</div>

<div class="flex items-center gap-2 mb-4">
    @foreach (['all' => 'All Time', 'daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month', 'yearly' => 'This Year'] as $key => $label)
        <a href="{{ route('expenses.index', ['period' => $key]) }}"
           class="px-3 py-1.5 rounded-lg text-sm {{ $period == $key ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-300 text-gray-600' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="grid grid-cols-2 gap-4 mb-4">
    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4">
        <p class="text-sm text-gray-600">Total Expenses ({{ $rangeLabel }})</p>
        <p class="text-2xl font-bold text-indigo-700">₱{{ number_format($totalForPeriod, 2) }}</p>
    </div>

    <div class="bg-amber-50 border border-amber-100 rounded-lg p-4">
        <p class="text-sm text-gray-600">Ingredient Purchases ({{ $rangeLabel }})</p>
        <p class="text-2xl font-bold text-amber-700">₱{{ number_format($ingredientSpendForPeriod, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">
            Cash spent on ingredients — shown separately, not included in Net Profit
            (which uses accurate FIFO cost-of-goods-sold instead).
        </p>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Title</th>
                <th class="px-4 py-3">Category</th>
                <th class="px-4 py-3">Amount</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($expenses as $expense)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $expense->title }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $expense->expenseCategory->name ?? '—' }}</td>
                    <td class="px-4 py-3">₱{{ number_format($expense->amount, 2) }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $expense->expense_date->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('expenses.edit', $expense) }}" class="text-indigo-600 hover:underline">Edit</a>
                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete this expense?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No expenses recorded.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $expenses->links() }}</div>
@endsection