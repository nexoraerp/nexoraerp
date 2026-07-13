<?php

namespace App\Http\Controllers;

use App\Actions\Onboarding\CompleteFirstProductTaskAction;
use App\Models\Product;
use App\Models\ProductDefinition;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(
        protected CompleteFirstProductTaskAction $completeFirstProductTask
    ) {
    }

    public function index()
    {
        $products = Product::latest()
            ->get()
            ->map(function ($product) {

                // Canlı stok hesapla
                $product->current_stock = $product->currentStock();

                return $product;
            });

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/Create', [
            'warehouses' => $this->warehouseOptions(),
            'definitions' => $this->definitionOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'             => [
                'required',
                Rule::unique('products', 'code')->where('user_id', $request->user()?->id),
            ],
            'barcode'          => 'nullable|string|max:255',
            'name'             => 'required|string|max:255',
            'category'         => 'nullable|string|max:255',
            'brand'            => 'nullable|string|max:255',
            'model'            => 'nullable|string|max:255',
            'purchase_price'   => 'required|numeric|min:0',
            'sale_price'       => 'required|numeric|min:0',
            'vat'              => 'required|numeric|min:0',
            'stock'            => 'nullable|numeric|min:0',
            'warehouse_id'     => 'nullable|exists:warehouses,id',
            'min_stock'        => 'required|numeric|min:0',
            'unit'             => 'required|string|max:50',
        ]);

        $validated['stock'] = $validated['stock'] ?? 0;

        if ((float) $validated['stock'] > 0 && empty($validated['warehouse_id'])) {
            return back()->withErrors([
                'warehouse_id' => 'Stok girişi için depo seçiniz.',
            ]);
        }

        DB::transaction(function () use ($request, $validated) {
            $product = Product::create(
                collect($validated)->except('warehouse_id')->all()
            );

            $this->syncOpeningStock(
                $product,
                (float) $validated['stock'],
                $validated['warehouse_id'] ?? null,
                'Ürün başlangıç stoğu'
            );

            $this->completeFirstProductTask->execute($request->user());
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Ürün başarıyla oluşturuldu.');
    }

    public function edit(Product $product)
    {
        $product->stock = $product->currentStock();

        return Inertia::render('Products/Edit', [
            'product' => $product,
            'warehouses' => $this->warehouseOptions(),
            'definitions' => $this->definitionOptions(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code'             => [
                'required',
                Rule::unique('products', 'code')
                    ->where('user_id', $request->user()?->id)
                    ->ignore($product->id),
            ],
            'barcode'          => 'nullable|string|max:255',
            'name'             => 'required|string|max:255',
            'category'         => 'nullable|string|max:255',
            'brand'            => 'nullable|string|max:255',
            'model'            => 'nullable|string|max:255',
            'purchase_price'   => 'required|numeric|min:0',
            'sale_price'       => 'required|numeric|min:0',
            'vat'              => 'required|numeric|min:0',
            'stock'            => 'nullable|numeric|min:0',
            'warehouse_id'     => 'nullable|exists:warehouses,id',
            'min_stock'        => 'required|numeric|min:0',
            'unit'             => 'required|string|max:50',
        ]);

        $validated['stock'] = $validated['stock'] ?? $product->currentStock();

        if (
            round((float) $validated['stock'] - (float) $product->currentStock(), 2) != 0.0
            && empty($validated['warehouse_id'])
        ) {
            return back()->withErrors([
                'warehouse_id' => 'Stok düzeltmesi için depo seçiniz.',
            ]);
        }

        DB::transaction(function () use ($product, $validated) {
            $targetStock = (float) $validated['stock'];

            $product->update(
                collect($validated)->except('warehouse_id')->all()
            );

            $this->syncStockDifference(
                $product,
                $targetStock,
                $validated['warehouse_id'] ?? null
            );
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Ürün başarıyla güncellendi.');
    }

    public function show(Product $product)
    {
        $product->current_stock = $product->currentStock();

        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Ürün silindi.');
    }

    private function syncOpeningStock(
        Product $product,
        float $quantity,
        ?int $warehouseId,
        string $description
    ): void {
        if ($quantity <= 0) {
            return;
        }

        StockMovement::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouseId,
            'type' => 'IN',
            'quantity' => $quantity,
            'unit_cost' => $product->purchase_price,
            'reference_type' => Product::class,
            'reference_id' => $product->id,
            'description' => $description,
            'created_by' => Auth::id(),
        ]);
    }

    private function syncStockDifference(
        Product $product,
        float $targetStock,
        ?int $warehouseId
    ): void {
        $currentStock = (float) $product->currentStock();
        $difference = round($targetStock - $currentStock, 2);

        if ($difference == 0.0) {
            return;
        }

        StockMovement::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouseId,
            'type' => $difference > 0 ? 'IN' : 'OUT',
            'quantity' => abs($difference),
            'unit_cost' => $product->purchase_price,
            'reference_type' => Product::class,
            'reference_id' => $product->id,
            'description' => 'Ürün stok düzeltmesi',
            'created_by' => Auth::id(),
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

    private function definitionOptions(): array
    {
        $definitions = ProductDefinition::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        return collect(ProductDefinition::types())
            ->keys()
            ->mapWithKeys(fn ($type) => [
                $type => ($definitions[$type] ?? collect())
                    ->pluck('name')
                    ->unique()
                    ->map(fn (string $name) => [
                        'value' => $name,
                        'label' => $name,
                    ])
                    ->values(),
            ])
            ->all();
    }
}
