<?php

namespace App\Http\Requests\Orders;

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
            'event_id' => 'required|exists:events,id',
            'purchase_date' => 'required|date',
            'status' => 'required|max:25',
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.exists' => 'Event does not exist',
            'event_id.required' => 'Event is required',
            'purchase_date.required' => 'Date is required',
            'purchase_date.date' => 'Date format is required',
            'status.required' => 'Status is required',
            'status.max' => 'The maximun number of characters in Status is 25',
        ];
    }
}
