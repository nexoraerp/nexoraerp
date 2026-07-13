<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CancelSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cancel_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'cancel_reason.required' => 'İptal sebebi zorunludur.',
            'cancel_reason.max' => 'İptal sebebi en fazla 1000 karakter olabilir.',
        ];
    }
}
