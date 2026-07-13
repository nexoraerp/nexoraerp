<?php

namespace App\Http\Controllers;

use App\Domain\Expenses\Services\ExpenseService;
use App\Exceptions\BusinessException;
use App\Http\Requests\CancelExpenseRequest;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\CashAccount;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    public function __construct(
        protected ExpenseService $expenseService
    ) {
    }

    public function index(Request $request)
    {
        $this->ensureDefaultCategories();

        return Inertia::render('Expenses/Index', [
            'expenses' => Expense::with(['category', 'creator'])
                ->latest('expense_date')
                ->get(),
            'categories' => $this->categoryOptions(),
            'filters' => [
                'search' => $request->input('search', ''),
            ],
            'kpis' => $this->kpis(),
        ]);
    }

    public function create()
    {
        $this->ensureDefaultCategories();

        return Inertia::render('Expenses/Create', [
            'categories' => $this->categoryOptions(),
            'cashAccounts' => $this->cashAccountOptions(),
        ]);
    }

    public function store(StoreExpenseRequest $request)
    {
        try {
            $expense = $this->expenseService->create($request->validated());
        } catch (BusinessException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('expenses.show', $expense)
            ->with('success', "Gider {$expense->expense_no} oluşturuldu.");
    }

    public function show(Expense $expense)
    {
        return Inertia::render('Expenses/Show', [
            'expense' => $expense->load(['category', 'creator', 'approver', 'canceller']),
        ]);
    }

    public function edit(Expense $expense)
    {
        if ($expense->status === 'Cancelled') {
            return redirect()->route('expenses.show', $expense)->with('error', 'İptal edilmiş gider düzenlenemez.');
        }

        return Inertia::render('Expenses/Edit', [
            'expense' => $expense->load('category'),
            'categories' => $this->categoryOptions(),
            'cashAccounts' => $this->cashAccountOptions(),
        ]);
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        if ($expense->status === 'Cancelled') {
            return back()->with('error', 'İptal edilmiş gider düzenlenemez.');
        }

        $expense = $this->expenseService->update($expense, $request->validated());

        return redirect()
            ->route('expenses.show', $expense)
            ->with('success', 'Gider güncellendi.');
    }

    public function cancel(CancelExpenseRequest $request, Expense $expense)
    {
        try {
            $this->expenseService->cancel($expense, $request->validated('cancel_reason'));
        } catch (BusinessException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('expenses.show', $expense)
            ->with('success', 'Gider iptal edildi.');
    }

    public function categories()
    {
        $this->ensureDefaultCategories();

        return Inertia::render('Expenses/Categories', [
            'categories' => ExpenseCategory::with('parent')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    private function categoryOptions()
    {
        return ExpenseCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn (ExpenseCategory $category) => [
                'value' => $category->id,
                'label' => $category->code.' - '.$category->name,
            ]);
    }

    private function cashAccountOptions()
    {
        return CashAccount::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn (CashAccount $account) => [
                'value' => $account->id,
                'label' => $account->code.' - '.$account->name,
            ]);
    }

    private function kpis(): array
    {
        $today = today();

        return [
            'today' => Expense::whereDate('expense_date', $today)->where('status', '!=', 'Cancelled')->sum('grand_total'),
            'month' => Expense::whereMonth('expense_date', now()->month)->whereYear('expense_date', now()->year)->where('status', '!=', 'Cancelled')->sum('grand_total'),
            'year' => Expense::whereYear('expense_date', now()->year)->where('status', '!=', 'Cancelled')->sum('grand_total'),
            'pending' => Expense::where('status', '!=', 'Cancelled')->where('remaining_total', '>', 0)->count(),
            'unpaid_total' => Expense::where('status', '!=', 'Cancelled')->sum('remaining_total'),
        ];
    }

    private function ensureDefaultCategories(): void
    {
        $defaults = [
            'Kira', 'Elektrik', 'Su', 'Doğalgaz', 'İnternet', 'Telefon', 'Personel', 'SGK',
            'Vergi', 'Akaryakıt', 'Kargo', 'Reklam', 'Yazılım', 'Muhasebe', 'Ofis',
            'Yemek', 'Bakım ve Onarım', 'Banka Komisyonları', 'Diğer',
        ];

        foreach ($defaults as $index => $name) {
            $code = Str::upper(Str::ascii(Str::slug($name, '')));

            ExpenseCategory::firstOrCreate(
                [
                    'user_id' => auth()->user()->tenantOwnerId(),
                    'code' => $code,
                ],
                [
                    'name' => $name,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
