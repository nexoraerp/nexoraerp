<?php

namespace App\Services\AI;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SupportTicket;
use App\Services\Insights\RiskAnalysisService;

class NexoraBriefingService
{
    public function __construct(
        protected DueReminderService $dueReminder,
        protected RiskAnalysisService $riskAnalysis
    ) {
    }

    public function dashboardBriefing(?string $userName = null): array
    {
        $due = $this->dueReminder->reminders();
        $risks = $this->riskAnalysis->risks();
        $criticalProduct = $this->criticalProduct();
        $trend = $this->monthlySalesTrend();

        $highlights = [];

        if (($due['overdue_count'] ?? 0) > 0) {
            $highlights[] = [
                'level' => 'danger',
                'text' => "{$due['overdue_count']} tahsilat gecikti.",
            ];
        }

        if ($criticalProduct !== null) {
            $highlights[] = [
                'level' => 'warning',
                'text' => "{$criticalProduct->name} kritik stokta.",
            ];
        }

        $highlights[] = [
            'level' => $trend['percentage'] >= 0 ? 'success' : 'danger',
            'text' => "Bu ay satışlar %{$trend['percentage']} " . ($trend['percentage'] >= 0 ? 'arttı.' : 'azaldı.'),
        ];

        if (count($highlights) === 1 && count($risks) === 0) {
            $highlights[] = [
                'level' => 'success',
                'text' => 'Bugün kritik risk görünmüyor.',
            ];
        }

        return [
            'greeting' => $this->greeting($userName),
            'headline' => 'Bugün dikkat etmeniz gereken ' . count($highlights) . ' konu var.',
            'highlights' => array_slice($highlights, 0, 3),
            'due_reminders' => $due,
            'risks' => $risks,
            'detail_route' => route('risk-analysis.index'),
        ];
    }

    public function contextText(?string $userName = null): string
    {
        $briefing = $this->dashboardBriefing($userName);

        $highlights = collect($briefing['highlights'])
            ->map(fn (array $item) => "- {$item['text']}")
            ->implode("\n");

        return collect([
            $briefing['greeting'],
            $briefing['headline'],
            $highlights,
            '',
            'ERP canlı veri özeti:',
            ...$this->businessContextLines(),
        ])->filter(fn ($line) => $line !== null)->implode("\n");
    }

    private function businessContextLines(): array
    {
        $completedSales = Sale::query()->where('status', 'Completed');
        $totalSales = (float) (clone $completedSales)->sum('subtotal');
        $totalVat = (float) (clone $completedSales)->sum('vat');
        $openReceivables = (float) Sale::query()
            ->where('status', 'Completed')
            ->where('remaining_total', '>', 0)
            ->sum('remaining_total');
        $totalPayments = (float) Payment::query()->sum('amount');
        $customerCount = Customer::query()->count();
        $productCount = Product::query()->count();
        $criticalProducts = Product::query()
            ->orderBy('name')
            ->get()
            ->filter(fn (Product $product) => (float) $product->current_stock <= (float) ($product->min_stock ?? 0))
            ->take(5)
            ->map(fn (Product $product) => "{$product->code} - {$product->name} ({$product->current_stock} {$product->unit})")
            ->values()
            ->all();
        $topReceivables = Customer::query()
            ->with('movements')
            ->orderBy('name')
            ->get()
            ->map(fn (Customer $customer) => [
                'name' => $customer->name,
                'balance' => (float) $customer->balance,
            ])
            ->filter(fn (array $customer) => $customer['balance'] > 0)
            ->sortByDesc('balance')
            ->take(5)
            ->map(fn (array $customer) => $customer['name'] . ': ' . $this->money($customer['balance']))
            ->values()
            ->all();
        $expenseTotal = class_exists(Expense::class)
            ? (float) Expense::query()->where('status', '!=', 'Cancelled')->sum('grand_total')
            : 0.0;
        $openSupportTickets = class_exists(SupportTicket::class)
            ? SupportTicket::query()->whereIn('status', ['open', 'in_progress'])->count()
            : 0;

        return [
            '- Toplam cari sayısı: ' . $customerCount,
            '- Toplam ürün sayısı: ' . $productCount,
            '- Tamamlanan net satış toplamı: ' . $this->money($totalSales),
            '- Satış KDV toplamı: ' . $this->money($totalVat) . ' (kâr sayılmaz)',
            '- Toplam tahsilat: ' . $this->money($totalPayments),
            '- Açık alacak / takip edilecek bakiye: ' . $this->money($openReceivables),
            '- Faaliyet giderleri toplamı: ' . $this->money($expenseTotal),
            '- Açık destek/çözüm öneri talebi: ' . $openSupportTickets,
            '- En yüksek cari bakiyeler: ' . (count($topReceivables) ? implode('; ', $topReceivables) : 'yok'),
            '- Kritik stok ürünleri: ' . (count($criticalProducts) ? implode('; ', $criticalProducts) : 'yok'),
        ];
    }

    private function money(float $value): string
    {
        return '₺' . number_format($value, 2, ',', '.');
    }

    private function criticalProduct(): ?Product
    {
        return Product::query()
            ->orderBy('name')
            ->get()
            ->first(fn (Product $product) => (float) $product->current_stock <= (float) ($product->min_stock ?? 0));
    }

    private function monthlySalesTrend(): array
    {
        $current = Sale::query()
            ->where('status', 'Completed')
            ->whereMonth('sale_date', now()->month)
            ->whereYear('sale_date', now()->year)
            ->sum('subtotal');

        $previousMonth = now()->subMonth();

        $previous = Sale::query()
            ->where('status', 'Completed')
            ->whereMonth('sale_date', $previousMonth->month)
            ->whereYear('sale_date', $previousMonth->year)
            ->sum('subtotal');

        if ((float) $previous <= 0) {
            return ['percentage' => (float) $current > 0 ? 100 : 0];
        }

        return [
            'percentage' => round(((float) $current - (float) $previous) / (float) $previous * 100),
        ];
    }

    private function greeting(?string $userName): string
    {
        $hour = now()->hour;

        $prefix = match (true) {
            $hour < 12 => 'Günaydın',
            $hour < 18 => 'İyi günler',
            default => 'İyi akşamlar',
        };

        return trim($prefix . ' ' . ($userName ?: ''));
    }
}
