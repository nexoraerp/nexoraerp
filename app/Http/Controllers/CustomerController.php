<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
        ]);
    }

    public function create()
    {
        return Inertia::render('Customers/Create');
    }
    public function show(Customer $customer)
    {
        $customer->load([
    
            'sales',
    
            'payments',
    
            'movements' => function ($query) {
    
                $query->with('reference')
                      ->orderBy('movement_date')
                      ->orderBy('id');
    
            },
    
        ]);
    
        return Inertia::render('Customers/Show', [
    
            'customer' => $customer,
    
            'summary' => [
    
                'balance' => $customer->balance,
    
                'total_sales' => $customer->sales->sum('grand_total'),
    
                'total_paid' => $customer->payments->sum('amount'),
    
                'open_invoice_count' => $customer->sales
                    ->where('remaining_total', '>', 0)
                    ->count(),
    
            ],
    
            'movements' => $customer->movements,
    
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:customers',
            'name' => 'required',
            'company' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'tax_number' => 'nullable',
            'tax_office' => 'nullable',
            'address' => 'nullable',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index');
    }

    public function edit(Customer $customer)
    {
        return Inertia::render('Customers/Edit', [
            'customer' => $customer,
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'code' => 'required|unique:customers,code,' . $customer->id,
            'name' => 'required',
            'company' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'tax_number' => 'nullable',
            'tax_office' => 'nullable',
            'address' => 'nullable',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index');
    }
    public function openSales(Customer $customer)
    {
        $sales = $customer->sales()
            ->where('status', 'Completed')
            ->where('remaining_total', '>', 0)
            ->orderBy('sale_date')
            ->get([
                'id',
                'sale_no',
                'sale_date',
                'due_date',
                'grand_total',
                'paid_total',
                'remaining_total',
            ]);
    
        return response()->json([
    
            'customer' => [
    
                'id' => $customer->id,
    
                'code' => $customer->code,
    
                'name' => $customer->name,
    
                'open_invoice_count' => $sales->count(),
    
                'open_balance' => $sales->sum('remaining_total'),
    
                'overdue_balance' => $sales
                    ->where('due_date', '<', today())
                    ->sum('remaining_total'),
    
            ],
    
            'sales' => $sales,
    
        ]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index');
    }
}