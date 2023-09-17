<?php

namespace App\Http\Requests\OrderDetails;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'quantity' => 'integer',
            'sale_price' => 'float',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.integer' => 'Integer format is required in Quantity',
            'sale_price.float' => 'Float format is required in Sale Price',
        ];
    }
}
