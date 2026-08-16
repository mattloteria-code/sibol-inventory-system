@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<h1 class="text-2xl font-bold mb-6">Reports</h1>

<div class="grid grid-cols-3 gap-4">
    <a href="{{ route('reports.sales') }}" class="bg-white rounded-lg shadow p-5 hover:shadow-md transition">
        <h2 class="font-semibold mb-1">Sales Report</h2>
        <p class="text-sm text-gray-500">Orders, revenue, and top-selling products.</p>
    </a>
    <a href="{{ route('reports.expenses') }}" class="bg-white rounded-lg shadow p-5 hover:shadow-md transition">
        <h2 class="font-semibold mb-1">Expense Report</h2>
        <p class="text-sm text-gray-500">Operating expenses broken down by category.</p>
    </a>
    <a href="{{ route('reports.profit-loss') }}" class="bg-white rounded-lg shadow p-5 hover:shadow-md transition">
        <h2 class="font-semibold mb-1">Profit &amp; Loss</h2>
        <p class="text-sm text-gray-500">Revenue, COGS, expenses, and net profit over time.</p>
    </a>
    <a href="{{ route('reports.inventory') }}" class="bg-white rounded-lg shadow p-5 hover:shadow-md transition">
        <h2 class="font-semibold mb-1">Inventory Report</h2>
        <p class="text-sm text-gray-500">Current stock value for products and ingredients.</p>
    </a>
    <a href="{{ route('reports.customers') }}" class="bg-white rounded-lg shadow p-5 hover:shadow-md transition">
        <h2 class="font-semibold mb-1">Customer Purchases</h2>
        <p class="text-sm text-gray-500">Top customers by orders and spend.</p>
    </a>
</div>
@endsection