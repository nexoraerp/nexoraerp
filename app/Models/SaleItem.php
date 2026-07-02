<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'warehouse_id',
        'quantity',
        'unit_price',
        'discount',
        'vat',
        'line_total',
    ];

    /**
     * Veri tipleri
     */
    protected $casts = [
        'quantity'    => 'decimal:2',
        'unit_price'  => 'decimal:2',
        'discount'    => 'decimal:2',
        'vat'         => 'decimal:2',
        'line_total'  => 'decimal:2',
    ];

    /**
     * Satış
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Ürün
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Depo
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}