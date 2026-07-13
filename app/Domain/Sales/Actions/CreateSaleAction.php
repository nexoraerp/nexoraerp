<?php

namespace App\Domain\Sales\Actions;

use App\Models\Sale;

class CreateSaleAction
{
    /**
     * Satış Kaydı Oluştur
     */
    public function execute(
        array $data,
        array $totals
    ): Sale {
        return Sale::create([

            'sale_no'         => $this->generateSaleNo(),

            'customer_id'     => $data['customer_id'],

            'sale_date'       => $data['sale_date'],

            'due_date'        => $data['due_date'],

            'payment_type'    => $data['payment_type'],

            'subtotal'        => $totals['subtotal'],

            'discount'        => $totals['discount'],

            'vat'             => $totals['vat'],

            'grand_total'     => $totals['grand_total'],

            'payment_status'  => 'Unpaid',

            'paid_total'      => 0,

            'remaining_total' => $totals['grand_total'],

            'status'          => 'Completed',

            'notes'           => $data['notes'] ?? null,

            'user_id'         => auth()->user()?->tenantOwnerId() ?? auth()->id(),

        ]);
    }

    /**
     * Satış Numarası Oluştur
     */
    private function generateSaleNo(): string
    {
        $lastSale = Sale::withoutGlobalScopes()
            ->orderByDesc('id')
            ->first();

        if (! $lastSale || empty($lastSale->sale_no)) {
            return 'SAT-000001';
        }

        $lastNumber = (int) str_replace('SAT-', '', $lastSale->sale_no);

        do {
            $lastNumber++;

            $saleNo = 'SAT-' . str_pad(
                $lastNumber,
                6,
                '0',
                STR_PAD_LEFT
            );
        } while (Sale::withoutGlobalScopes()->where('sale_no', $saleNo)->exists());

        return $saleNo;
    }
}
