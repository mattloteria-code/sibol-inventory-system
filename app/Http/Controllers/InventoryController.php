<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $lowStockCount = Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->where('is_active', true)
            ->count();

        $totalProducts = Product::where('is_active', true)->count();

        $recentMovements = StockMovement::with('product')->latest()->take(10)->get();

        return view('inventory.index', compact('lowStockCount', 'totalProducts', 'recentMovements'));
    }

    public function restockForm()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('inventory.restock', compact('products'));
    }

    public function restock(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::findOrFail($validated['product_id']);

            $product->increment('stock_quantity', $validated['quantity']);

            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'restock',
                'quantity_change' => $validated['quantity'],
                'quantity_after' => $product->fresh()->stock_quantity,
                'reason' => $validated['reason'] ?? 'Manual restock',
            ]);
        });

        return redirect()->route('inventory.restock.form')
            ->with('success', 'Stock updated successfully.');
    }

    public function movements(Request $request)
    {
        $movements = StockMovement::with('product')
            ->when($request->product_id, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $products = Product::orderBy('name')->get();

        return view('inventory.movements', compact('movements', 'products'));
    }

    public function lowStock()
    {
        $products = Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->where('is_active', true)
            ->orderBy('stock_quantity')
            ->paginate(15);

        return view('inventory.low-stock', compact('products'));
    }
}