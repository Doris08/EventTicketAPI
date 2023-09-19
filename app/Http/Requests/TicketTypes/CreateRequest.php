<?php

namespace App\Http\Requests\TicketTypes;

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
            'name' => 'required|max:150',
            'description' => 'max:500',
            'quantity_available' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'sale_start_date' => 'required|date|before_or_equal:sale_end_date',
            'sale_start_time' => 'required|date_format:H:i|before:sale_end_time',
            'sale_end_date' => 'required|date|after_or_equal:sale_start_date',
            'sale_end_time' => 'required|date_format:H:i|after:sale_start_time',
            'purchase_limit' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.exists' => 'Event does not exist',
            'event_id.required' => 'Event is required',
            'name.required' => 'Name is required',
            'name.max' => 'The maximun number of characters in Name is 150',
            'description.max' => 'The maximun number of characters in Description is 500',
            'price.required' => 'Price is required',
            'price.numeric' => 'Numeric format is required in Price',
            'quantity_available.required' => 'Quantity Available is required',
            'quantity_available.integer' => 'Integer format is required in Quantity Available',
            'quantity_available.min' => 'Quantity Available cannot be lower than 1',
            'sale_start_date.required' => 'Sale Start Date is required',
            'sale_start_date.date' => 'Date format is required in Sale Start Date',
            'sale_start_date.before_or_equal' => 'Sale Start Date needs to be lower or equal than Sale End Date',
            'sale_start_time.required' => 'Sale Start Time is required',
            'sale_start_time.date_format' => 'Time format HH:MM is required in Sale Start Time',
            'sale_start_time.before' => 'Sale Start Time needs to be lower than Sale End Time',
            'sale_end_date.required' => 'Sale End Date is required',
            'sale_end_date.date' => 'Date format is required in Sale End Date',
            'sale_end_date.before_or_equal' => 'Sale End Date needs to be higher or equal than Sale Start Date',
            'sale_end_time.required' => 'Sale End Time is required',
            'sale_end_time.date_format' => 'Time format HH:MM is required in Sale End Time',
            'sale_end_time.before' => 'Sale End Time needs to be higher than Sale Start Time',
            'purchase_limit.required' => 'Purchase Limit is required',
            'purchase_limit.integer' => 'Integer format is required in Purchase Limit',
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
