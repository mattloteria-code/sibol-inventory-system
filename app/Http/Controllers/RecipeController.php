<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\ProductIngredient;
use App\Http\Requests\StoreRecipeItemRequest;

class RecipeController extends Controller
{
    public function edit(Product $product)
    {
        $product->load('recipeItems.ingredient');

        $usedIngredients = $product->recipeItems->pluck('ingredient_id');

        $availableIngredients = Ingredient::where('is_active', true)
        ->whereNotIn('id', $usedIngredients)
        ->orderBy('name')
        ->get();

        $estimatedCostPerUnit = $product->recipeItems->sum('estimated_cost');

        return view('products.recipe', compact('product', 'availableIngredients', 'estimatedCostPerUnit'));
    }

    public function store(StoreRecipeItemRequest $request, Product $product)
    {
        ProductIngredient::create([
            'product_id' => $product->id,
            'ingredient_id' => $request->ingredient_id,
            'quantity_required' =>$request->quantity_required,
        ]);

        return redirect()->route('products.recipe.edit', $product)
        ->with('success', 'Ingredient added to recipe.');
    }

    public function update(Request $request, Product $product, ProductIngredient $productIngredient)
    {
        $request->validate([
            'quantity_required' => 'required|numeric|min:0.001',
        ]);

        $productIngredient->update([
            'quantity_required' =>$request->quantity_required,
        ]);

        return redirect()->route('products.recipe.edit', $product)
        ->with('success', 'Recipe updated.');
    }

    public function destroy(Product $product, ProductIngredient $productIngredient)
    {
        $productIngredient->delete();

        return redirect()->route('products.recipe.edit', $product)
        ->with('success', 'Ingredient removed from recipe.');
    }
}
