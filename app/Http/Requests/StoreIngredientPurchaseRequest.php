<?php

namespace App\Http\Requests;

use App\Models\Ingredient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIngredientPurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ingredient_id' => 'required|exists:ingredients,id',
            'unit' => [
                'required',
                Rule::in(array_keys(config('units.units'))),
                function ($attribute, $value, $fail) {
                    $ingredient = Ingredient::find($this->ingredient_id);

                    if ($ingredient) {
                        $unitType = config("units.units.{$value}.type");

                        if ($unitType !== $ingredient->unit_type) {
                            $fail("This unit doesn't match {$ingredient->name}'s type ({$ingredient->unit_type}).");
                        }
                    }
                },
            ],
            'quantity' => 'required|numeric|min:0.001',
            'total_price' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }
}