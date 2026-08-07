<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductionBatch;
use App\Http\Requests\StoreProductionBatchRequest;
use App\Services\ProductionService;

class ProductionController extends Controller
{
    public function index()
    {
        $batches = ProductionBatch::with('product')->latest('produced_at')->paginate(15);

        return view('production.index', compact('batches'));
    }

    public function selectProduct()
    {
        $products = Product::where('is_active', true)
        ->whereHas('recipeItems')
        ->orderBy('name')
        ->get();

        return view('production.select-product', compact('products'));
    }

    public function create(Product $product)
    {
        $product->load('recipeItems.ingredient');

        if ($product->recipeItems->isEmpty()) {
            return redirect()->route('production.create')
            ->with('error', "{$product->name} has no recipe defined.");
        }

        return view('production.create', compact('product'));
    }

    public function store(StoreProductionBatchRequest $request, ProductionService $productionService)
    {
        $product = Product::findOrFail($request->product_id);

        try {
            $batch = $productionService->produceBatch(
                $product,
                $request->ingredients,
                (int) $request->quantity_produced,
                $request->notes
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('production.show', $batch)
            ->with('success', "Batch of {$batch->quantity_produced} {$product->name} produced successfully.");
    }

    public function show(ProductionBatch $production)
    {
        $production->load('product', 'batchIngredients.ingredient', 'batchIngredients.purchase');

        return view('production.show', ['batch' => $production]);
    }
}