<?php

namespace App\Services\Reports;

use App\Models\Expense;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfitLossReportService
{
    public function build(Request $request): array
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        $netSales = $this->netSales($startDate, $endDate);
        $salesVat = $this->salesVat($startDate, $endDate);
        $costOfSales = $this->costOfSales($startDate, $endDate);
        $grossProfit = $netSales - $costOfSales;
        $operatingExpenses = $this->operatingExpenses($startDate, $endDate);
        $expenseVat = $this->expenseVat($startDate, $endDate);
        $operatingProfit = $grossProfit - $operatingExpenses;

        return [
            'filters' => [
                'range' => $request->input('range', 'month'),
                'startDate' => $startDate->toDateString(),
                'endDate' => $endDate->toDateString(),
            ],
            'kpis' => [
                'net_sales' => $netSales,
                'cost_of_sales' => $costOfSales,
                'gross_profit' => $grossProfit,
                'gross_margin' => $netSales > 0 ? ($grossProfit / $netSales) * 100 : 0,
                'operating_expenses' => $operatingExpenses,
                'operating_profit' => $operatingProfit,
                'operating_margin' => $netSales > 0 ? ($operatingProfit / $netSales) * 100 : 0,
                'sales_vat' => $salesVat,
                'expense_vat' => $expenseVat,
                'estimated_vat_payable' => $salesVat - $expenseVat,
            ],
            'charts' => [
                'labels' => $this->monthLabels($startDate, $endDate),
                'sales' => $this->monthlySales($startDate, $endDate),
                'expenses' => $this->monthlyExpenses($startDate, $endDate),
                'grossProfit' => $this->monthlyGrossProfit($startDate, $endDate),
                'operatingProfit' => $this->monthlyOperatingProfit($startDate, $endDate),
                'expenseCategoryLabels' => $this->expenseCategoryBreakdown($startDate, $endDate)->pluck('label')->all(),
                'expenseCategoryValues' => $this->expenseCategoryBreakdown($startDate, $endDate)->pluck('value')->all(),
            ],
            'monthlyRows' => $this->monthlyRows($startDate, $endDate),
            'topExpenseCategories' => $this->expenseCategoryBreakdown($startDate, $endDate)->take(5)->values(),
            'aiSummary' => $this->aiSummary($netSales, $operatingExpenses, $operatingProfit),
        ];
    }

    private function resolveDateRange(Request $request): array
    {
        return match ($request->input('range', 'month')) {
            'today' => [today(), today()],
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
            'custom' => [
                Carbon::parse($request->input('startDate', now()->startOfMonth()->toDateString())),
                Carbon::parse($request->input('endDate', now()->endOfMonth()->toDateString())),
            ],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    private function salesQuery(Carbon $startDate, Carbon $endDate)
    {
        return Sale::query()
            ->where('status', 'Completed')
            ->whereDate('sale_date', '>=', $startDate->toDateString())
            ->whereDate('sale_date', '<=', $endDate->toDateString());
    }

    private function saleItemsQuery(Carbon $startDate, Carbon $endDate)
    {
        return SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.status', 'Completed')
            ->whereDate('sales.sale_date', '>=', $startDate->toDateString())
            ->whereDate('sales.sale_date', '<=', $endDate->toDateString());
    }

    private function expensesQuery(Carbon $startDate, Carbon $endDate)
    {
        return Expense::query()
            ->where('status', '!=', 'Cancelled')
            ->whereDate('expense_date', '>=', $startDate->toDateString())
            ->whereDate('expense_date', '<=', $endDate->toDateString());
    }

    private function netSales(Carbon $startDate, Carbon $endDate): float
    {
        return (float) $this->salesQuery($startDate, $endDate)->sum('subtotal');
    }

    private function salesVat(Carbon $startDate, Carbon $endDate): float
    {
        return (float) $this->salesQuery($startDate, $endDate)->sum('vat');
    }

    private function costOfSales(Carbon $startDate, Carbon $endDate): float
    {
        return (float) $this->saleItemsQuery($startDate, $endDate)
            ->selectRaw('COALESCE(SUM(sale_items.purchase_price_snapshot * sale_items.quantity), 0) as total')
            ->value('total');
    }

    private function operatingExpenses(Carbon $startDate, Carbon $endDate): float
    {
        return (float) $this->expensesQuery($startDate, $endDate)
            ->selectRaw('COALESCE(SUM(subtotal - discount), 0) as total')
            ->value('total');
    }

    private function expenseVat(Carbon $startDate, Carbon $endDate): float
    {
        return (float) $this->expensesQuery($startDate, $endDate)->sum('vat');
    }

    private function monthLabels(Carbon $startDate, Carbon $endDate): array
    {
        return collect($this->months($startDate, $endDate))
            ->map(fn (Carbon $date) => $date->format('m.Y'))
            ->all();
    }

    private function monthlySales(Carbon $startDate, Carbon $endDate): array
    {
        return $this->monthlySeries($startDate, $endDate, fn ($month) => $this->netSales($month->copy()->startOfMonth(), $month->copy()->endOfMonth()));
    }

    private function monthlyExpenses(Carbon $startDate, Carbon $endDate): array
    {
        return $this->monthlySeries($startDate, $endDate, fn ($month) => $this->operatingExpenses($month->copy()->startOfMonth(), $month->copy()->endOfMonth()));
    }

    private function monthlyGrossProfit(Carbon $startDate, Carbon $endDate): array
    {
        return $this->monthlySeries($startDate, $endDate, function ($month) {
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            return $this->netSales($start, $end) - $this->costOfSales($start, $end);
        });
    }

    private function monthlyOperatingProfit(Carbon $startDate, Carbon $endDate): array
    {
        return $this->monthlySeries($startDate, $endDate, function ($month) {
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();
            $grossProfit = $this->netSales($start, $end) - $this->costOfSales($start, $end);

            return $grossProfit - $this->operatingExpenses($start, $end);
        });
    }

    private function monthlyRows(Carbon $startDate, Carbon $endDate): array
    {
        return collect($this->months($startDate, $endDate))
            ->map(function (Carbon $month) {
                $start = $month->copy()->startOfMonth();
                $end = $month->copy()->endOfMonth();
                $netSales = $this->netSales($start, $end);
                $cost = $this->costOfSales($start, $end);
                $expenses = $this->operatingExpenses($start, $end);
                $grossProfit = $netSales - $cost;

                return [
                    'month' => $month->format('m.Y'),
                    'net_sales' => $netSales,
                    'cost_of_sales' => $cost,
                    'gross_profit' => $grossProfit,
                    'operating_expenses' => $expenses,
                    'operating_profit' => $grossProfit - $expenses,
                ];
            })
            ->all();
    }

    private function expenseCategoryBreakdown(Carbon $startDate, Carbon $endDate)
    {
        return $this->expensesQuery($startDate, $endDate)
            ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->selectRaw('expense_categories.name as label, COALESCE(SUM(expenses.subtotal - expenses.discount), 0) as value')
            ->groupBy('expense_categories.id', 'expense_categories.name')
            ->orderByDesc('value')
            ->get()
            ->map(fn ($row) => [
                'label' => $row->label,
                'value' => (float) $row->value,
            ]);
    }

    private function monthlySeries(Carbon $startDate, Carbon $endDate, callable $resolver): array
    {
        return collect($this->months($startDate, $endDate))
            ->map(fn (Carbon $month) => round((float) $resolver($month), 2))
            ->all();
    }

    private function months(Carbon $startDate, Carbon $endDate): array
    {
        $months = [];
        $cursor = $startDate->copy()->startOfMonth();

        while ($cursor <= $endDate) {
            $months[] = $cursor->copy();
            $cursor->addMonth();
        }

        return $months;
    }

    private function aiSummary(float $netSales, float $operatingExpenses, float $operatingProfit): string
    {
        if ($netSales <= 0 && $operatingExpenses <= 0) {
            return 'Seçili dönemde kâr/zarar analizi için yeterli satış veya gider verisi bulunmuyor.';
        }

        if ($operatingProfit >= 0) {
            return 'Seçili dönemde faaliyet kârı pozitiftir. Giderlerin satış gelirine oranı düzenli izlenmelidir.';
        }

        return 'Seçili dönemde faaliyet zararı oluşmuştur. En yüksek gider kategorileri ve satış maliyetleri ayrıca incelenmelidir.';
    }
}
