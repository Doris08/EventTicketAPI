<?php

namespace App\Http\Requests\TicketTypes;

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
            'name' => 'max:150',
            'description' => 'max:500',
            'quantity_available' => 'integer|min:1',
            'price' => 'float',
            'sale_start_date' => 'date|before:sale_end_date',
            'sale_start_time' => 'time|before:sale_end_time',
            'sale_end_date' => 'date|after:sale_start_date',
            'sale_end_time' => 'time|after:sale_start_time',
            'purchase_limit' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'The maximun number of characters in Name is 150',
            'description.max' => 'The maximun number of characters in Name is 150',
            'quantity_available.integer' => 'Integer format is required in Quantity Available',
            'quantity_available.min' => 'Quantity Available cannot be lower than 1',
            'sale_start_date.date' => 'Date format is required in Sale Start Date',
            'sale_start_date.before' => 'Sale Start Date needs to be lower than Sale End Date',
            'sale_start_time.date' => 'Time format is required in Sale Start Time',
            'sale_start_time.before' => 'Sale Start Time needs to be lower than Sale End Time',
            'sale_end_date.date' => 'Date format is required in Sale End Date',
            'sale_end_date.before' => 'Sale End Date needs to be higher than Sale Start Date',
            'sale_end_time.date' => 'Time format is required in Sale End Time' ,
            'sale_end_time.before' => 'Sale End Time needs to be higher than Sale Start Time',
            'purchase_limit.integer' => 'Integer format is required in Purchase Limit',
        ];
    }
}
