<?php

namespace App\Http\Controllers;

use App\Models\CashAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CashAccountController extends Controller
{
    /**
     * Kasa Listesi
     */
    public function index()
    {
        $cashAccounts = CashAccount::latest()->get();

        return Inertia::render('CashAccounts/Index', [

            'cashAccounts' => $cashAccounts,

        ]);
    }

    /**
     * Yeni Kasa Sayfası
     */
    public function create()
    {
        return Inertia::render('CashAccounts/Create');
    }

    /**
     * Kasayı Kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'code' => [
                'required',
                Rule::unique('cash_accounts', 'code')->where('user_id', $request->user()?->id),
            ],

            'name' => 'required',

            'currency' => 'required',

            'notes' => 'nullable',

        ]);

        CashAccount::create($validated);

        return redirect()->route('cash-accounts.index');
    }

    /**
     * Kasa Detayı
     */
    public function show(CashAccount $cashAccount)
    {
        $cashAccount->load([
    
            'movements' => function ($query) {
    
                $query->latest('movement_date');
    
            },
    
        ]);
    
        return Inertia::render('CashAccounts/Show', [
    
            'cashAccount' => $cashAccount,
    
            'summary' => [
    
                'balance' =>
    
                    $cashAccount->movements->sum('debit')
                    -
                    $cashAccount->movements->sum('credit'),
    
                'total_in' =>
    
                    $cashAccount->movements->sum('debit'),
    
                'total_out' =>
    
                    $cashAccount->movements->sum('credit'),
    
                'movement_count' =>
    
                    $cashAccount->movements->count(),
    
            ],
    
            'movements' =>
    
                $cashAccount->movements,
    
        ]);
    }

    /**
     * Düzenleme Sayfası
     */
    public function edit(CashAccount $cashAccount)
    {
        //
    }

    /**
     * Güncelle
     */
    public function update(Request $request, CashAccount $cashAccount)
    {
        //
    }

    /**
     * Sil
     */
    public function destroy(CashAccount $cashAccount)
    {
        //
    }
}
