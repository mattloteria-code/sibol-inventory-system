<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIngredientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('ingredients', 'name')->ignore($this->ingredient),
            ],
            'unit' => ['required', Rule::in(array_keys(config('units.units')))],
            'low_stock_threshold' => 'required|numeric|min:0',
        ];
    }
}