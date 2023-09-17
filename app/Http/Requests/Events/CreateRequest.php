<?php

namespace App\Http\Requests\Events;

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
            'name' => 'required|max:150',
            'description' => 'max:500',
            'start_date' => 'required|date|before:end_date',
            'start_time' => 'required|time|before:end_time',
            'end_date' => 'required|date|after:start_date',
            'end_time' => 'required|time|after:start_time',
            'location' => 'required|max:250',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.max' => 'The maximun number of characters in Name is 150',
            'description.max' => 'The maximun number of characters in Name is 150',
            'start_date.required' => 'Start Date is required',
            'start_date.date' => 'Date format is required',
            'start_date.before' => 'Start Date needs to be lower than End Date',
            'start_time.required' => 'Start Time is required',
            'start_time.date' => 'Time format is required',
            'start_time.before' => 'Start Time needs to be lower than End Time',
            'end_date.required' => 'End Date is required',
            'end_date.date' => 'Date format is required',
            'end_date.before' => 'End Date needs to be higher than Start Date',
            'end_time.required' => 'End Time is required',
            'end_time.date' => 'Time format is required',
            'end_time.before' => 'End Time needs to be higher than Start Time',
            'location.required' => 'Location is required',
            'location.max' => 'The maximun number of characters in Location is 250',
            'image_header_url.required' => 'Image is required',
            'status.required' => 'Status is required',
            'status.boolean' => 'Boolean format is required',
        ];
    }
}
