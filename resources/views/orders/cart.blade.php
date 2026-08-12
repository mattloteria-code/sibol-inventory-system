@extends('layouts.app')

@section('title', 'New Order')

@section('content')
<h1 class="text-2xl font-bold mb-6">New Order</h1>

<div class="grid grid-cols-3 gap-6">
    {{-- LEFT: Customer + Product selection --}}
    <div class="col-span-2 space-y-6">

        {{-- Customer selection --}}
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="font-semibold mb-3">Customer</h2>
            @if ($customer)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium">{{ $customer->name }}</p>
                        <p class="text-sm text-gray-500">{{ $customer->phone ?? $customer->email ?? '' }}</p>
                    </div>
                    <form action="{{ route('orders.cart.set-customer') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <select name="customer_id" class="border border-gray-300 rounded-lg px-2 py-1 text-sm" onchange="this.form.submit()">
                            <option value="">Change customer...</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @else
                <form action="{{ route('orders.cart.set-customer') }}" method="POST" class="flex gap-2">
                    @csrf
                    <select name="customer_id" required class="flex-1 border border-gray-300 rounded-lg px-3 py-2
                    onchange="this.form.submit()">
                        <option value="">Select a customer...</option>
                        @foreach ($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Select</button>
                </form>
                <p class="text-sm text-gray-400 mt-2">
                    No customer yet? <a href="{{ route('customers.create') }}" class="text-indigo-600 hover:underline">Add one first</a>.
                </p>
            @endif
        </div>

        {{-- Product search --}}
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="font-semibold mb-3">Add Products</h2>
            <form action="{{ route('orders.cart.index') }}" method="GET" class="mb-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or SKU..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </form>

            <div class="divide-y divide-gray-100">
                @forelse ($products as $product)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium">{{ $product->name }}</p>
                            <p class="text-sm text-gray-500">
                                ₱{{ number_format($product->selling_price, 2) }} · {{ $product->stock_quantity }} in stock
                            </p>
                        </div>
                        <form action="{{ route('orders.cart.add', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-indigo-700">
                                + Add
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-6">No products found.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- RIGHT: Cart summary --}}
    <div class="bg-white rounded-lg shadow p-4 h-fit">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Cart</h2>
            @if ($cartItems->isNotEmpty())
                <form action="{{ route('orders.cart.clear') }}" method="POST" onsubmit="return confirm('Clear cart?')">
                    @csrf
                    <button type="submit" class="text-red-600 text-xs hover:underline">Clear all</button>
                </form>
            @endif
        </div>

        @forelse ($cartItems as $productId => $item)
            <div class="py-3 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium">{{ $item['product']->name }}</p>
                    <form action="{{ route('orders.cart.remove', $item['product']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 text-xs hover:underline">Remove</button>
                    </form>
                </div>
                <div class="flex items-center justify-between mt-1">
                    <form action="{{ route('orders.cart.update', $item['product']) }}" method="POST" class="flex items-center gap-1">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                               max="{{ $item['product']->stock_quantity }}"
                               class="w-16 border border-gray-300 rounded px-2 py-1 text-sm">
                        <button type="submit" class="text-indigo-600 text-xs hover:underline">Update</button>
                    </form>
                    <span class="text-sm text-gray-600">₱{{ number_format($item['subtotal'], 2) }}</span>
                </div>
            </div>
        @empty
            <p class="text-gray-400 text-sm text-center py-6">No items yet.</p>
        @endforelse

        {{-- Payment method selection --}}
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="font-semibold mb-3">Payment Method</h2>
            <form action="{{ route('orders.cart.payment-method') }}" method="POST" class="flex gap-2">
                @csrf
                <select name="payment_method" required class="flex-1 border border-gray-300 rounded-lg px-3 py-2"
                        onchange="this.form.submit()">
                    <option value="">Select payment method...</option>
                    @foreach (config('payments.methods') as $value => $label)
                        <option value="{{ $value }}" {{ $paymentMethod == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
            @if ($paymentMethod)
                <p class="text-sm text-green-600 mt-2">✓ {{ config("payments.methods.$paymentMethod") }} selected</p>
            @endif
        </div>

        @if ($cartItems->isNotEmpty())
            <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-200 font-semibold">
                <span>Total</span>
                <span>₱{{ number_format($total, 2) }}</span>
            </div>

            @if (!$customer)
                <p class="text-xs text-red-500 mt-2">Select a customer before checking out.</p>
            @elseif (!$paymentMethod)
                <p class="text-xs text-red-500 mt-2">Select a payment method before checking out.</p>
            @else

                <form action="{{ route('orders.cart.checkout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full mt-4 bg-green-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-green-700">
                        Checkout & Create Order
                    </button>
                </form>
            @endif
        @endif
    </div>
</div>
@endsection