<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expense_category_id' => ['required', 'exists:expense_categories,id'],
            'expense_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:expense_date'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'document_no' => ['nullable', 'string', 'max:255'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'vat_rate' => ['nullable', 'numeric', 'min:0'],
            'paid_total' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', Rule::in(['Cash', 'Bank', 'Card', 'Mixed'])],
            'payment_source_type' => ['nullable', Rule::in(['cash_account'])],
            'payment_source_id' => ['nullable', 'exists:cash_accounts,id'],
            'notes' => ['nullable', 'string'],
            'attachment_path' => ['nullable', 'string', 'max:255'],
        ];
    }
}
