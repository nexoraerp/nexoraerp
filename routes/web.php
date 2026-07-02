<?php


use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CashAccountController;
use App\Http\Controllers\LandingController;
Route::get('/', [LandingController::class, 'index'])
    ->name('landing');
/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])
    ->name('landing');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Customers
    |--------------------------------------------------------------------------
    */

    Route::get('/customers', [CustomerController::class, 'index'])
        ->name('customers.index');

    Route::get('/customers/create', [CustomerController::class, 'create'])
        ->name('customers.create');

    Route::post('/customers', [CustomerController::class, 'store'])
        ->name('customers.store');

    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])
        ->name('customers.edit');

    Route::put('/customers/{customer}', [CustomerController::class, 'update'])
        ->name('customers.update');

    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])
        ->name('customers.destroy');

    Route::get('/customers/{customer}', [CustomerController::class, 'show'])
        ->name('customers.show');
        Route::get(
            'customers/{customer}/open-sales',
            [CustomerController::class, 'openSales']
        )->name('customers.open-sales');

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->name('products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('products.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('products.destroy');

    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->name('products.show');
        /*
|--------------------------------------------------------------------------
| Warehouses
|--------------------------------------------------------------------------
*/

Route::get('/warehouses', [WarehouseController::class, 'index'])
->name('warehouses.index');

Route::get('/warehouses/create', [WarehouseController::class, 'create'])
->name('warehouses.create');

Route::post('/warehouses', [WarehouseController::class, 'store'])
->name('warehouses.store');

Route::get('/warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])
->name('warehouses.edit');

Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update'])
->name('warehouses.update');

Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])
->name('warehouses.destroy');

Route::get('/warehouses/{warehouse}', [WarehouseController::class, 'show'])
->name('warehouses.show');
/*
|--------------------------------------------------------------------------
| Stock Movements
|--------------------------------------------------------------------------
*/

Route::get('/stock-movements', [StockMovementController::class, 'index'])
    ->name('stock-movements.index');

Route::get('/stock-movements/create', [StockMovementController::class, 'create'])
    ->name('stock-movements.create');

Route::post('/stock-movements', [StockMovementController::class, 'store'])
    ->name('stock-movements.store');

Route::get('/stock-movements/{stockMovement}/edit', [StockMovementController::class, 'edit'])
    ->name('stock-movements.edit');

Route::put('/stock-movements/{stockMovement}', [StockMovementController::class, 'update'])
    ->name('stock-movements.update');

Route::delete('/stock-movements/{stockMovement}', [StockMovementController::class, 'destroy'])
    ->name('stock-movements.destroy');

Route::get('/stock-movements/{stockMovement}', [StockMovementController::class, 'show'])
    ->name('stock-movements.show');
    /*
|--------------------------------------------------------------------------
| Sales
|--------------------------------------------------------------------------
*/

Route::get('/sales', [SaleController::class, 'index'])
->name('sales.index');

Route::get('/sales/create', [SaleController::class, 'create'])
->name('sales.create');

Route::post('/sales', [SaleController::class, 'store'])
->name('sales.store');

Route::get('/sales/{sale}', [SaleController::class, 'show'])
->name('sales.show');

Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])
->name('sales.edit');

Route::put('/sales/{sale}', [SaleController::class, 'update'])
->name('sales.update');
Route::resource('payments', PaymentController::class);

Route::patch('/sales/{sale}/cancel', [SaleController::class, 'cancel'])
    ->name('sales.cancel');
    Route::resource('cash-accounts', CashAccountController::class);
});

require __DIR__.'/auth.php';