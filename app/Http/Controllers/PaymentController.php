<?php

namespace App\Http\Controllers;

use App\Actions\Onboarding\CompleteFirstPaymentTaskAction;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\PaymentItem;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use App\Models\CashAccount;
use App\Models\AccountMovement;
use App\Models\CashMovement;

class PaymentController extends Controller
{
    public function __construct(
        protected CompleteFirstPaymentTaskAction $completeFirstPaymentTask
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with([
            'customer',
            'user',
        ])
        ->latest()
        ->get();
    
        return Inertia::render('Payments/Index', [
    
            'payments' => $payments,
    
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return Inertia::render('Payments/Create', [

        'customers' => Customer::orderBy('name')
            ->get()
            ->map(fn ($customer) => [

                'value' => $customer->id,

                'label' => $customer->code.' - '.$customer->name,

            ]),

        'cashAccounts' => CashAccount::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn ($cash) => [

                'value' => $cash->id,

                'label' => $cash->code.' - '.$cash->name,

            ]),

    ]);
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
    
            'customer_id' => ['required', 'exists:customers,id'],
            'cash_account_id' => [
    'required',
    'exists:cash_accounts,id',
],
    
            'payment_method' => ['required', 'in:Cash,Bank,Card,Mixed'],
    
            'reference_no' => ['nullable', 'string'],
    
            'notes' => ['nullable', 'string'],
    
            'items' => ['required', 'array', 'min:1'],
    
            'items.*.sale_id' => ['required', 'exists:sales,id'],
    
            'items.*.amount' => ['required', 'numeric', 'min:0.01'],
    
        ]);
    
        DB::beginTransaction();
    
        try {
    
            $payment = Payment::create([
    
                'payment_no' => 'PAY-' . now()->format('YmdHis'),
    
                'customer_id' => $validated['customer_id'],
    
                'payment_date' => now(),
    
                'amount' => collect($validated['items'])->sum('amount'),
    
                'payment_method' => $validated['payment_method'],
    
                'reference_no' => $validated['reference_no'] ?? null,
    
                'notes' => $validated['notes'] ?? null,
    
                'user_id' => Auth::id(),
    
            ]);
           
            foreach ($validated['items'] as $item) {

                $sale = Sale::findOrFail($item['sale_id']);

                if ((int) $sale->customer_id !== (int) $validated['customer_id']) {

                    throw new \Exception(
                        'Seçilen fatura bu cariye ait değil.'
                    );

                }
            
                /*
                |--------------------------------------------------------------------------
                | Güvenlik Kontrolleri
                |--------------------------------------------------------------------------
                */
            
                if ($sale->status !== 'Completed') {
            
                    throw new \Exception(
                        "Tamamlanmamış satış tahsil edilemez."
                    );
            
                }
            
                if ($sale->remaining_total <= 0) {
            
                    throw new \Exception(
                        "Bu fatura daha önce tamamen tahsil edilmiş."
                    );
            
                }
            
                if ($item['amount'] > $sale->remaining_total) {
            
                    throw new \Exception(
                        "Tahsilat tutarı kalan borçtan büyük olamaz."
                    );
            
                }
            
                /*
                |--------------------------------------------------------------------------
                | Payment Item
                |--------------------------------------------------------------------------
                */
            
                PaymentItem::create([
            
                    'payment_id' => $payment->id,
            
                    'sale_id' => $sale->id,
            
                    'amount' => $item['amount'],
            
                ]);

                $sale->paid_total = $sale->paid_total + $item['amount'];

                $sale->remaining_total = max(
                    0,
                    $sale->remaining_total - $item['amount']
                );

                if ($sale->remaining_total == 0) {
                    $sale->payment_status = 'Paid';
                } elseif ($sale->paid_total > 0) {
                    $sale->payment_status = 'Partial';
                } else {
                    $sale->payment_status = 'Unpaid';
                }

                $sale->save();

                $this->recordAccountMovement(
                    $payment,
                    $sale,
                    $item['amount']
                );

                $this->recordCashMovement(
                    $payment,
                    $sale,
                    $item['amount'],
                    $validated['cash_account_id']
                );

            
            }
    
            $this->completeFirstPaymentTask->execute($request->user());

            DB::commit();
    
            return redirect()
                ->route('payments.index')
                ->with('success', 'Tahsilat oluşturuldu.');
    
        } catch (\Throwable $e) {
    
            DB::rollBack();
    
            return back()->with('error', $e->getMessage());
    
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }

    private function recordAccountMovement(
        Payment $payment,
        Sale $sale,
        float $amount
    ): void {
        AccountMovement::create([
            'customer_id' => $payment->customer_id,
            'movement_date' => $payment->payment_date,
            'type' => 'PAYMENT',
            'reference_type' => Payment::class,
            'reference_id' => $payment->id,
            'debit' => 0,
            'credit' => $amount,
            'due_date' => $sale->due_date,
            'description' => 'Tahsilat : '.$payment->payment_no.' / '.$sale->sale_no,
            'user_id' => Auth::id(),
        ]);
    }

    private function recordCashMovement(
        Payment $payment,
        Sale $sale,
        float $amount,
        int $cashAccountId
    ): void {
        CashMovement::create([
            'cash_account_id' => $cashAccountId,
            'movement_date' => $payment->payment_date,
            'type' => 'COLLECTION',
            'reference_type' => Payment::class,
            'reference_id' => $payment->id,
            'debit' => $amount,
            'credit' => 0,
            'description' => 'Tahsilat : '.$payment->payment_no.' / '.$sale->sale_no,
            'user_id' => Auth::id(),
        ]);
    }
}
