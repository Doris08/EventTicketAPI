<?php

namespace App\Http\Requests\Refunds;

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
            'order_detail_id' => 'required|exists:order_details,id',
            'date' => 'required|date',
            'time' => 'required|time',
            'reason' => 'required|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'order_detail_id.exists' => 'Order Detail does not exist',
            'order_detail_id.required' => 'Order Detail is required',
            'date.required' => 'Date is required',
            'date.date' => 'Date format is required',
            'time.required' => 'Time is required',
            'time.time' => 'Time format is required',
            'reason.required' => 'Reason is required',
            'reason.max' => 'The maximun number of characters in Reason is 500',
        ];
    }
}
