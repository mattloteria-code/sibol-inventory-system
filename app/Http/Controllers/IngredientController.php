<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIngredientPurchaseRequest;
use App\Http\Requests\StoreIngredientRequest;
use App\Http\Requests\UpdateIngredientRequest;
use App\Models\Ingredient;
use App\Models\IngredientPriceHistory;
use App\Models\IngredientPurchase;
use App\Support\AuditContext;
use Illuminate\Support\Facades\DB;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredients = Ingredient::orderBy('name')->paginate(15);

        return view('ingredients.index', compact('ingredients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ingredients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIngredientRequest $request)
    {
        $validated = $request->validated();

        $unitType = config("units.units.{$validated['unit']}.type");

        $validated['preferred_unit'] = $validated['unit'];
        $validated['unit_type'] = $unitType;
        $validated['base_unit'] = config("units.base_unit_for_type.{$unitType}");
        unset($validated['unit']);

        Ingredient::create($validated);

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredient $ingredient)
    {
        $purchases = $ingredient->purchases()->latest('id')->paginate(10);
        $priceHistory = $ingredient->priceHistory()->latest('changed_at')->take(10)->get();

        return view('ingredients.show', compact('ingredient', 'purchases', 'priceHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingredient $ingredient)
    {
        return view('ingredients.edit', compact('ingredient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIngredientRequest $request, Ingredient $ingredient)
    {
        $validated = $request->validated();

        $unitType = config("units.units.{$validated['unit']}.type");

        $validated['preferred_unit'] = $validated['unit'];
        $validated['unit_type'] = $unitType;
        $validated['base_unit'] = config("units.base_unit_for_type.{$unitType}");
        unset($validated['unit']);

        $ingredient->update($validated);

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        if ($ingredient->purchases()->exists()){
            return redirect()->route('ingredients.index')
            ->with('error', 'Cannot delete an ingredient with purchase history');
        }

        $ingredient->delete();

        return redirect()->route('ingredients.index')
        ->with('success', 'Ingredient deleted successfully');
    }

    public function purchaseForm()
    {
        $ingredients = Ingredient::where('is_active', true)->orderBy('name')->get();

        return view('ingredients.purchase', compact('ingredients'));
    }

    public function storePurchase(StoreIngredientPurchaseRequest $request)
    {
        DB::transaction(function () use ($request) {
            $ingredient = Ingredient::findOrFail($request->ingredient_id);

            $unitConfig = config("units.units.{$request->unit}");
            $conversionToBase = $unitConfig['to_base'];

            $baseUnitsAdded = $request->quantity * $conversionToBase;
            $pricePerBaseUnit = $request->total_price / $baseUnitsAdded;
            $pricePerPurchaseUnit = $request->total_price / $request->quantity;

            $oldestBefore = $ingredient->purchases()
            ->where('remaining_quantity', '>', 0)
            ->orderBy('purchase_date')
            ->orderBy('id')
            ->first();

            $oldFifoPrice = $oldestBefore
                ? $oldestBefore->price_per_base_unit
                : $ingredient->current_price_per_base_unit;

            $newFifoPrice = $oldestBefore
                ? $oldFifoPrice
                : $pricePerBaseUnit;

            if (bccomp($ingredient->current_price_per_base_unit, $newFifoPrice, 4) !== 0) {
                IngredientPriceHistory::create([
                    'ingredient_id' => $ingredient->id,
                    'old_price' => $ingredient->current_price_per_base_unit,
                    'new_price' => $newFifoPrice,
                    'reason' => 'New purchase recorded',
                    'changed_at' => now(),
                ]);
            }

            IngredientPurchase::create([
                'ingredient_id' => $ingredient->id,
                'purchase_unit' => $request->unit,
                'purchase_unit_quantity' => $request->quantity,
                'conversion_to_base' => $conversionToBase,
                'unit_price' => $pricePerPurchaseUnit,
                'total_cost' => $request->total_price,
                'base_units_added' => $baseUnitsAdded,
                'price_per_base_unit' => $pricePerBaseUnit,
                'remaining_quantity' => $baseUnitsAdded,
                'supplier' => $request->supplier,
                'purchase_date' => $request->purchase_date,
                'notes' => $request->notes,
            ]);

            AuditContext::without(function () use ($ingredient, $baseUnitsAdded, $newFifoPrice) {    
                $ingredient->increment('current_stock', $baseUnitsAdded);
                $ingredient->update(['current_price_per_base_unit' => $newFifoPrice]);
            });
        });

        return redirect()->route('ingredients.purchase.form')
            ->with('success', 'Purchase recorded and stock updated.');
    }
}
