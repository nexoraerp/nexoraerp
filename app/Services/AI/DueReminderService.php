<?php

namespace App\Services\AI;

use App\Models\Sale;
use Carbon\Carbon;

class DueReminderService
{
    public function reminders(): array
    {
        $today = today();

        $sales = Sale::query()
            ->with('customer')
            ->where('status', 'Completed')
            ->where('remaining_total', '>', 0)
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', $today->copy()->addDays(7))
            ->orderBy('due_date')
            ->get();

        $items = $sales->map(function (Sale $sale) use ($today) {
            $dueDate = Carbon::parse($sale->due_date);
            $days = $today->diffInDays($dueDate, false);

            return [
                'sale_id' => $sale->id,
                'sale_no' => $sale->sale_no,
                'customer' => $sale->customer?->name,
                'due_date' => $dueDate->toDateString(),
                'days' => $days,
                'remaining_total' => (float) $sale->remaining_total,
                'status' => $this->status($days),
                'message' => $this->message($sale, $days),
            ];
        });

        return [
            'overdue_count' => $items->where('status', 'overdue')->count(),
            'today_count' => $items->where('status', 'today')->count(),
            'upcoming_count' => $items->where('status', 'upcoming')->count(),
            'total_amount' => $items->sum('remaining_total'),
            'items' => $items->values()->all(),
        ];
    }

    private function status(int $days): string
    {
        if ($days < 0) {
            return 'overdue';
        }

        if ($days === 0) {
            return 'today';
        }

        return 'upcoming';
    }

    private function message(Sale $sale, int $days): string
    {
        $amount = number_format((float) $sale->remaining_total, 2, ',', '.');

        if ($days < 0) {
            return "{$sale->customer?->name} için {$sale->sale_no} vadesi ".abs($days)." gün geçmiş. Kalan tutar: ₺{$amount}.";
        }

        if ($days === 0) {
            return "{$sale->customer?->name} için {$sale->sale_no} vadesi bugün. Kalan tutar: ₺{$amount}.";
        }

        return "{$sale->customer?->name} için {$sale->sale_no} vadesine {$days} gün kaldı. Kalan tutar: ₺{$amount}.";
    }
}
