<?php

namespace App\AI\Tools;

use App\AI\Core\BaseTool;
use App\AI\Core\Tool;
use App\Services\WarehouseService;

#[Tool('warehouse')]
class WarehouseTool extends BaseTool
{
    public function __construct(
        WarehouseService $service
    ) {
        $this->service = $service;
    }
}