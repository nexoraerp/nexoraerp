<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
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
        return Inertia::render('Products/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:products',
            'barcode' => 'nullable',
            'name' => 'required',
            'category' => 'nullable',
            'brand' => 'nullable',
            'model' => 'nullable',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'vat' => 'required|numeric',
            'stock' => 'required|numeric', // Şimdilik duruyor
            'min_stock' => 'required|numeric',
            'unit' => 'required',
        ]);

        Product::create($validated);

        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => 'required|unique:products,code,' . $product->id,
            'barcode' => 'nullable',
            'name' => 'required',
            'category' => 'nullable',
            'brand' => 'nullable',
            'model' => 'nullable',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'vat' => 'required|numeric',
            'stock' => 'required|numeric', // Şimdilik duruyor
            'min_stock' => 'required|numeric',
            'unit' => 'required',
        ]);

        $product->update($validated);

        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index');
    }
}