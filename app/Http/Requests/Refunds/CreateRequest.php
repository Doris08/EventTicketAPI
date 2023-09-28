<?php

namespace App\Http\Requests\Refunds;

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
            'ticket_type_id' => 'required',
            'quantity_to_refund' => 'required|min:1',
            'reason' => 'required|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'order_detail_id.exists' => 'Order Detail does not exist',
            'order_detail_id.required' => 'Order Detail is required',
            'ticket_type_id.exists' => 'TicketType does not exist',
            'ticket_type_id.required' => 'TicketType is required',
            'quantity_to_refund.required' => 'Quantity to refund is required',
            'quantity_to_refund.min' => 'Quantity to refund minimun is 1',
            'reason.required' => 'Reason is required',
            'reason.max' => 'The maximun number of characters in Reason is 25'
        ];
    }
}
