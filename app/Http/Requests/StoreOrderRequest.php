<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type_id' => 'required|exists:order_types,id',
            'partnership_id' => 'sometimes|required|exists:partnerships,id',
            'description' => 'nullable|string|max:65535',
            'date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'address' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ];
    }
}
