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
            'order_id' => 'required|exists:orders,id',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1',
            'sale_price' => 'required|float',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.exists' => 'Order does not exist',
            'order_id.required' => 'Order is required',
            'ticket_type_id.exists' => 'Ticket Type does not exist',
            'ticket_type_id.required' => 'Ticket Type is required',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Integer format is required in Quantity',
            'quantity.min' => 'Quantity cannot be lower than 1',
            'sale_price.required' => 'Sale Price is required',
            'sale_price.float' => 'Float format is required in Sale Price',
        ];
    }
}
