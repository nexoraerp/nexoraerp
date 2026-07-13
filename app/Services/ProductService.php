<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService extends BaseService
{
    public function __construct()
    {
        $this->model = new Product();
    }

    /*
    |--------------------------------------------------------------------------
    | Ürün Ara
    |--------------------------------------------------------------------------
    */

    public function search(string $search): Collection
    {
        return Product::query()

            ->where('name', 'like', "%{$search}%")
            ->orWhere('code', 'like', "%{$search}%")
            ->orWhere('barcode', 'like', "%{$search}%")
            ->orWhere('brand', 'like', "%{$search}%")
            ->orWhere('model', 'like', "%{$search}%")
            ->orWhere('category', 'like', "%{$search}%")

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | İsme Göre Bul
    |--------------------------------------------------------------------------
    */

    public function findByName(string $name): ?Product
    {
        return Product::query()

            ->where('name', $name)

            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Koda Göre Bul
    |--------------------------------------------------------------------------
    */

    public function findByCode(string $code): ?Product
    {
        return Product::query()

            ->where('code', $code)

            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Barkoda Göre Bul
    |--------------------------------------------------------------------------
    */

    public function findByBarcode(string $barcode): ?Product
    {
        return Product::query()

            ->where('barcode', $barcode)

            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | ID'ye Göre Bul (Exception Fırlatır)
    |--------------------------------------------------------------------------
    */

    public function findOrFail(int $id): Product
    {
        return Product::query()->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Ürün Var mı?
    |--------------------------------------------------------------------------
    */

    public function existsByName(string $name): bool
    {
        return Product::query()

            ->where('name', $name)

            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Satış Fiyatı
    |--------------------------------------------------------------------------
    */

    public function price(Product $product): float
    {
        return (float) $product->sale_price;
    }

    /*
    |--------------------------------------------------------------------------
    | Alış Fiyatı
    |--------------------------------------------------------------------------
    */

    public function purchasePrice(Product $product): float
    {
        return (float) $product->purchase_price;
    }

    /*
    |--------------------------------------------------------------------------
    | KDV
    |--------------------------------------------------------------------------
    */

    public function vat(Product $product): float
    {
        return (float) $product->vat;
    }

    /*
    |--------------------------------------------------------------------------
    | Güncel Stok
    |--------------------------------------------------------------------------
    */

    public function stock(Product $product): float
    {
        return (float) $product->current_stock;
    }

    /*
    |--------------------------------------------------------------------------
    | Stok Yeterli mi?
    |--------------------------------------------------------------------------
    */

    public function hasEnoughStock(
        Product $product,
        float $quantity
    ): bool
    {
        return $this->stock($product) >= $quantity;
    }

    /*
    |--------------------------------------------------------------------------
    | Aktif Ürünler
    |--------------------------------------------------------------------------
    */

    public function active(): Collection
    {
        return Product::query()

            ->where('is_active', true)

            ->orderBy('name')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Kritik Stoktaki Ürünler
    |--------------------------------------------------------------------------
    */

    public function criticalStock(): Collection
    {
        return Product::query()

            ->get()

            ->filter(fn (Product $product) =>
                $product->current_stock <= $product->min_stock
            )

            ->values();
    }
}