<?php

namespace App\Services\Sales\Actions;

class CalculateSaleTotalsAction
{
    /**
     * Satış toplamlarını hesaplar.
     */
    public function execute(array $items): array
    {
        $subtotal = 0;
        $discount = 0;
        $vat = 0;

        foreach ($items as $item) {

            $quantity = (float) ($item['quantity'] ?? 0);

            $unitPrice = (float) ($item['unit_price'] ?? 0);

            $lineDiscount = (float) ($item['discount'] ?? 0);

            $vatRate = (float) ($item['vat'] ?? 0);

            $lineSubtotal = ($quantity * $unitPrice) - $lineDiscount;

            $subtotal += $lineSubtotal;

            $discount += $lineDiscount;

            $vat += $lineSubtotal * ($vatRate / 100);
        }

        return [

            'subtotal' => round($subtotal, 2),

            'discount' => round($discount, 2),

            'vat' => round($vat, 2),

            'grand_total' => round(
                $subtotal + $vat,
                2
            ),

        ];
    }
}