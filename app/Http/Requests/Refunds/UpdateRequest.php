<?php

namespace App\Http\Requests\Refunds;

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
            'date' => 'date',
            'time' => 'time',
            'reason' => 'max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'date.date' => 'Date format is required',
            'time.time' => 'Time format is required',
            'reason.max' => 'The maximun number of characters in Reason is 500',
        ];
    }
}
