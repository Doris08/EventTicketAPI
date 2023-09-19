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
            'order_details' => 'required|array'
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.exists' => 'Event does not exist',
            'event_id.required' => 'Event is required',
            'purchase_date.required' => 'Date is required',
            'purchase_date.date' => 'Date format is required',
            'order_details.required' => 'Needs at least 1 Order Detail',
            'order_details.array' => 'Array format is required in Order Detail',
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
