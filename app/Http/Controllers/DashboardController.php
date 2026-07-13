<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\StockMovement;
use App\Models\Sale;
use App\Models\SaleItem;
use Inertia\Inertia;
use App\Models\Payment;
use App\Models\CashAccount;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $tenantUserId = auth()->user()?->tenantOwnerId();

        $completedSaleItems = SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.status', 'Completed')
            ->when($tenantUserId, fn ($query) => $query->where('sales.user_id', $tenantUserId));

        $monthCompletedSaleItems = (clone $completedSaleItems)
            ->whereMonth('sales.sale_date', now()->month)
            ->whereYear('sales.sale_date', now()->year);

        $monthVatTotal = (clone $monthCompletedSaleItems)
            ->selectRaw('COALESCE(SUM(sale_items.line_total * sale_items.vat / 100), 0) as total')
            ->value('total');

        $monthGrossProfit = (clone $monthCompletedSaleItems)
            ->selectRaw('COALESCE(SUM((sale_items.unit_price - products.purchase_price) * sale_items.quantity - sale_items.discount), 0) as total')
            ->value('total');

        $monthNetRevenue = Sale::whereMonth('sale_date', now()->month)
            ->whereYear('sale_date', now()->year)
            ->where('status', 'Completed')
            ->sum('subtotal');

        $monthRevenue = Sale::whereMonth('sale_date', now()->month)
            ->whereYear('sale_date', now()->year)
            ->where('status', 'Completed')
            ->sum('grand_total');

        $monthProfitMargin = $monthNetRevenue > 0
            ? ($monthGrossProfit / $monthNetRevenue) * 100
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Kritik Stoklar
        |--------------------------------------------------------------------------
        */

        $criticalProducts = Product::all()
            ->filter(function ($product) {
                return $product->currentStock() <= $product->min_stock;
            })
            ->take(5)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Son Stok Hareketleri
        |--------------------------------------------------------------------------
        */

        $lastMovements = StockMovement::with([
            'product',
            'warehouse',
            'user',
        ])
        ->latest()
        ->take(5)
        ->get();
/*
|--------------------------------------------------------------------------
| Son 30 Gün Satış Grafiği
|--------------------------------------------------------------------------
*/

$salesChart = collect();

for ($i = 29; $i >= 0; $i--) {

    $date = Carbon::today()->subDays($i);

    $salesChart->push([

        'date' => $date->format('d.m'),
    
        'sales' => Sale::whereDate('sale_date', $date)
            ->where('status', 'Completed')
            ->sum('grand_total'),
    
        'collections' => Payment::whereDate('payment_date', $date)
            ->sum('amount'),
    
    ]);

}
        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return Inertia::render('Dashboard/Index', [

            /*
            |--------------------------------------------------------------------------
            | Genel İstatistikler
            |--------------------------------------------------------------------------
            */

            'customerCount' => Customer::count(),

            'productCount' => Product::count(),

            'warehouseCount' => Warehouse::count(),

            'movementCount' => StockMovement::count(),

            /*
            |--------------------------------------------------------------------------
            | Satış İstatistikleri
            |--------------------------------------------------------------------------
            */

            'saleCount' => Sale::count(),

            'completedSales' => Sale::where('status', 'Completed')->count(),

            'cancelledSales' => Sale::where('status', 'Cancelled')->count(),

            'todaySales' => Sale::whereDate('sale_date', today())
                ->where('status', 'Completed')
                ->count(),

            'totalRevenue' => Sale::where('status', 'Completed')
                ->sum('grand_total'),

            'todayRevenue' => Sale::whereDate('sale_date', today())
                ->where('status', 'Completed')
                ->sum('grand_total'),

            'monthRevenue' => $monthRevenue,

            'averageSale' => Sale::where('status', 'Completed')
                ->avg('grand_total'),

            'monthVatTotal' => $monthVatTotal,

            'monthGrossProfit' => $monthGrossProfit,

            'monthProfitMargin' => $monthProfitMargin,

            /*
            |--------------------------------------------------------------------------
            | En Çok Satılan Ürünler
            |--------------------------------------------------------------------------
            */

            'topProducts' => SaleItem::selectRaw('sale_items.product_id, SUM(sale_items.quantity) as total_quantity')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->where('sales.status', 'Completed')
                ->when($tenantUserId, fn ($query) => $query->where('sales.user_id', $tenantUserId))
                ->with('product')
                ->groupBy('sale_items.product_id')
                ->orderByDesc('total_quantity')
                ->take(5)
                ->get(),

            /*
            |--------------------------------------------------------------------------
            | Son Satışlar
            |--------------------------------------------------------------------------
            */

            'lastSales' => Sale::with('customer')
                ->latest()
                ->take(5)
                ->get(),

            /*
            |--------------------------------------------------------------------------
            | Dashboard Widgetları
            |--------------------------------------------------------------------------
            */

            'criticalProducts' => $criticalProducts,

            'lastMovements' => $lastMovements,
            /*
|--------------------------------------------------------------------------
| Finans
|--------------------------------------------------------------------------
*/

'todayCollection' => Payment::whereDate('payment_date', today())
->sum('amount'),

'totalCash' => CashAccount::all()
->sum('balance'),

'totalReceivable' => Sale::where('status', 'Completed')
->sum('remaining_total'),

'openInvoiceCount' => Sale::where('status', 'Completed')
->where('remaining_total', '>', 0)
->count(),

'lastPayments' => Payment::with('customer')
->latest()
->take(5)
->get(),
'salesChart' => $salesChart,

        ]);
    }
}
