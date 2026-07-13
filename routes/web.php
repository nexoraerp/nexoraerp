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
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CashAccountController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RiskAnalysisController;
use App\Http\Controllers\Admin\ProgressController;
use App\Http\Controllers\AI\AIChatController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfitLossController;
use App\Http\Controllers\SupportTicketController;
use OpenAI\Laravel\Facades\OpenAI;
Route::get('/', [LandingController::class, 'index'])
    ->name('landing');
Route::get('/ozellikler', [LandingController::class, 'features'])
    ->name('landing.features');
Route::get('/moduller', [LandingController::class, 'modules'])
    ->name('landing.modules');
Route::get('/cozumler', [LandingController::class, 'solutions'])
    ->name('landing.solutions');
Route::get('/fiyatlar', [LandingController::class, 'pricing'])
    ->name('landing.pricing');
Route::get('/hakkimizda', [LandingController::class, 'about'])
    ->name('landing.about');
Route::get('/blog', [LandingController::class, 'blog'])
    ->name('landing.blog');
Route::get('/iletisim', [LandingController::class, 'contact'])
    ->name('landing.contact');
Route::get('/sss', [LandingController::class, 'faq'])
    ->name('landing.faq');
Route::get('/dokumantasyon', [LandingController::class, 'documentation'])
    ->name('landing.documentation');
Route::get('/destek-merkezi', [LandingController::class, 'support'])
    ->name('landing.support');
Route::get('/kvkk', [LandingController::class, 'kvkk'])
    ->name('landing.kvkk');
Route::get('/gizlilik', [LandingController::class, 'privacy'])
    ->name('landing.privacy');
Route::get('/cerez-politikasi', [LandingController::class, 'cookies'])
    ->name('landing.cookies');
Route::get('/kullanici-sozlesmesi', [LandingController::class, 'terms'])
    ->name('landing.terms');
Route::get('/acik-riza-metni', [LandingController::class, 'explicitConsent'])
    ->name('landing.explicit-consent');
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

Route::middleware(['auth', 'verified'])->group(function () {

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

Route::get('/stock-movements/transfer', [StockMovementController::class, 'transfer'])
    ->name('stock-movements.transfer');

Route::post('/stock-movements/transfer', [StockMovementController::class, 'storeTransfer'])
    ->name('stock-movements.transfer.store');

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
| Quotes
|--------------------------------------------------------------------------
*/

Route::get('/quotes', [QuoteController::class, 'index'])
    ->name('quotes.index');

Route::get('/quotes/create', [QuoteController::class, 'create'])
    ->name('quotes.create');

Route::post('/quotes', [QuoteController::class, 'store'])
    ->name('quotes.store');

Route::get('/quotes/{quote}/print', [QuoteController::class, 'print'])
    ->name('quotes.print');

Route::get('/quotes/{quote}', [QuoteController::class, 'show'])
    ->name('quotes.show');

Route::get('/quotes/{quote}/edit', [QuoteController::class, 'edit'])
    ->name('quotes.edit');

Route::put('/quotes/{quote}', [QuoteController::class, 'update'])
    ->name('quotes.update');

Route::patch('/quotes/{quote}/send', [QuoteController::class, 'send'])
    ->name('quotes.send');

Route::patch('/quotes/{quote}/accept', [QuoteController::class, 'accept'])
    ->name('quotes.accept');

Route::patch('/quotes/{quote}/reject', [QuoteController::class, 'reject'])
    ->name('quotes.reject');

Route::patch('/quotes/{quote}/analyze', [QuoteController::class, 'analyze'])
    ->name('quotes.analyze');

Route::post('/quotes/{quote}/convert', [QuoteController::class, 'convert'])
    ->name('quotes.convert');

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

 /*
|--------------------------------------------------------------------------
| AI CHAT
|--------------------------------------------------------------------------
*/

Route::post('/ai/chat', [AIChatController::class, 'chat'])
->name('ai.chat');

Route::get('/admin/progress', [ProgressController::class, 'index'])
    ->middleware('permission:admin.progress.view')
    ->name('admin.progress.index');

Route::get('/admin/support-tickets', [SupportTicketController::class, 'adminIndex'])
    ->middleware('permission:admin.progress.view')
    ->name('admin.support-tickets.index');

Route::patch('/admin/users/{user}/activate-annual-license', [ProgressController::class, 'activateAnnualLicense'])
    ->middleware('permission:admin.progress.view')
    ->name('admin.users.activate-annual-license');

Route::get('/risk-analysis', [RiskAnalysisController::class, 'index'])
    ->name('risk-analysis.index');

Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');
Route::get('/reports/profit-loss', [ProfitLossController::class, 'index'])
    ->middleware('permission:profit_loss.view')
    ->name('reports.profit-loss');

Route::get('/expenses/categories', [ExpenseController::class, 'categories'])
    ->middleware('permission:expenses.view')
    ->name('expenses.categories');
Route::get('/expenses', [ExpenseController::class, 'index'])
    ->middleware('permission:expenses.view')
    ->name('expenses.index');
Route::get('/expenses/create', [ExpenseController::class, 'create'])
    ->middleware('permission:expenses.create')
    ->name('expenses.create');
Route::post('/expenses', [ExpenseController::class, 'store'])
    ->middleware('permission:expenses.create')
    ->name('expenses.store');
Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])
    ->middleware('permission:expenses.view')
    ->name('expenses.show');
Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])
    ->middleware('permission:expenses.update')
    ->name('expenses.edit');
Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])
    ->middleware('permission:expenses.update')
    ->name('expenses.update');
Route::patch('/expenses/{expense}/cancel', [ExpenseController::class, 'cancel'])
    ->middleware('permission:expenses.cancel')
    ->name('expenses.cancel');

Route::get('/settings', [SettingsController::class, 'index'])
    ->name('settings.index');
Route::get('/audit-logs', [AuditLogController::class, 'index'])
    ->name('audit-logs.index');
Route::get('/support-tickets', [SupportTicketController::class, 'index'])
    ->name('support-tickets.index');
Route::post('/support-tickets', [SupportTicketController::class, 'store'])
    ->name('support-tickets.store');
Route::patch('/support-tickets/{ticket}', [SupportTicketController::class, 'update'])
    ->name('support-tickets.update');
Route::post('/settings/sub-users', [SettingsController::class, 'storeSubUser'])
    ->name('settings.sub-users.store');
Route::put('/settings/sub-users/{user}', [SettingsController::class, 'updateSubUser'])
    ->name('settings.sub-users.update');
Route::delete('/settings/sub-users/{user}', [SettingsController::class, 'destroySubUser'])
    ->name('settings.sub-users.destroy');
Route::post('/settings/product-definitions', [SettingsController::class, 'storeDefinition'])
    ->name('settings.product-definitions.store');
Route::delete('/settings/product-definitions/{definition}', [SettingsController::class, 'destroyDefinition'])
    ->name('settings.product-definitions.destroy');

/*
|--------------------------------------------------------------------------
| AI TEST (Geçici)
|--------------------------------------------------------------------------
*/

Route::get('/ai-test', function () {

    $response = OpenAI::chat()->create([
        'model' => config('ai.providers.openai.model'),
        'messages' => [
            [
                'role' => 'user',
                'content' => 'Merhaba'
            ]
        ],
        'temperature' => config('ai.temperature'),
        'max_tokens' => config('ai.max_tokens'),
    ]);

    return $response->choices[0]->message->content;

})->name('ai.test');

});

require __DIR__.'/auth.php';
