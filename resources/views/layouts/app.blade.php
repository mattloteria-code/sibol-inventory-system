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
        <nav class="bg-indigo-600 text-white shadow">
            <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="font-bold text-lg">Order Tracker</a>
                <div class="space-x-4 text-sm">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-200">Dashboard</a>
                    <a href="{{ route('products.index') }}" class="hover:text-indigo-200">Products</a>
                    <a href="{{ route('categories.index') }}" class="hover:text-indigo-200">Categories</a>
                    <a href="{{ route('expenses.index') }}" class="hover:text-indigo-200">Expenses</a>
                    <a href="{{ route('expense-categories.index') }}" class="hover:text-indigo-200">Expense Categories</a>
                    <a href="{{ route('customers.index') }}" class="hover:text-indigo-200">Customers</a>
                    <a href="{{ route('orders.cart.index') }}" class="hover:text-indigo-200">New Order</a>
                    <a href="{{ route('orders.index') }}" class="hover:text-indigo-200">Orders</a>
                    <a href="{{ route('inventory.index') }}" class="hover:text-indigo-200">Inventory</a>
                    <a href="{{ route('ingredients.index') }}" class="hover:text-indigo-200">Ingredients</a>
                    <a href="{{ route('production.index') }}" class="hover:text-indigo-200">Production</a>
                    <a href="{{ route('reports.index') }}" class="hover:text-indigo-200">Reports</a>
                </div>
            </div>
        </nav>

        <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8">
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