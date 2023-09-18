<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
            'email' => 'required|email|max:150',
            'password' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Email format is required',
            'email.max' => 'The maximun number of characters in Email is 150',
            'password.required' => 'Password is required',
            'password.min' => 'The minimum number of characters in Password is 6',
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
