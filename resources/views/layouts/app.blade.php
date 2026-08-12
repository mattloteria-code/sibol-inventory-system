<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Order Tracking System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <nav class="fixed left-0 top-0 h-screen w-64 bg-sky-950 text-white shadow-lg">
        <div class="px-6 py-5 bg-sky-950 border-b border-indigo-500">
            <a href="{{ route('dashboard') }}" class="font-bold text-xl flex items-center gap-3">
                <img src="{{ asset('images/sibollogo.jpg') }}"
                    alt="Order Tracker Logo"
                    class="h-12 w-12 object-contain rounded">
                    <h1> Sibol </h1>
            </a>
        </div>

        <div class="p-4 space-y-2 overflow-y-auto h-[calc(100vh-80px)]">

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
            class="block px-4 py-2 rounded-lg
            {{ request()->routeIs('dashboard*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                Dashboard
            </a>

            <details class="group" {{ request()->routeIs('expense-categories.*', 'categories.*') ? 'open' : '' }}>
                <summary class="flex items-center justify-between px-4 py-2 rounded-lg cursor-pointer hover:bg-indigo-600">
                    <span>Categories</span>

                    <span class="transition-transform group-open:rotate-180">
                        ▼
                    </span>
                </summary>

                <div class="ml-4 mt-1 space-y-1">

                    {{-- Expense Category --}}
                    <a href="{{ route('expense-categories.index') }}"
                    class="block px-4 py-2 text-sm rounded-lg
                    {{ request()->routeIs('expense-categories.*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                        Expense Category
                    </a>

                    {{-- Product Category --}}
                    <a href="{{ route('categories.index') }}"
                    class="block px-4 py-2 text-sm rounded-lg
                    {{ request()->routeIs('categories.*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                        Product Category
                    </a>

                </div>
            </details>

            {{-- Inventory Dropdown --}}
            <details class="group" {{ request()->routeIs('ingredients.*', 'production.*', 'products.*', 'inventory.*') ? 'open' : '' }}>
                <summary class="flex items-center justify-between px-4 py-2 rounded-lg cursor-pointer hover:bg-indigo-600">
                    <span>Inventory</span>

                    <span class="transition-transform group-open:rotate-180">
                        ▼
                    </span>
                </summary>

                <div class="ml-4 mt-1 space-y-1">

                    {{-- Ingredients --}}
                    <a href="{{ route('ingredients.index') }}"
                    class="block px-4 py-2 text-sm rounded-lg
                    {{ request()->routeIs('ingredients.*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                        Ingredients
                    </a>

                    {{-- Production --}}
                    <a href="{{ route('production.index') }}"
                    class="block px-4 py-2 text-sm rounded-lg
                    {{ request()->routeIs('production.*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                        Production
                    </a>

                    {{-- Products --}}
                    <a href="{{ route('products.index') }}"
                    class="block px-4 py-2 text-sm rounded-lg
                    {{ request()->routeIs('products.*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                        Products
                    </a>

                    {{-- Inventory --}}
                    <a href="{{ route('inventory.index') }}"
                    class="block px-4 py-2 text-sm rounded-lg
                    {{ request()->routeIs('inventory.*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                        Product History
                    </a>

                </div>
            </details>

            {{-- Orders --}}
            <a href="{{ route('orders.index') }}"
            class="block px-4 py-2 rounded-lg
            {{ request()->routeIs('orders.*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                Orders
            </a>

            {{-- Customers --}}
            <a href="{{ route('customers.index') }}"
            class="block px-4 py-2 rounded-lg
            {{ request()->routeIs('customers.*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                Customers
            </a>            

            {{-- Expenses --}}
            <a href="{{ route('expenses.index') }}"
            class="block px-4 py-2 rounded-lg
            {{ request()->routeIs('expenses.*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                Expenses
            </a>

            {{-- Reports --}}
            <details class="group" {{ request()->routeIs('reports.*') ? 'open' : '' }}>
                <summary class="flex items-center justify-between px-4 py-2 rounded-lg cursor-pointer hover:bg-indigo-600">
                    <span>Reports</span>

                    <span class="transition-transform group-open:rotate-180">
                        ▼
                    </span>
                </summary>
                <div class="ml-4 mt-1 space-y-1">

                    <a href="{{ route('reports.customers') }}"
                    class="block px-4 py-2 rounded-lg
                    {{ request()->routeIs('reports.customer*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                        Customers
                    </a>
                    <a href="{{ route('reports.expenses') }}"
                    class="block px-4 py-2 rounded-lg
                    {{ request()->routeIs('reports.expenses*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                        Expenses
                    </a>
                    <a href="{{ route('reports.inventory') }}"
                    class="block px-4 py-2 rounded-lg
                    {{ request()->routeIs('reports.inventory*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                        Inventory
                    </a>
                    <a href="{{ route('reports.profit-loss') }}"
                    class="block px-4 py-2 rounded-lg
                    {{ request()->routeIs('reports.profit-loss*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                        Profit
                    </a>
                    <a href="{{ route('reports.sales') }}"
                    class="block px-4 py-2 rounded-lg 
                    {{ request()->routeIs('reports.sales*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                        Sales
                    </a>
                </div>
            </details>


            {{-- Audit Log --}}
            <a href="{{ route('audit-logs.index') }}"
            class="block px-4 py-2 rounded-lg
            {{ request()->routeIs('audit-logs.*')
                            ? 'bg-indigo-500 text-white'
                            : 'hover:bg-indigo-600' }}">
                Audit Log
            </a>

        </div>
    </nav>


        <main class="flex-1 ml-64 max-w-6xl mx-auto w-full px-4 py-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 text-red-800 px-4 py-3 rounded-lg border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>