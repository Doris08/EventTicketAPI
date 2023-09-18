<?php

namespace App\Http\Requests\Attendees;

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
            'name' => 'required|max:250',
            'email' => 'required|max:250|email',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.max' => 'The maximun number of characters in name is 250',
            'email.required' => 'Email is required',
            'email.max' => 'The maximun number of characters in email is 250',
            'email.email' => 'Email format is required',
        ];
    }
}
