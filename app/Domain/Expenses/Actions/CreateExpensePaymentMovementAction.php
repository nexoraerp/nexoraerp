<?php

namespace App\Domain\Expenses\Actions;

use App\Models\CashMovement;
use App\Models\Expense;

class CreateExpensePaymentMovementAction
{
    public function execute(Expense $expense, bool $reversal = false): void
    {
        if ((float) $expense->paid_total <= 0 || ! $expense->payment_source_id) {
            return;
        }

        CashMovement::create([
            'cash_account_id' => $expense->payment_source_id,
            'movement_date' => $expense->expense_date,
            'type' => $reversal ? 'EXPENSE_CANCEL' : 'EXPENSE',
            'reference_type' => Expense::class,
            'reference_id' => $expense->id,
            'debit' => $reversal ? $expense->paid_total : 0,
            'credit' => $reversal ? 0 : $expense->paid_total,
            'description' => ($reversal ? 'Gider iptal ters kaydı : ' : 'Gider ödemesi : ') . $expense->expense_no,
            'user_id' => auth()->user()?->tenantOwnerId() ?? auth()->id(),
        ]);
    }
}
