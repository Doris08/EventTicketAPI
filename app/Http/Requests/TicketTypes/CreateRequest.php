<?php

namespace App\Http\Requests\TicketTypes;

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
            'event_id' => 'required|exists:users,id',
            'name' => 'required|max:150',
            'description' => 'max:500',
            'quantity_available' => 'required|integer',
            'price' => 'required|float',
            'sale_start_date' => 'required|date|before:sale_end_date',
            'sale_start_time' => 'required|time|before:sale_end_time',
            'sale_end_date' => 'required|date|after:sale_start_date',
            'sale_end_time' => 'required|time|after:sale_start_time',
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
            'description.max' => 'The maximun number of characters in Name is 150',
            'quantity_available.required' => 'Image is required',
            'quantity_available.integer' => 'Integer format is required in Quantity Available',
            'sale_start_date.required' => 'Sale Start Date is required',
            'sale_start_date.date' => 'Date format is required in Sale Start Date',
            'sale_start_date.before' => 'Sale Start Date needs to be lower than Sale End Date',
            'sale_start_time.required' => 'Sale Start Time is required',
            'sale_start_time.date' => 'Time format is required in Sale Start Time',
            'sale_start_time.before' => 'Sale Start Time needs to be lower than Sale End Time',
            'sale_end_date.required' => 'Sale End Date is required',
            'sale_end_date.date' => 'Date format is required in Sale End Date',
            'sale_end_date.before' => 'Sale End Date needs to be higher than Sale Start Date',
            'sale_end_time.required' => 'Sale End Time is required',
            'sale_end_time.date' => 'Time format is required in Sale End Time' ,
            'sale_end_time.before' => 'Sale End Time needs to be higher than Sale Start Time',
            'purchase_limit.required' => 'Purchase Limit is required',
            'purchase_limit.integer' => 'Integer format is required in Purchase Limit',
        ];
    }
}