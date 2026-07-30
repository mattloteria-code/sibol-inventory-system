<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function cart(Request $request)
    {
        $cart = session('cart', []);
        $customerId = session('cart_customer_id');
        $customer = $customerId ? Customer::find($customerId) : null;

        $cartItems = collect($cart)->map(function ($item, $productId) {
            $product = Product::find($productId);
            return[
                'product' => $product,
                'quantity' => $item['quantity'],
                'subtotal' => $product ? $product->selling_price * $item['quantity'] : 0,
            ];
        })->filter(fn($item) => $item['product'] !== null);

        $total = $cartItems->sum('subtotal');

        $customers = Customer::orderBy('name')->get();

        $products = Product::where('is_active', true)
        ->where('stock_quantity', '>', 0)
        ->when($request->search, function($query, $search){
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->get();

        return view('orders.cart', compact('cartItems', 'total', 'customer', 'customers', 'products'));
    }


    public function setCustomer(Request $request)
    {
        $request->validate(['customer_id' => 'required|exists:customers,id']);

        session(['cart_customer_id' => $request->customer_id]);

        return back()->with('success', 'Customer selected.');
    }


    public function addToCart(Product $product)
    {
        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = ['quantity' => 1];
        }

        session(['cart' => $cart]);

        return back()->with('success', "{$product->name} added to cart.");
    }


    public function updateCartItem(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $request->quantity;
            session(['cart' => $cart]);
        }

        return back();
    }

    public function removeFromCart(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Item removed.');
    }

    public function clearCart()
    {
        session()->forget(['cart', 'cart_customer_id']);

        return redirect()->route('orders.cart.index')->with('success', 'Cart cleared.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function checkout()
    {
        $cart = session('cart', []);
        $customerId = session('cart_customer_id');

        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        if (!$customerId) {
            return back()->with('error', 'Please select a customer first.');
        }

        $order = DB::transaction(function () use ($cart, $customerId) {
            // Lock the product rows while we check/update stock, to prevent
            // two simultaneous checkouts from overselling the same stock.
            $products = Product::whereIn('id', array_keys($cart))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Validate stock BEFORE creating anything
            foreach ($cart as $productId => $item) {
                $product = $products->get($productId);

                if (!$product) {
                    throw ValidationException::withMessages([
                        'cart' => "A product in your cart no longer exists.",
                    ]);
                }

                if ($product->stock_quantity < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'cart' => "Not enough stock for {$product->name}. Only {$product->stock_quantity} left.",
                    ]);
                }
            }

            // Create the order
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'customer_id' => $customerId,
                'status' => 'pending',
                'total_amount' => 0, // will update after items are added
            ]);

            $total = 0;

            foreach ($cart as $productId => $item) {
                $product = $products->get($productId);
                $quantity = $item['quantity'];

                // Snapshot product details onto the order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $product->selling_price,
                    'unit_cost' => $product->cost_price,
                ]);

                // Deduct stock
                $product->decrement('stock_quantity', $quantity);

                // Log the stock movement
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'sale',
                    'quantity_change' => -$quantity,
                    'quantity_after' => $product->fresh()->stock_quantity,
                    'reason' => "Order #{$order->order_number}",
                ]);

                $total += $product->selling_price * $quantity;
            }

            // Now that we know the total, save it on the order
            $order->update(['total_amount' => $total]);

            return $order;
        });

        // Clear the cart now that the order is safely saved
        session()->forget(['cart', 'cart_customer_id']);

        return redirect()->route('orders.show', $order)
            ->with('success', "Order {$order->order_number} created successfully.");
    }

    private function generateOrderNumber(): string
    {
        $lastOrder = Order::latest('id')->first();
        $nextNumber = $lastOrder ? $lastOrder->id + 1 : 1;

        return 'ORD-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public function show(Order $order)
    {
        $order->load('customer', 'items.product');

        return view('orders.show', compact('order'));
    }

    public function index(Request $request){
        $orders = Order::with('customer')
        ->when($request->search, function ($query, $search){
            $query->where('order_number', 'like', "%{$search}%")
            ->orWhereHas('customer', function ($q) use($search){
                $q->where('name', 'like', "%{$search}%");
            });
        })
        ->when($request->status, function ($query, $status) {
            $query->where('status', $status);
        })
        ->latest()->paginate(10)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $newStatus = $request->status;

        // If moving TO cancelled from a non-cancelled state, restock items
        if ($newStatus === 'cancelled' && $order->status !== 'cancelled') {
            DB::transaction(function () use ($order) {
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);

                    if ($product) {
                        $product->increment('stock_quantity', $item->quantity);

                        StockMovement::create([
                            'product_id' => $product->id,
                            'type' => 'adjustment',
                            'quantity_change' => $item->quantity,
                            'quantity_after' => $product->fresh()->stock_quantity,
                            'reason' => "Order #{$order->order_number} cancelled",
                        ]);
                    }
                }

                $order->update(['status' => 'cancelled']);
            });

            return back()->with('success', 'Order cancelled and stock restored.');
        }

        $order->update(['status' => $newStatus]);

        return back()->with('success', 'Order status updated.');
    }
}
