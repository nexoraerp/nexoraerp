<?php

namespace App\Services;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleService
{
    public function create(Request $request): Sale
    {
        // Satış oluşturma mantığı buraya taşınacak.

        return new Sale();
    }
}