<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'barcode',
        'name',
        'category',
        'brand',
        'model',
        'purchase_price',
        'sale_price',
        'vat',
        'stock',
        'min_stock',
        'unit',
        'is_active',
    ];

    /**
     * JSON çıktısına eklenecek alanlar
     */
    protected $appends = [
        'current_stock',
    ];

    /**
     * Veri tipleri
     */
    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price'     => 'decimal:2',
        'vat'            => 'decimal:2',
        'stock'          => 'decimal:2',
        'min_stock'      => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    /**
     * Ürüne ait tüm stok hareketleri
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Satış kalemleri
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Anlık stok hesaplama
     */
    public function currentStock()
    {
        $in = $this->stockMovements()
            ->whereIn('type', ['IN', 'RETURN'])
            ->sum('quantity');

        $out = $this->stockMovements()
            ->whereIn('type', ['OUT', 'TRANSFER'])
            ->sum('quantity');

        return $in - $out;
    }

    /**
     * current_stock accessor
     */
    public function getCurrentStockAttribute()
    {
        return $this->currentStock();
    }
}