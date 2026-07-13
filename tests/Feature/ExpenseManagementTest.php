<?php

namespace Tests\Feature;

use App\Domain\Expenses\Services\ExpenseService;
use App\Exceptions\BusinessException;
use App\Models\CashAccount;
use App\Models\CashMovement;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use App\Services\Reports\ProfitLossReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ExpenseManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_paid_expense_creates_cash_movement_with_vat_and_discount(): void
    {
        $user = $this->user();
        $category = $this->category($user);
        $cash = $this->cash($user);

        $this->actingAs($user);

        $expense = app(ExpenseService::class)->create([
            'expense_category_id' => $category->id,
            'expense_date' => today()->toDateString(),
            'due_date' => today()->toDateString(),
            'title' => 'Reklam gideri',
            'subtotal' => 1000,
            'discount' => 100,
            'vat_rate' => 20,
            'paid_total' => 1080,
            'payment_method' => 'Cash',
            'payment_source_type' => 'cash_account',
            'payment_source_id' => $cash->id,
        ]);

        $this->assertSame('Paid', $expense->payment_status);
        $this->assertSame('Paid', $expense->status);
        $this->assertEquals(180, (float) $expense->vat);
        $this->assertEquals(1080, (float) $expense->grand_total);
        $this->assertEquals(0, (float) $expense->remaining_total);

        $this->assertDatabaseHas('cash_movements', [
            'cash_account_id' => $cash->id,
            'type' => 'EXPENSE',
            'reference_id' => $expense->id,
            'debit' => 0,
            'credit' => 1080,
        ]);
    }

    public function test_unpaid_and_partial_expense_statuses_are_consistent(): void
    {
        $user = $this->user();
        $category = $this->category($user);
        $this->actingAs($user);

        $unpaid = app(ExpenseService::class)->create([
            'expense_category_id' => $category->id,
            'expense_date' => today()->toDateString(),
            'title' => 'Ofis gideri',
            'subtotal' => 500,
            'vat_rate' => 0,
            'paid_total' => 0,
        ]);

        $partial = app(ExpenseService::class)->create([
            'expense_category_id' => $category->id,
            'expense_date' => today()->toDateString(),
            'title' => 'Yazılım gideri',
            'subtotal' => 500,
            'vat_rate' => 0,
            'paid_total' => 200,
        ]);

        $this->assertSame('Unpaid', $unpaid->payment_status);
        $this->assertSame('Approved', $unpaid->status);
        $this->assertSame('Partial', $partial->payment_status);
        $this->assertSame('PartiallyPaid', $partial->status);
    }

    public function test_expense_cannot_be_cancelled_twice_and_reversal_cash_movement_is_created(): void
    {
        $user = $this->user();
        $category = $this->category($user);
        $cash = $this->cash($user);
        $this->actingAs($user);

        $expense = app(ExpenseService::class)->create([
            'expense_category_id' => $category->id,
            'expense_date' => today()->toDateString(),
            'title' => 'Kargo gideri',
            'subtotal' => 300,
            'vat_rate' => 0,
            'paid_total' => 300,
            'payment_source_type' => 'cash_account',
            'payment_source_id' => $cash->id,
        ]);

        app(ExpenseService::class)->cancel($expense, 'Yanlış kayıt');

        $this->assertDatabaseHas('cash_movements', [
            'cash_account_id' => $cash->id,
            'type' => 'EXPENSE_CANCEL',
            'reference_id' => $expense->id,
            'debit' => 300,
            'credit' => 0,
        ]);

        $this->expectException(BusinessException::class);

        app(ExpenseService::class)->cancel($expense->fresh(), 'Tekrar');
    }

    public function test_profit_loss_uses_completed_sales_excludes_cancelled_records_and_keeps_vat_out_of_profit(): void
    {
        $user = $this->user();
        $category = $this->category($user);
        $this->actingAs($user);

        $sale = Sale::create([
            'sale_no' => 'SAT-TEST-1',
            'customer_id' => $this->customerId($user),
            'sale_date' => today(),
            'due_date' => today(),
            'payment_type' => 'Cash',
            'subtotal' => 1000,
            'discount' => 0,
            'vat' => 200,
            'grand_total' => 1200,
            'payment_status' => 'Unpaid',
            'paid_total' => 0,
            'remaining_total' => 1200,
            'status' => 'Completed',
            'user_id' => $user->id,
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $this->productId($user),
            'warehouse_id' => $this->warehouseId($user),
            'quantity' => 2,
            'unit_price' => 500,
            'purchase_price_snapshot' => 300,
            'discount' => 0,
            'vat' => 20,
            'line_total' => 1200,
        ]);

        Sale::create([
            'sale_no' => 'SAT-CANCEL',
            'customer_id' => $this->customerId($user),
            'sale_date' => today(),
            'due_date' => today(),
            'payment_type' => 'Cash',
            'subtotal' => 9999,
            'vat' => 0,
            'grand_total' => 9999,
            'status' => 'Cancelled',
            'user_id' => $user->id,
        ]);

        app(ExpenseService::class)->create([
            'expense_category_id' => $category->id,
            'expense_date' => today()->toDateString(),
            'title' => 'Faaliyet gideri',
            'subtotal' => 100,
            'discount' => 0,
            'vat_rate' => 20,
            'paid_total' => 0,
        ]);

        $cancelledExpense = app(ExpenseService::class)->create([
            'expense_category_id' => $category->id,
            'expense_date' => today()->toDateString(),
            'title' => 'İptal gider',
            'subtotal' => 999,
            'discount' => 0,
            'vat_rate' => 0,
            'paid_total' => 0,
        ]);
        app(ExpenseService::class)->cancel($cancelledExpense, 'İptal');

        $report = app(ProfitLossReportService::class)->build(Request::create('/reports/profit-loss', 'GET', [
            'range' => 'today',
        ]));

        $this->assertEquals(1000, $report['kpis']['net_sales']);
        $this->assertEquals(600, $report['kpis']['cost_of_sales']);
        $this->assertEquals(400, $report['kpis']['gross_profit']);
        $this->assertEquals(100, $report['kpis']['operating_expenses']);
        $this->assertEquals(300, $report['kpis']['operating_profit']);
        $this->assertEquals(200, $report['kpis']['sales_vat']);
        $this->assertEquals(20, $report['kpis']['expense_vat']);
    }

    private function user(): User
    {
        return User::create([
            'name' => 'Test User',
            'email' => uniqid('user').'@example.com',
            'password' => 'password',
            'role' => 'user',
            'is_active' => true,
        ]);
    }

    private function category(User $user): ExpenseCategory
    {
        return ExpenseCategory::create([
            'user_id' => $user->id,
            'name' => 'Reklam',
            'code' => uniqid('REK'),
            'is_active' => true,
        ]);
    }

    private function cash(User $user): CashAccount
    {
        return CashAccount::create([
            'user_id' => $user->id,
            'code' => uniqid('KAS'),
            'name' => 'Ana Kasa',
            'currency' => 'TRY',
            'is_active' => true,
        ]);
    }

    private function customerId(User $user): int
    {
        return \App\Models\Customer::create([
            'user_id' => $user->id,
            'code' => uniqid('CAR'),
            'name' => 'Test Cari',
            'type' => 'Customer',
            'is_active' => true,
        ])->id;
    }

    private function productId(User $user): int
    {
        return \App\Models\Product::create([
            'user_id' => $user->id,
            'code' => uniqid('URN'),
            'name' => 'Test Ürün',
            'unit' => 'Adet',
            'purchase_price' => 300,
            'sale_price' => 500,
            'vat' => 20,
            'min_stock' => 0,
        ])->id;
    }

    private function warehouseId(User $user): int
    {
        return \App\Models\Warehouse::create([
            'user_id' => $user->id,
            'code' => uniqid('DEP'),
            'name' => 'Ana Depo',
            'is_active' => true,
        ])->id;
    }
}
