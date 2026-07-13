<?php

namespace App\Domain\Expenses\Services;

use App\Domain\Expenses\Actions\CalculateExpenseTotalsAction;
use App\Domain\Expenses\Actions\CancelExpenseAction;
use App\Domain\Expenses\Actions\CreateExpenseAction;
use App\Domain\Expenses\Actions\CreateExpensePaymentMovementAction;
use App\Domain\Expenses\Actions\UpdateExpenseAction;
use App\Models\CashMovement;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function __construct(
        protected CalculateExpenseTotalsAction $calculateTotals,
        protected CreateExpenseAction $createExpense,
        protected UpdateExpenseAction $updateExpense,
        protected CancelExpenseAction $cancelExpense,
        protected CreateExpensePaymentMovementAction $createPaymentMovement,
    ) {
    }

    public function create(array $data): Expense
    {
        return DB::transaction(function () use ($data) {
            $expense = $this->createExpense->execute(
                $data,
                $this->calculateTotals->execute($data)
            );

            $this->createPaymentMovement->execute($expense);

            return $expense->fresh(['category', 'creator']);
        });
    }

    public function update(Expense $expense, array $data): Expense
    {
        return DB::transaction(function () use ($expense, $data) {
            $expense = $this->updateExpense->execute(
                $expense,
                $data,
                $this->calculateTotals->execute($data)
            );

            CashMovement::where('reference_type', Expense::class)
                ->where('reference_id', $expense->id)
                ->whereIn('type', ['EXPENSE', 'EXPENSE_CANCEL'])
                ->delete();

            $this->createPaymentMovement->execute($expense);

            return $expense;
        });
    }

    public function cancel(Expense $expense, string $reason): Expense
    {
        return DB::transaction(function () use ($expense, $reason) {
            $expense = $this->cancelExpense->execute($expense, $reason);
            $this->createPaymentMovement->execute($expense, true);

            return $expense;
        });
    }
}
