@extends('layouts.app')

@section('title', 'Profit & Loss')

@section('content')
<h1 class="text-2xl font-bold mb-6">Profit &amp; Loss</h1>

@include('reports._period-tabs', ['routeName' => 'reports.profit-loss'])

<div class="grid grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Revenue</p>
        <p class="text-xl font-bold">₱{{ number_format($summary['revenue'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">COGS</p>
        <p class="text-xl font-bold">₱{{ number_format($summary['cogs'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Gross Profit</p>
        <p class="text-xl font-bold">₱{{ number_format($summary['gross_profit'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Operating Expenses</p>
        <p class="text-xl font-bold">₱{{ number_format($summary['operating_expenses'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">Net Profit</p>
        <p class="text-xl font-bold {{ $summary['net_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
            ₱{{ number_format($summary['net_profit'], 2) }}
        </p>
    </div>
</div>

<h2 class="text-lg font-semibold mb-3">Breakdown</h2>
@if ($truncated ?? false)
    <p class="text-sm text-amber-600 mb-3">Showing the most recent 24 months only.</p>
@endif
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Period</th>
                <th class="px-4 py-3">Revenue</th>
                <th class="px-4 py-3">COGS</th>
                <th class="px-4 py-3">Gross Profit</th>
                <th class="px-4 py-3">Expenses</th>
                <th class="px-4 py-3 text-right">Net Profit</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($breakdown as $row)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $row['label'] }}</td>
                    <td class="px-4 py-3">₱{{ number_format($row['summary']['revenue'], 2) }}</td>
                    <td class="px-4 py-3">₱{{ number_format($row['summary']['cogs'], 2) }}</td>
                    <td class="px-4 py-3">₱{{ number_format($row['summary']['gross_profit'], 2) }}</td>
                    <td class="px-4 py-3">₱{{ number_format($row['summary']['operating_expenses'], 2) }}</td>
                    <td class="px-4 py-3 text-right {{ $row['summary']['net_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        ₱{{ number_format($row['summary']['net_profit'], 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection