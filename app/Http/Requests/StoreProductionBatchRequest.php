<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreProductionBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity_produced' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'ingredients' => 'required|array|min:1',
            'ingredients.*' => 'required|numeric|min:0.001',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $product = Product::with('recipeItems')->find($this->product_id);

            if (!$product) {
                return;
            }

            $validRecipeIngredientIds = $product->recipeItems->pluck('ingredient_id')->toArray();
            $submittedIngredientIds = array_keys($this->ingredients ?? []);

            foreach ($submittedIngredientIds as $ingredientId) {
                if (!in_array($ingredientId, $validRecipeIngredientIds)) {
                    $validator->errors()->add('ingredients', 'One of the submitted ingredients is not part of this product\'s recipe.');
                }
            }
        });
    }
}