<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with([
            'product',
            'warehouse',
            'user',
        ])
        ->latest()
        ->paginate(20);

        return Inertia::render('StockMovements/Index', [
            'movements' => $movements,
        ]);
    }

    public function create()
    {
        return Inertia::render('StockMovements/Create', [

            'products' => Product::orderBy('name')
                ->get()
                ->map(fn ($product) => [
                    'value' => $product->id,
                    'label' => $product->code . ' - ' . $product->name,
                ]),

            'warehouses' => Warehouse::orderBy('name')
                ->get()
                ->map(fn ($warehouse) => [
                    'value' => $warehouse->id,
                    'label' => $warehouse->name,
                ]),

            'types' => [
                'IN',
                'OUT',
                'TRANSFER',
                'ADJUSTMENT',
                'RETURN',
            ],

        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'   => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type'         => 'required|in:IN,OUT,TRANSFER,ADJUSTMENT,RETURN',
            'quantity'     => 'required|numeric|min:0.01',
            'unit_cost'    => 'nullable|numeric|min:0',
            'description'  => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        StockMovement::create($validated);

        return redirect()
            ->route('stock-movements.index')
            ->with('success', 'Stok hareketi başarıyla oluşturuldu.');
    }

    public function show(StockMovement $stockMovement)
    {
        return Inertia::render('StockMovements/Show', [
            'movement' => $stockMovement->load([
                'product',
                'warehouse',
                'user',
            ]),
        ]);
    }

    public function edit(StockMovement $stockMovement)
    {
        return Inertia::render('StockMovements/Edit', [

            'movement' => $stockMovement,

            'products' => Product::orderBy('name')
                ->get()
                ->map(fn ($product) => [
                    'value' => $product->id,
                    'label' => $product->code . ' - ' . $product->name,
                ]),

            'warehouses' => Warehouse::orderBy('name')
                ->get()
                ->map(fn ($warehouse) => [
                    'value' => $warehouse->id,
                    'label' => $warehouse->name,
                ]),

            'types' => [
                'IN',
                'OUT',
                'TRANSFER',
                'ADJUSTMENT',
                'RETURN',
            ],

        ]);
    }

    public function update(Request $request, StockMovement $stockMovement)
    {
        $validated = $request->validate([
            'product_id'   => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type'         => 'required|in:IN,OUT,TRANSFER,ADJUSTMENT,RETURN',
            'quantity'     => 'required|numeric|min:0.01',
            'unit_cost'    => 'nullable|numeric|min:0',
            'description'  => 'nullable|string',
        ]);

        $stockMovement->update($validated);

        return redirect()
            ->route('stock-movements.index')
            ->with('success', 'Stok hareketi güncellendi.');
    }

    public function destroy(StockMovement $stockMovement)
    {
        $stockMovement->delete();

        return redirect()
            ->route('stock-movements.index')
            ->with('success', 'Stok hareketi silindi.');
    }
}