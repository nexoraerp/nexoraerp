<?php

namespace App\Domain\Expenses\Actions;

class CalculateExpenseTotalsAction
{
    public function execute(array $data): array
    {
        $subtotal = round((float) ($data['subtotal'] ?? 0), 2);
        $discount = round((float) ($data['discount'] ?? 0), 2);
        $vatRate = round((float) ($data['vat_rate'] ?? 0), 2);
        $netTotal = max(0, $subtotal - $discount);
        $vat = round($netTotal * ($vatRate / 100), 2);
        $grandTotal = round($netTotal + $vat, 2);
        $paidTotal = min(round((float) ($data['paid_total'] ?? 0), 2), $grandTotal);
        $remainingTotal = round(max(0, $grandTotal - $paidTotal), 2);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'vat_rate' => $vatRate,
            'vat' => $vat,
            'grand_total' => $grandTotal,
            'paid_total' => $paidTotal,
            'remaining_total' => $remainingTotal,
            'payment_status' => $this->paymentStatus($paidTotal, $grandTotal),
            'status' => $this->status($paidTotal, $grandTotal),
        ];
    }

    private function paymentStatus(float $paidTotal, float $grandTotal): string
    {
        if ($paidTotal <= 0) {
            return 'Unpaid';
        }

        if ($paidTotal >= $grandTotal) {
            return 'Paid';
        }

        return 'Partial';
    }

    private function status(float $paidTotal, float $grandTotal): string
    {
        if ($paidTotal <= 0) {
            return 'Approved';
        }

        if ($paidTotal >= $grandTotal) {
            return 'Paid';
        }

        return 'PartiallyPaid';
    }
}
