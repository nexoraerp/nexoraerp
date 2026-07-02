<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\AccountMovement;
use App\Services\Finance\AccountMovementService;
class SaleController extends Controller
{
   

    public function index()
    {
        $sales = Sale::with([
            'customer',
            'user',
        ])
        ->latest()
        ->get();

        return Inertia::render('Sales/Index', [
            'sales' => $sales,
        ]);
    }

    public function create()
    {
        return Inertia::render('Sales/Create', [

            'customers' => Customer::orderBy('name')
                ->get()
                ->map(fn ($customer) => [
                    'value' => $customer->id,
                    'label' => $customer->code . ' - ' . $customer->name,
                ]),

            'products' => Product::orderBy('name')
                ->get()
                ->map(fn ($product) => [
                    'value' => $product->id,
                    'label' => $product->code . ' - ' . $product->name,
                    'price' => $product->sale_price,
                    'vat'   => $product->vat,
                    'stock' => $product->current_stock,
                ]),

            'warehouses' => Warehouse::orderBy('name')
                ->get()
                ->map(fn ($warehouse) => [
                    'value' => $warehouse->id,
                    'label' => $warehouse->name,
                ]),

        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'  => 'required|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
        
            'sale_date'    => 'required|date',
        
            'due_date'     => 'required|date|after_or_equal:sale_date',
        
            'payment_type' => 'required|in:Cash,Credit,Card,Bank,Mixed',
        
            'items'        => 'required|array|min:1',
        ]);

        DB::transaction(function () use ($request) {

            $subtotal = 0;
            $vatTotal = 0;

            foreach ($request->items as $item) {

                $lineTotal =
                    ($item['quantity'] * $item['unit_price'])
                    - ($item['discount'] ?? 0);

                $subtotal += $lineTotal;
                $vatTotal += $lineTotal * (($item['vat'] ?? 0) / 100);
            }

            $grandTotal = $subtotal + $vatTotal;

            $sale = Sale::create([

                'sale_no'         => 'SAT-' . now()->format('YmdHis'),
            
                'customer_id'     => $request->customer_id,
            
                'sale_date'       => $request->sale_date,
            
                'due_date'        => $request->payment_type === 'Cash'
                    ? $request->sale_date
                    : $request->due_date,
            
                'payment_type'    => $request->payment_type,
            
                'subtotal'        => $subtotal,
            
                'discount'        => 0,
            
                'vat'             => $vatTotal,
            
                'grand_total'     => $grandTotal,
            
                'payment_status'  => 'Unpaid',
            
                'paid_total'      => 0,
            
                'remaining_total' => $grandTotal,
            
                'status'          => 'Completed',
            
                'notes'           => $request->notes,
            
                'user_id'         => Auth::id(),
            
            ]);
            AccountMovementService::recordSale($sale);
            foreach ($request->items as $item) {

                $lineTotal =
                    ($item['quantity'] * $item['unit_price'])
                    - ($item['discount'] ?? 0);

                $sale->items()->create([

                    'product_id'   => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'discount'     => $item['discount'] ?? 0,
                    'vat'          => $item['vat'] ?? 0,
                    'line_total'   => $lineTotal,

                ]);

                StockMovement::create([

                    'product_id'     => $item['product_id'],
                    'warehouse_id'   => $item['warehouse_id'],
                    'type'           => 'OUT',
                    'quantity'       => $item['quantity'],
                    'unit_cost'      => $item['unit_price'],
                    'reference_type' => Sale::class,
                    'reference_id'   => $sale->id,
                    'description'    => 'Satış oluşturuldu - '.$sale->sale_no,
                    'created_by'     => Auth::id(),

                ]);

            }

        });

        return redirect()
            ->route('sales.index')
            ->with('success', 'Satış başarıyla kaydedildi.');
    }

    public function show(Sale $sale)
    {
        return Inertia::render('Sales/Show', [

            'sale' => $sale->load([
                'customer',
                'items.product',
                'items.warehouse',
                'user',
            ]),

        ]);
    }

    public function edit(Sale $sale)
    {
        if ($sale->status === 'Cancelled') {
    
            return redirect()
                ->route('sales.show', $sale)
                ->with('error', 'İptal edilmiş satış düzenlenemez.');
    
        }
    
        return Inertia::render('Sales/Edit', [
    
            'sale' => $sale->load('items'),
    
            'customers' => Customer::orderBy('name')
                ->get()
                ->map(fn ($customer) => [
                    'value' => $customer->id,
                    'label' => $customer->code . ' - ' . $customer->name,
                ]),
    
            'products' => Product::orderBy('name')
                ->get()
                ->map(fn ($product) => [
                    'value' => $product->id,
                    'label' => $product->code . ' - ' . $product->name,
                    'price' => $product->sale_price,
                    'vat'   => $product->vat,
                    'stock' => $product->current_stock,
                ]),
    
            'warehouses' => Warehouse::orderBy('name')
                ->get()
                ->map(fn ($warehouse) => [
                    'value' => $warehouse->id,
                    'label' => $warehouse->name,
                ]),
    
        ]);
    
    }     public function update(Request $request, Sale $sale)
    {
        if ($sale->status === 'Cancelled') {

            return back()->with('error', 'İptal edilmiş satış düzenlenemez.');

        }

        $request->validate([
            'customer_id'  => 'required|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'sale_date'    => 'required|date',
            'items'        => 'required|array|min:1',
        ]);

        DB::transaction(function () use ($request, $sale) {

            $subtotal = 0;
            $vatTotal = 0;

            foreach ($request->items as $item) {

                $lineTotal =
                    ($item['quantity'] * $item['unit_price'])
                    - ($item['discount'] ?? 0);

                $subtotal += $lineTotal;
                $vatTotal += $lineTotal * (($item['vat'] ?? 0) / 100);

            }

            $sale->update([

                'customer_id' => $request->customer_id,
            
                'sale_date'   => $request->sale_date,
            
                'due_date'    => $request->payment_type === 'Cash'
                    ? $request->sale_date
                    : $request->due_date,
            
                'payment_type' => $request->payment_type,
            
                'subtotal'    => $subtotal,
            
                'discount'    => 0,
            
                'vat'         => $vatTotal,
            
                'grand_total' => $subtotal + $vatTotal,
            
                'notes'       => $request->notes,
            
            ]);

            // Eski kalemleri sil
            $sale->items()->delete();

            // Eski stok hareketlerini sil
            StockMovement::where('reference_type', Sale::class)
                ->where('reference_id', $sale->id)
                ->where('type', 'OUT')
                ->delete();

            // Yeni kalemleri oluştur
            foreach ($request->items as $item) {

                $lineTotal =
                    ($item['quantity'] * $item['unit_price'])
                    - ($item['discount'] ?? 0);

                $sale->items()->create([

                    'product_id'   => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'discount'     => $item['discount'] ?? 0,
                    'vat'          => $item['vat'] ?? 0,
                    'line_total'   => $lineTotal,

                ]);

                StockMovement::create([

                    'product_id'     => $item['product_id'],
                    'warehouse_id'   => $item['warehouse_id'],
                    'type'           => 'OUT',
                    'quantity'       => $item['quantity'],
                    'unit_cost'      => $item['unit_price'],
                    'reference_type' => Sale::class,
                    'reference_id'   => $sale->id,
                    'description'    => 'Satış güncellendi - '.$sale->sale_no,
                    'created_by'     => Auth::id(),

                ]);

            }

        });

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', 'Satış başarıyla güncellendi.');
    }

    public function cancel(Request $request, Sale $sale)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:1000',
        ]);

        if ($sale->status === 'Cancelled') {

            return back()->with('error', 'Bu satış zaten iptal edilmiş.');

        }

        DB::transaction(function () use ($sale, $request) {

            $sale->update([

                'status'          => 'Cancelled',
                'cancel_reason'   => $request->cancel_reason,
                'cancelled_at'    => now(),
                'cancelled_by'    => Auth::id(),

            ]);

            foreach ($sale->items as $item) {

                StockMovement::create([

                    'product_id'     => $item->product_id,
                    'warehouse_id'   => $item->warehouse_id,
                    'type'           => 'IN',
                    'quantity'       => $item->quantity,
                    'unit_cost'      => $item->unit_price,
                    'reference_type' => Sale::class,
                    'reference_id'   => $sale->id,
                    'description'    => 'Satış iptal edildi - '.$sale->sale_no,
                    'created_by'     => Auth::id(),

                ]);

            }

        });

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', 'Satış başarıyla iptal edildi.');
    }

    public function destroy(Sale $sale)
    {
        //
    }
}