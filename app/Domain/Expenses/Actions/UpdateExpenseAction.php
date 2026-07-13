<?php

namespace App\Domain\Expenses\Actions;

use App\Models\Expense;

class UpdateExpenseAction
{
    public function execute(Expense $expense, array $data, array $totals): Expense
    {
        $expense->update([
            'expense_category_id' => $data['expense_category_id'],
            'expense_date' => $data['expense_date'],
            'due_date' => $data['due_date'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'supplier_name' => $data['supplier_name'] ?? null,
            'document_no' => $data['document_no'] ?? null,
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount'],
            'vat_rate' => $totals['vat_rate'],
            'vat' => $totals['vat'],
            'grand_total' => $totals['grand_total'],
            'paid_total' => $totals['paid_total'],
            'remaining_total' => $totals['remaining_total'],
            'payment_status' => $totals['payment_status'],
            'payment_method' => $data['payment_method'] ?? null,
            'payment_source_type' => $data['payment_source_type'] ?? null,
            'payment_source_id' => $data['payment_source_id'] ?? null,
            'status' => $totals['status'],
            'notes' => $data['notes'] ?? null,
            'attachment_path' => $data['attachment_path'] ?? $expense->attachment_path,
        ]);

        return $expense->fresh(['category', 'creator']);
    }
}
