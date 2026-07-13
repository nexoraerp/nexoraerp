<?php

namespace App\Services\Insights;

use App\Models\Product;
use App\Models\Sale;

class RiskAnalysisService
{
    public function risks(): array
    {
        return collect()
            ->merge($this->overdueSalesRisks())
            ->merge($this->stockRisks())
            ->values()
            ->all();
    }

    private function overdueSalesRisks()
    {
        $overdue = Sale::query()
            ->with('customer')
            ->where('status', 'Completed')
            ->where('remaining_total', '>', 0)
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', today())
            ->orderByDesc('remaining_total')
            ->take(5)
            ->get();

        return $overdue->map(fn (Sale $sale) => [
            'level' => ((float) $sale->remaining_total >= 10000) ? 'high' : 'medium',
            'title' => 'Geciken tahsilat riski',
            'description' => ($sale->customer?->name ?? 'Cari') . " için {$sale->sale_no} vadesi geçti.",
            'metric' => number_format((float) $sale->remaining_total, 2, ',', '.') . ' TL',
            'action' => 'Tahsilat planı oluşturun',
        ]);
    }

    private function stockRisks()
    {
        $products = Product::query()
            ->orderBy('name')
            ->get()
            ->filter(fn (Product $product) => (float) $product->current_stock <= (float) ($product->min_stock ?? 0))
            ->take(5);

        return $products->map(function (Product $product) {
            $stock = (float) $product->current_stock;

            return [
                'level' => $stock < 0 ? 'high' : 'medium',
                'title' => $stock < 0 ? 'Negatif stok riski' : 'Kritik stok seviyesi',
                'description' => "{$product->code} - {$product->name} stok seviyesi minimum eşiğe yakın.",
                'metric' => number_format($stock, 2, ',', '.') . ' ' . ($product->unit ?? ''),
                'action' => 'Stok hareketlerini kontrol edin',
            ];
        });
    }
}
