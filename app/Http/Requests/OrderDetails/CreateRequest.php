<?php

namespace App\Http\Requests\OrderDetails;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1',
            'sale_price' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_type_id.exists' => 'Ticket Type does not exist',
            'ticket_type_id.required' => 'Ticket Type is required',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Integer format is required in Quantity',
            'quantity.min' => 'Quantity cannot be lower than 1',
            'sale_price.required' => 'Sale Price is required',
            'sale_price.numeric' => 'Numeric format is required in Sale Price',
        ];
    }
}
