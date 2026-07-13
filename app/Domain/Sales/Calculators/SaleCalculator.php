<?php

namespace App\Domain\Sales\Calculators;

use App\Domain\Kernel\Calculator;

class SaleCalculator extends Calculator
{
    public function calculate(array $data): array
    {
        $subtotal = 0;
        $discount = 0;
        $vat = 0;

        foreach ($data['items'] as &$item) {

            $quantity = (float) ($item['quantity'] ?? 0);

            $unitPrice = (float) ($item['unit_price'] ?? 0);

            $lineDiscount = (float) ($item['discount'] ?? 0);

            $vatRate = (float) ($item['vat'] ?? 0);

            $lineSubtotal = ($quantity * $unitPrice) - $lineDiscount;

            $lineVat = $lineSubtotal * ($vatRate / 100);

            $item['line_total'] = round(
                $lineSubtotal + $lineVat,
                2
            );

            $subtotal += $lineSubtotal;

            $discount += $lineDiscount;

            $vat += $lineVat;
        }

        unset($item);

        $data['items'] = $data['items'];

        $data['totals'] = [

            'subtotal' => round($subtotal, 2),

            'discount' => round($discount, 2),

            'vat' => round($vat, 2),

            'grand_total' => round(
                $subtotal + $vat,
                2
            ),

        ];

        return $data;
    }
}