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

            'monthRevenue' => Sale::whereMonth('sale_date', now()->month)
                ->whereYear('sale_date', now()->year)
                ->where('status', 'Completed')
                ->sum('grand_total'),

            'averageSale' => Sale::where('status', 'Completed')
                ->avg('grand_total'),

            /*
            |--------------------------------------------------------------------------
            | En Çok Satılan Ürünler
            |--------------------------------------------------------------------------
            */

            'topProducts' => SaleItem::selectRaw('product_id, SUM(quantity) as total_quantity')
                ->with('product')
                ->groupBy('product_id')
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