<?php

namespace App\Services;

use App\Models\Warehouse;

class WarehouseService extends BaseService
{
    public function __construct()
    {
        $this->model = new Warehouse();
    }
}