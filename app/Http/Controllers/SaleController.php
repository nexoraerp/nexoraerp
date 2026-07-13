<?php

namespace App\Http\Controllers;

use App\Domain\Sales\Services\SalesService;
use App\Exceptions\BusinessException;
use App\Http\Requests\CancelSaleRequest;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use Inertia\Inertia;

class SaleController extends Controller
{
    public function __construct(
        protected SalesService $salesService
    ) {
    }
    
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
            'customers' => $this->customerOptions(),
            'products' => $this->productOptions(),
            'warehouses' => $this->warehouseOptions(),
        ]);
    }

    public function store(StoreSaleRequest $request)
    {
        try {
            $sale = $this->salesService->create(
                $request->validated()
            );
        } catch (BusinessException $exception) {
            return back()->with('error', $exception->getMessage());
        }
    
        return redirect()
            ->route('sales.show', $sale)
            ->with(
                'success',
                "Satış {$sale->sale_no} başarıyla oluşturuldu."
            );
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
            'customers' => $this->customerOptions(),
            'products' => $this->productOptions(),
            'warehouses' => $this->warehouseOptions(),
        ]);
    }

    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        try {
            $this->salesService->update($sale, $request->validated());
        } catch (BusinessException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', 'Satış başarıyla güncellendi.');
    }

    public function cancel(CancelSaleRequest $request, Sale $sale)
    {
        try {
            $this->salesService->cancel(
                $sale,
                $request->validated('cancel_reason')
            );
        } catch (BusinessException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', 'Satış başarıyla iptal edildi.');
    }

    public function destroy(Sale $sale)
    {
        //
    }

    private function customerOptions()
    {
        return Customer::orderBy('name')
            ->get()
            ->map(fn ($customer) => [
                'value' => $customer->id,
                'label' => $customer->code . ' - ' . $customer->name,
            ]);
    }

    private function productOptions()
    {
        return Product::orderBy('name')
            ->get()
            ->map(fn ($product) => [
                'value' => $product->id,
                'label' => $product->code . ' - ' . $product->name,
                'price' => (float) $product->sale_price,
                'purchase_price' => (float) $product->purchase_price,
                'vat' => (float) $product->vat,
                'stock' => (float) $product->current_stock,
                'unit' => $product->unit,
                'category' => $product->category,
                'brand' => $product->brand,
                'model' => $product->model,
                'barcode' => $product->barcode,
            ]);
    }

    private function warehouseOptions()
    {
        return Warehouse::orderBy('name')
            ->get()
            ->map(fn ($warehouse) => [
                'value' => $warehouse->id,
                'label' => $warehouse->name,
            ]);
    }
}
