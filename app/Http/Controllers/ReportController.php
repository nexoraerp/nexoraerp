<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\Reports\ReportMetricsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function __construct(
        protected ReportMetricsService $reportMetrics
    ) {
    }

    public function index(Request $request)
    {
        $report = $this->reportMetrics->build($request);

        return Inertia::render('Reports/Index', [
            'filters' => [
                ...$report['filters'],
                'branch' => $request->input('branch', 'all'),
                'warehouse' => $request->input('warehouse', 'all'),
                'customer' => $request->input('customer', 'all'),
                'product' => $request->input('product', 'all'),
                'category' => $request->input('category', 'all'),
                'user' => $request->input('user', 'all'),
                'paymentType' => $request->input('paymentType', 'all'),
            ],
            'filterOptions' => $this->filterOptions(),
            'kpis' => $report['kpis'],
            'charts' => $report['charts'],
            'tableRows' => $report['tableRows'],
            'insights' => $report['insights'],
            'reportCards' => $report['reportCards'],
        ]);
    }

    private function filterOptions(): array
    {
        return [
            'branches' => [
                ['value' => 'all', 'label' => 'Tüm Şubeler'],
                ['value' => 'center', 'label' => 'Merkez Şube'],
            ],
            'warehouses' => $this->optionList(Warehouse::orderBy('name')->get(), 'name'),
            'customers' => $this->optionList(Customer::orderBy('name')->get(), 'name'),
            'products' => $this->optionList(Product::orderBy('name')->get(), 'name'),
            'categories' => collect([['value' => 'all', 'label' => 'Tüm Kategoriler']])
                ->merge(Product::query()
                    ->whereNotNull('category')
                    ->where('category', '!=', '')
                    ->distinct()
                    ->orderBy('category')
                    ->pluck('category')
                    ->map(fn ($category) => ['value' => $category, 'label' => $category]))
                ->values(),
            'users' => $this->optionList(collect([auth()->user()])->filter(), 'name'),
            'paymentTypes' => [
                ['value' => 'all', 'label' => 'Tüm Ödemeler'],
                ['value' => 'Cash', 'label' => 'Nakit'],
                ['value' => 'Credit', 'label' => 'Vadeli'],
                ['value' => 'Card', 'label' => 'Kart'],
                ['value' => 'Bank', 'label' => 'Havale / EFT'],
                ['value' => 'Mixed', 'label' => 'Karma'],
            ],
        ];
    }

    private function optionList($records, string $labelColumn)
    {
        return collect([['value' => 'all', 'label' => 'Tümü']])
            ->merge($records->map(fn ($record) => [
                'value' => $record->id,
                'label' => $record->{$labelColumn},
            ]))
            ->values();
    }
}
