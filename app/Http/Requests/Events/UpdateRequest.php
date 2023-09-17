<?php

namespace App\Http\Requests\Events;

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
            'start_date' => 'date|before:end_date',
            'start_time' => 'time|before:end_time',
            'end_date' => 'date|after:start_date',
            'end_time' => 'time|after:start_time',
            'location' => 'max:250',
            'status' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'The maximun number of characters in Name is 150',
            'description.max' => 'The maximun number of characters in Name is 150',
            'start_date.date' => 'Date format is required',
            'start_date.before' => 'Start Date needs to be lower than End Date',
            'start_time.date' => 'Time format is required',
            'start_time.before' => 'Start Time needs to be lower than End Time',
            'end_date.date' => 'Date format is required',
            'end_date.before' => 'End Date needs to be higher than Start Date',
            'end_time.date' => 'Time format is required',
            'end_time.before' => 'End Time needs to be higher than Start Time',
            'location.max' => 'The maximun number of characters in Location is 250',
            'status.boolean' => 'Boolean format is required',
        ];
    }
}
