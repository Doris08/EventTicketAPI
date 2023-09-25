<?php

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'start_date' => 'date|before_or_equal:end_date',
            'start_time' => 'date_format:H:i|before:end_time',
            'end_date' => 'date|after_or_equal:start_date',
            'end_time' => 'date_format:H:i|after:start_time',
            'location' => 'max:250',
            'image_header_url' => 'url',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'The maximun number of characters in Name is 150',
            'description.max' => 'The maximun number of characters in Description is 500',
            'start_date.date' => 'Date format is required in Start Date',
            'start_date.before_or_equal' => 'Start Date needs to be lower or equal than End Date',
            'start_time.date_format' => 'Time format HH:MM is required in Start Time',
            'start_time.before' => 'Start Time needs to be lower than End Time',
            'end_date.date' => 'Date format is required in End Date',
            'end_date.after_or_equal' => 'End Date needs to be higher or equal than Start Date',
            'end_time.date_format' => 'Time format HH:MM is required in End Time',
            'end_time.before' => 'End Time needs to be higher than Start Time',
            'location.max' => 'The maximun number of characters in Location is 250',
            'image_header_url.url' => 'Url format is required in Image',
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
