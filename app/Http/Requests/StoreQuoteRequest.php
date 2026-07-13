<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'quote_date' => ['required', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:quote_date'],
            'probability' => ['nullable', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
            'terms' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.warehouse_id' => ['required', 'exists:warehouses,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
            'items.*.vat' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Cari seçiniz.',
            'warehouse_id.required' => 'Depo seçiniz.',
            'quote_date.required' => 'Teklif tarihi zorunludur.',
            'valid_until.after_or_equal' => 'Geçerlilik tarihi teklif tarihinden önce olamaz.',
            'items.required' => 'En az bir ürün eklemelisiniz.',
            'items.min' => 'En az bir ürün eklemelisiniz.',
            'items.*.product_id.required' => 'Ürün seçiniz.',
            'items.*.warehouse_id.required' => 'Depo seçiniz.',
            'items.*.quantity.required' => 'Miktar giriniz.',
            'items.*.quantity.min' => "Miktar 0'dan büyük olmalıdır.",
        ];
    }
}
