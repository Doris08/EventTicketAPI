<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'event_id' => 'required|exists:events,id',
            'purchase_date' => 'required|date',
            'attendee_name' => 'required|max:150',
            'attendee_email' => 'required|max:150',
            'order_details' => 'required|array',
            'order_details.*.ticket_type_id' => 'required|exists:ticket_types,id',
            'order_details.*.quantity' => 'required|integer|min:1',

        ];
    }

    public function messages(): array
    {
        return [
            'event_id.exists' => 'Event does not exist',
            'event_id.required' => 'Event is required',
            'purchase_date.required' => 'Date is required',
            'purchase_date.date' => 'Date format is required',
            'attendee_name.required' => 'Attendee Name is required',
            'attendee_name.max' => 'The maximun number of characters in Attendee Name is 150',
            'attendee_email.required' => 'Attendee Email is required',
            'attendee_email.max' => 'The maximun number of characters in Attendee Email is 150',
            'order_details.required' => 'Needs at least 1 Order Detail',
            'order_details.array' => 'Array format is required in Order Detail',
            'order_details.*.ticket_type_id.exists' => 'Ticket Type does not exist',
            'order_details.*.ticket_type_id.required' => 'Ticket Type is required',
            'order_details.*.quantity.required' => 'Quantity is required',
            'order_details.*.quantity.integer' => 'Integer format is required in Quantity',
            'order_details.*.quantity.min' => 'Quantity cannot be lower than 1',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = [
            'status' => false,
            'code' => 422,
            'message' => 'Unprocessable entity',
            'errors_validation' =>  $validator->errors()
        ];

        throw new HttpResponseException(response()->json($errors, 422));
    }
}
