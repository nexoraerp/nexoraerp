<?php

namespace App\Services\Reports;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportMetricsService
{
    public function build(Request $request): array
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        $filters = [
            'range' => $request->input('range', 'month'),
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ];

        $netSales = $this->netSales($startDate, $endDate);
        $totalVat = $this->totalVat($startDate, $endDate);
        $grossProfit = $this->grossProfit($startDate, $endDate);
        $profitMargin = $netSales > 0 ? ($grossProfit / $netSales) * 100 : 0;
        $totalCollection = $this->totalCollection($request, $startDate, $endDate);
        $orderCount = $this->orderCount($request, $startDate, $endDate);
        $averageOrder = $orderCount > 0 ? $netSales / $orderCount : 0;
        $topProduct = $this->topProduct($startDate, $endDate);

        return [
            'filters' => $filters,
            'kpis' => [
                [
                    'label' => 'Toplam Satış',
                    'value' => $this->money($netSales),
                    'hint' => 'İskonto sonrası KDV hariç net satış',
                    'tone' => 'blue',
                ],
                [
                    'label' => 'Toplam Tahsilat',
                    'value' => $this->money($totalCollection),
                    'hint' => 'Seçili dönemde alınan tahsilat',
                    'tone' => 'emerald',
                ],
                [
                    'label' => 'Toplam KDV',
                    'value' => $this->money($totalVat),
                    'hint' => 'KDV gelir veya kar olarak sayılmaz',
                    'tone' => 'cyan',
                ],
                [
                    'label' => 'Tahmini Brüt Kar',
                    'value' => $this->money($grossProfit),
                    'hint' => 'Net satıştan ürün maliyeti düşülerek',
                    'tone' => 'green',
                ],
                [
                    'label' => 'Kar Marjı',
                    'value' => '%' . $this->number($profitMargin),
                    'hint' => 'Brüt kar / KDV hariç net satış',
                    'tone' => 'purple',
                ],
                [
                    'label' => 'Toplam Sipariş',
                    'value' => (string) $orderCount,
                    'hint' => 'Tamamlanan satış belgesi adedi',
                    'tone' => 'indigo',
                ],
                [
                    'label' => 'Ortalama Sipariş',
                    'value' => $this->money($averageOrder),
                    'hint' => 'KDV hariç belge başı ortalama',
                    'tone' => 'amber',
                ],
                [
                    'label' => 'En Çok Satılan Ürün',
                    'value' => $topProduct['name'],
                    'hint' => $topProduct['hint'],
                    'tone' => 'slate',
                ],
            ],
            'charts' => [
                'labels' => $this->dateLabels($startDate, $endDate),
                'sales' => $this->salesSeries($startDate, $endDate),
                'profit' => $this->profitSeries($startDate, $endDate),
                'categoryLabels' => $this->categorySales($startDate, $endDate)->pluck('label')->all(),
                'categorySales' => $this->categorySales($startDate, $endDate)->pluck('value')->all(),
                'paymentLabels' => $this->paymentDistribution($request, $startDate, $endDate)->pluck('label')->all(),
                'paymentValues' => $this->paymentDistribution($request, $startDate, $endDate)->pluck('value')->all(),
            ],
            'tableRows' => $this->tableRows($startDate, $endDate),
            'insights' => $this->insights($startDate, $endDate, $netSales, $grossProfit),
            'reportCards' => $this->reportCards($request, $startDate, $endDate),
        ];
    }

    private function resolveDateRange(Request $request): array
    {
        $range = $request->string('range', 'month')->toString();

        return match ($range) {
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

    private function baseItemsQuery(Carbon $startDate, Carbon $endDate)
    {
        return SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->leftJoin('products', function ($join) {
                $join->on('sale_items.product_id', '=', 'products.id')
                    ->on('products.user_id', '=', 'sales.user_id');
            })
            ->where('sales.status', 'Completed')
            ->when(auth()->check(), fn ($query) => $query->where('sales.user_id', auth()->id()))
            ->whereDate('sales.sale_date', '>=', $startDate->toDateString())
            ->whereDate('sales.sale_date', '<=', $endDate->toDateString());
    }

    private function filteredItemsQuery(Request $request, Carbon $startDate, Carbon $endDate)
    {
        return $this->baseItemsQuery($startDate, $endDate)
            ->when($request->input('warehouse', 'all') !== 'all', fn ($query) => $query->where('sale_items.warehouse_id', $request->input('warehouse')))
            ->when($request->input('customer', 'all') !== 'all', fn ($query) => $query->where('sales.customer_id', $request->input('customer')))
            ->when($request->input('product', 'all') !== 'all', fn ($query) => $query->where('sale_items.product_id', $request->input('product')))
            ->when($request->input('category', 'all') !== 'all', fn ($query) => $query->where('products.category', $request->input('category')))
            ->when($request->input('paymentType', 'all') !== 'all', fn ($query) => $query->where('sales.payment_type', $request->input('paymentType')));
    }

    private function filteredSalesQuery(Request $request, Carbon $startDate, Carbon $endDate)
    {
        return Sale::query()
            ->where('status', 'Completed')
            ->whereDate('sale_date', '>=', $startDate->toDateString())
            ->whereDate('sale_date', '<=', $endDate->toDateString())
            ->when($request->input('customer', 'all') !== 'all', fn ($query) => $query->where('customer_id', $request->input('customer')))
            ->when($request->input('paymentType', 'all') !== 'all', fn ($query) => $query->where('payment_type', $request->input('paymentType')))
            ->when($request->input('warehouse', 'all') !== 'all', fn ($query) => $query->whereHas('items', fn ($items) => $items->where('warehouse_id', $request->input('warehouse'))))
            ->when($request->input('product', 'all') !== 'all', fn ($query) => $query->whereHas('items', fn ($items) => $items->where('product_id', $request->input('product'))))
            ->when($request->input('category', 'all') !== 'all', fn ($query) => $query->whereHas('items.product', fn ($product) => $product->where('category', $request->input('category'))));
    }

    private function netSales(Carbon $startDate, Carbon $endDate): float
    {
        return (float) $this->filteredItemsQuery(request(), $startDate, $endDate)
            ->selectRaw('COALESCE(SUM(sale_items.line_total), 0) as total')
            ->value('total');
    }

    private function totalVat(Carbon $startDate, Carbon $endDate): float
    {
        return (float) $this->filteredItemsQuery(request(), $startDate, $endDate)
            ->selectRaw('COALESCE(SUM(sale_items.line_total * sale_items.vat / 100), 0) as total')
            ->value('total');
    }

    private function grossProfit(Carbon $startDate, Carbon $endDate): float
    {
        return (float) $this->filteredItemsQuery(request(), $startDate, $endDate)
            ->selectRaw('COALESCE(SUM(sale_items.line_total - (COALESCE(products.purchase_price, 0) * sale_items.quantity)), 0) as total')
            ->value('total');
    }

    private function totalCollection(Request $request, Carbon $startDate, Carbon $endDate): float
    {
        return (float) Payment::query()
            ->whereDate('payment_date', '>=', $startDate->toDateString())
            ->whereDate('payment_date', '<=', $endDate->toDateString())
            ->when($request->input('customer', 'all') !== 'all', fn ($query) => $query->where('customer_id', $request->input('customer')))
            ->when($request->input('paymentType', 'all') !== 'all', fn ($query) => $query->where('payment_method', $request->input('paymentType')))
            ->sum('amount');
    }

    private function orderCount(Request $request, Carbon $startDate, Carbon $endDate): int
    {
        return $this->filteredSalesQuery($request, $startDate, $endDate)->count();
    }

    private function topProduct(Carbon $startDate, Carbon $endDate): array
    {
        $row = $this->filteredItemsQuery(request(), $startDate, $endDate)
            ->selectRaw('sale_items.product_id, products.name, COALESCE(SUM(sale_items.quantity), 0) as total_quantity')
            ->groupBy('sale_items.product_id', 'products.name')
            ->orderByDesc('total_quantity')
            ->first();

        if (! $row) {
            return [
                'name' => 'Veri yok',
                'hint' => 'Seçili dönemde satış bulunmuyor',
            ];
        }

        return [
            'name' => $row->name ?? 'Ürün bulunamadı',
            'hint' => $this->number($row->total_quantity) . ' adet',
        ];
    }

    private function salesSeries(Carbon $startDate, Carbon $endDate): array
    {
        $rows = $this->filteredItemsQuery(request(), $startDate, $endDate)
            ->selectRaw('DATE(sales.sale_date) as date, COALESCE(SUM(sale_items.line_total), 0) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        return $this->fillDateSeries($startDate, $endDate, $rows);
    }

    private function profitSeries(Carbon $startDate, Carbon $endDate): array
    {
        $rows = $this->filteredItemsQuery(request(), $startDate, $endDate)
            ->selectRaw('DATE(sales.sale_date) as date, COALESCE(SUM(sale_items.line_total - (COALESCE(products.purchase_price, 0) * sale_items.quantity)), 0) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        return $this->fillDateSeries($startDate, $endDate, $rows);
    }

    private function categorySales(Carbon $startDate, Carbon $endDate)
    {
        return $this->filteredItemsQuery(request(), $startDate, $endDate)
            ->selectRaw("COALESCE(NULLIF(products.category, ''), 'Kategorisiz') as label, COALESCE(SUM(sale_items.line_total), 0) as value")
            ->groupBy('label')
            ->orderByDesc('value')
            ->take(8)
            ->get()
            ->map(fn ($row) => [
                'label' => $row->label,
                'value' => (float) $row->value,
            ]);
    }

    private function paymentDistribution(Request $request, Carbon $startDate, Carbon $endDate)
    {
        return $this->filteredSalesQuery($request, $startDate, $endDate)
            ->selectRaw("COALESCE(payment_type, 'Belirtilmedi') as label, COUNT(*) as value")
            ->groupBy('label')
            ->orderByDesc('value')
            ->get()
            ->map(fn ($row) => [
                'label' => $this->paymentLabel($row->label),
                'value' => (int) $row->value,
            ]);
    }

    private function tableRows(Carbon $startDate, Carbon $endDate): array
    {
        return $this->filteredSalesQuery(request(), $startDate, $endDate)
            ->with(['customer', 'items.product'])
            ->latest('sale_date')
            ->take(100)
            ->get()
            ->map(function (Sale $sale) {
                $netSales = (float) $sale->items->sum('line_total');
                $vat = (float) $sale->items->sum(fn ($item) => ((float) $item->line_total) * ((float) $item->vat / 100));
                $cost = (float) $sale->items->sum(fn ($item) => ((float) ($item->product?->purchase_price ?? 0)) * ((float) $item->quantity));

                return [
                    'id' => $sale->id,
                    'date' => $sale->sale_date?->format('d.m.Y') ?? '-',
                    'no' => $sale->sale_no,
                    'customer' => $sale->customer?->name ?? '-',
                    'sales' => $netSales,
                    'vat' => $vat,
                    'profit' => $netSales - $cost,
                    'payment' => $this->paymentLabel($sale->payment_type),
                    'status' => $this->saleStatusLabel($sale),
                    'show_url' => route('sales.show', $sale),
                ];
            })
            ->values()
            ->all();
    }

    private function insights(Carbon $startDate, Carbon $endDate, float $netSales, float $grossProfit): array
    {
        $customer = $this->filteredItemsQuery(request(), $startDate, $endDate)
            ->join('customers', function ($join) {
                $join->on('sales.customer_id', '=', 'customers.id')
                    ->on('customers.user_id', '=', 'sales.user_id');
            })
            ->selectRaw('customers.name, COALESCE(SUM(sale_items.line_total - (COALESCE(products.purchase_price, 0) * sale_items.quantity)), 0) as profit')
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('profit')
            ->first();

        $product = $this->topProduct($startDate, $endDate);

        $category = $this->filteredItemsQuery(request(), $startDate, $endDate)
            ->selectRaw("COALESCE(NULLIF(products.category, ''), 'Kategorisiz') as category, COALESCE(SUM(sale_items.line_total - (COALESCE(products.purchase_price, 0) * sale_items.quantity)), 0) as profit")
            ->groupBy('category')
            ->orderByDesc('profit')
            ->first();

        $day = $this->filteredItemsQuery(request(), $startDate, $endDate)
            ->selectRaw('DATE(sales.sale_date) as sale_day, COALESCE(SUM(sale_items.line_total), 0) as total')
            ->groupBy('sale_day')
            ->orderByDesc('total')
            ->first();

        $previousStart = $startDate->copy()->subDays($startDate->diffInDays($endDate) + 1);
        $previousEnd = $startDate->copy()->subDay();
        $previousSales = $this->filteredItemsQuery(request(), $previousStart, $previousEnd)
            ->selectRaw('COALESCE(SUM(sale_items.line_total), 0) as total')
            ->value('total');
        $trend = ((float) $previousSales) > 0 ? (($netSales - (float) $previousSales) / (float) $previousSales) * 100 : 0;

        return [
            [
                'label' => 'En Çok Kazandıran Müşteri',
                'value' => $customer?->name ?? 'Veri yok',
                'detail' => $customer ? $this->money($customer->profit) . ' brüt kar' : 'Seçili dönemde satış bulunmuyor',
            ],
            [
                'label' => 'En Çok Satılan Ürün',
                'value' => $product['name'],
                'detail' => $product['hint'],
            ],
            [
                'label' => 'En Karlı Kategori',
                'value' => $category?->category ?? 'Veri yok',
                'detail' => $category ? $this->money($category->profit) . ' brüt kar' : 'Kategori verisi bulunmuyor',
            ],
            [
                'label' => 'En Yoğun Satış Günü',
                'value' => $day ? Carbon::parse($day->sale_day)->translatedFormat('d F l') : 'Veri yok',
                'detail' => $day ? $this->money($day->total) . ' net satış' : 'Seçili dönemde satış bulunmuyor',
            ],
            [
                'label' => 'Dönem Kar Marjı',
                'value' => '%' . $this->number($netSales > 0 ? ($grossProfit / $netSales) * 100 : 0),
                'detail' => 'KDV hariç net satışa göre',
            ],
            [
                'label' => 'Önceki Dönem Trendi',
                'value' => '%' . $this->number($trend),
                'detail' => $trend >= 0 ? 'Satışlar yükselişte' : 'Satışlar önceki döneme göre düşük',
            ],
        ];
    }

    private function reportCards(Request $request, Carbon $startDate, Carbon $endDate): array
    {
        $salesCount = $this->filteredSalesQuery($request, $startDate, $endDate)->count();
        $customersCount = Customer::query()->count();
        $stockMovementsCount = StockMovement::query()
            ->whereDate('created_at', '>=', $startDate->toDateString())
            ->whereDate('created_at', '<=', $endDate->toDateString())
            ->count();
        $paymentsCount = Payment::query()
            ->whereDate('payment_date', '>=', $startDate->toDateString())
            ->whereDate('payment_date', '<=', $endDate->toDateString())
            ->count();
        $productsCount = Product::query()->count();
        $warehousesCount = Warehouse::query()->count();

        return [
            ['title' => 'Risk Analizi', 'description' => 'Vade, stok ve işlem risklerini gerçek verilerle izleyin.', 'icon' => 'Activity', 'route' => 'risk-analysis.index', 'metric' => 'Canlı'],
            ['title' => 'Satış Raporu', 'description' => 'Dönemsel tamamlanan satış belgeleri.', 'icon' => 'ReceiptText', 'metric' => $salesCount . ' belge'],
            ['title' => 'Cari Raporu', 'description' => 'Hesabınızdaki aktif cari kayıtları.', 'icon' => 'Users', 'metric' => $customersCount . ' cari'],
            ['title' => 'Stok Hareketleri', 'description' => 'Seçili dönemde oluşan stok hareketleri.', 'icon' => 'Boxes', 'metric' => $stockMovementsCount . ' hareket'],
            ['title' => 'Kasa Hareketleri', 'description' => 'Seçili dönemde alınan tahsilatlar.', 'icon' => 'Wallet', 'metric' => $paymentsCount . ' tahsilat'],
            ['title' => 'Ürün Karlılık Analizi', 'description' => 'Satış ve maliyet verisine göre brüt kar.', 'icon' => 'TrendingUp', 'metric' => $this->money($this->grossProfit($startDate, $endDate))],
            ['title' => 'En Çok Satan Ürünler', 'description' => 'Adet bazlı ürün performansı.', 'icon' => 'PackageCheck', 'metric' => $this->topProduct($startDate, $endDate)['name']],
            ['title' => 'En Karlı Ürünler', 'description' => 'Brüt kara göre ürün analizi.', 'icon' => 'BadgePercent', 'metric' => $this->money($this->grossProfit($startDate, $endDate))],
            ['title' => 'Depo Analizi', 'description' => 'Kullanıcı hesabındaki depo kapsamı.', 'icon' => 'Warehouse', 'metric' => $warehousesCount . ' depo'],
            ['title' => 'Kategori Analizi', 'description' => 'Kategori bazlı satış dağılımı.', 'icon' => 'Layers3', 'metric' => $this->categorySales($startDate, $endDate)->count() . ' kategori'],
            ['title' => 'Tahsilat Analizi', 'description' => 'Seçili dönemde alınan tahsilat toplamı.', 'icon' => 'Banknote', 'metric' => $this->money($this->totalCollection($request, $startDate, $endDate))],
        ];
    }

    private function dateLabels(Carbon $startDate, Carbon $endDate): array
    {
        $labels = [];
        $cursor = $startDate->copy();

        while ($cursor <= $endDate) {
            $labels[] = $cursor->format('d.m');
            $cursor->addDay();
        }

        return $labels;
    }

    private function fillDateSeries(Carbon $startDate, Carbon $endDate, $rows): array
    {
        $series = [];
        $cursor = $startDate->copy();

        while ($cursor <= $endDate) {
            $series[] = (float) ($rows[$cursor->toDateString()] ?? 0);
            $cursor->addDay();
        }

        return $series;
    }

    private function money(float|int|string $value): string
    {
        return '₺' . number_format((float) $value, 2, ',', '.');
    }

    private function number(float|int|string $value): string
    {
        return number_format((float) $value, 2, ',', '.');
    }

    private function paymentLabel(?string $value): string
    {
        return [
            'Cash' => 'Nakit',
            'Credit' => 'Vadeli',
            'Card' => 'Kart',
            'Bank' => 'Havale / EFT',
            'Mixed' => 'Karma',
        ][$value] ?? ($value ?: 'Belirtilmedi');
    }

    private function saleStatusLabel(Sale $sale): string
    {
        if ((float) $sale->remaining_total <= 0) {
            return 'Tamamlandı';
        }

        if ((float) $sale->paid_total > 0) {
            return 'Kısmi Tahsilat';
        }

        return 'Açık';
    }
}
