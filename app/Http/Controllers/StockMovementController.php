<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function transfer()
    {
        return Inertia::render('StockMovements/Transfer', [
            'products' => Product::orderBy('name')
                ->get()
                ->map(fn ($product) => [
                    'value' => $product->id,
                    'label' => $product->code . ' - ' . $product->name,
                    'stock' => (float) $product->current_stock,
                    'unit' => $product->unit,
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

    public function storeTransfer(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'from_warehouse_id' => ['required', 'exists:warehouses,id', 'different:to_warehouse_id'],
            'to_warehouse_id' => ['required', 'exists:warehouses,id'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $availableStock = $this->warehouseStock(
            (int) $validated['product_id'],
            (int) $validated['from_warehouse_id']
        );

        if ($availableStock < (float) $validated['quantity']) {
            return back()
                ->withErrors([
                    'quantity' => 'Kaynak depoda yeterli stok yok. Mevcut stok: ' . number_format($availableStock, 2, ',', '.'),
                ])
                ->withInput();
        }

        DB::transaction(function () use ($validated) {
            $product = Product::findOrFail($validated['product_id']);
            $fromWarehouse = Warehouse::findOrFail($validated['from_warehouse_id']);
            $toWarehouse = Warehouse::findOrFail($validated['to_warehouse_id']);
            $description = $validated['description']
                ?: "{$product->name} {$fromWarehouse->name} deposundan {$toWarehouse->name} deposuna transfer edildi.";

            $out = StockMovement::create([
                'product_id' => $product->id,
                'warehouse_id' => $fromWarehouse->id,
                'type' => 'OUT',
                'quantity' => $validated['quantity'],
                'unit_cost' => $validated['unit_cost'] ?? $product->purchase_price ?? 0,
                'description' => 'Depo transfer çıkışı - ' . $description,
                'created_by' => auth()->id(),
            ]);

            StockMovement::create([
                'product_id' => $product->id,
                'warehouse_id' => $toWarehouse->id,
                'type' => 'IN',
                'quantity' => $validated['quantity'],
                'unit_cost' => $validated['unit_cost'] ?? $product->purchase_price ?? 0,
                'reference_type' => StockMovement::class,
                'reference_id' => $out->id,
                'description' => 'Depo transfer girişi - ' . $description,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('stock-movements.index')
            ->with('success', 'Depolar arası ürün transferi tamamlandı.');
    }

    private function warehouseStock(int $productId, int $warehouseId): float
    {
        $in = StockMovement::query()
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->whereIn('type', ['IN', 'RETURN'])
            ->sum('quantity');

        $out = StockMovement::query()
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->whereIn('type', ['OUT', 'TRANSFER'])
            ->sum('quantity');

        return (float) $in - (float) $out;
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
