<?php

namespace App\Domain\Expenses\Actions;

use App\Models\Expense;

class CreateExpenseAction
{
    public function execute(array $data, array $totals): Expense
    {
        return Expense::create([
            'user_id' => auth()->user()?->tenantOwnerId() ?? auth()->id(),
            'expense_no' => $this->generateExpenseNo(),
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
            'attachment_path' => $data['attachment_path'] ?? null,
            'created_by' => auth()->id(),
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
    }

    private function generateExpenseNo(): string
    {
        $lastExpense = Expense::withoutGlobalScopes()->orderByDesc('id')->first();
        $lastNumber = $lastExpense ? (int) str_replace('GDR-', '', $lastExpense->expense_no) : 0;

        do {
            $lastNumber++;
            $expenseNo = 'GDR-' . str_pad($lastNumber, 6, '0', STR_PAD_LEFT);
        } while (Expense::withoutGlobalScopes()->where('expense_no', $expenseNo)->exists());

        return $expenseNo;
    }
}
