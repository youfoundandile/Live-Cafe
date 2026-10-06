<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Define the validation rules for the request
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],   // real count for merch, placeholder for made items
            'prod_availability' => ['boolean'],

            'ingredients' => ['nullable', 'array'],         // leave empty for merch
            'ingredients.*.id' => ['required', 'distinct', 'exists:ingredients,id'],
            'ingredients.*.quantity' => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->isAdmin();

    }
}
