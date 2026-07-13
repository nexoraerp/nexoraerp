<?php

namespace App\Domain\Expenses\Actions;

use App\Exceptions\BusinessException;
use App\Models\Expense;

class CancelExpenseAction
{
    public function execute(Expense $expense, string $reason): Expense
    {
        if ($expense->status === 'Cancelled') {
            throw new BusinessException('Bu gider zaten iptal edilmiş.');
        }

        $expense->update([
            'status' => 'Cancelled',
            'remaining_total' => 0,
            'cancel_reason' => $reason,
            'cancelled_by' => auth()->id(),
            'cancelled_at' => now(),
        ]);

        return $expense->fresh(['category', 'creator']);
    }
}
