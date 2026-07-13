<?php

namespace App\AI\Tools;

use App\AI\Core\BaseTool;
use App\AI\Core\Tool;
use App\Services\StockService;

#[Tool('stock')]
class StockTool extends BaseTool
{
    public function __construct(
        StockService $service
    ) {
        $this->service = $service;
    }
}