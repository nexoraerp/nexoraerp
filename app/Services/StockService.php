<?php

namespace App\Services;

use App\Models\StockMovement;

class StockService extends BaseService
{
    public function __construct()
    {
        $this->model = new StockMovement();
    }
}