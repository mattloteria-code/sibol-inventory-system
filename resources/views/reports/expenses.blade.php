@extends('layouts.app')

@section('title', 'Expense Report')

@section('content')
<h1 class="text-2xl font-bold mb-6">Expense Report</h1>

@include('reports._period-tabs', ['routeName' => 'reports.expenses'])

<div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 mb-6">
    <p class="text-sm text-gray-600">Total Expenses ({{ $rangeLabel }})</p>
    <p class="text-2xl font-bold text-indigo-700">₱{{ number_format($totalExpenses, 2) }}</p>
</div>

<h2 class="text-lg font-semibold mb-3">By Category</h2>
<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Category</th>
                <th class="px-4 py-3 text-right">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($byCategory as $row)
                <tr>
                    <td class="px-4 py-3">{{ $row->expenseCategory->name ?? 'Uncategorized' }}</td>
                    <td class="px-4 py-3 text-right">₱{{ number_format($row->total, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="2" class="px-4 py-8 text-center text-gray-400">No expenses in this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<h2 class="text-lg font-semibold mb-3">All Expenses</h2>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Title</th>
                <th class="px-4 py-3">Category</th>
                <th class="px-4 py-3">Amount</th>
                <th class="px-4 py-3">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($expenses as $expense)
                <tr>
                    <td class="px-4 py-3">{{ $expense->title }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $expense->expenseCategory->name ?? '—' }}</td>
                    <td class="px-4 py-3">₱{{ number_format($expense->amount, 2) }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $expense->expense_date->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No expenses in this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $expenses->links() }}</div>
@endsection