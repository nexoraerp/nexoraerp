<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => [
                'required',
                'exists:customers,id',
            ],

            'warehouse_id' => [
                'required',
                'exists:warehouses,id',
            ],

            'sale_date' => [
                'required',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:sale_date',
                'required_unless:payment_type,Cash',
            ],

            'payment_type' => [
                'required',
                'in:Cash,Credit,Card,Bank,Mixed',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],

            'items.*.warehouse_id' => [
                'required',
                'exists:warehouses,id',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'items.*.unit_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items.*.discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.vat' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Cari seçiniz.',
            'warehouse_id.required' => 'Depo seçiniz.',
            'sale_date.required' => 'Satış tarihi zorunludur.',
            'due_date.required_unless' => 'Vadeli satışlarda vade tarihi zorunludur.',
            'items.required' => 'En az bir ürün eklemelisiniz.',
            'items.min' => 'En az bir ürün eklemelisiniz.',
            'items.*.product_id.required' => 'Ürün seçiniz.',
            'items.*.quantity.required' => 'Miktar giriniz.',
            'items.*.quantity.min' => "Miktar 0'dan büyük olmalıdır.",
        ];
    }
}
