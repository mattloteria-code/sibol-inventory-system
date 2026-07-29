<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'sku' => ['sometimes','string', 'max:100',
             Rule::unique('products', 'sku')->ignore($this->product)
             ],
            'description' => 'nullable|string',
            'cost_price' => 'sometimes|numeric|min:0',
            'selling_price' => 'sometimes|numeric|min:0',
            'low_stock_threshold' => 'sometimes|integer|min:0',
        ];
    }
}
