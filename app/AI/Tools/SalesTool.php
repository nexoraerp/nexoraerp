<?php

namespace App\AI\Tools;

use App\AI\Core\BaseTool;
use App\AI\Core\Tool;
use App\Services\SalesService;

#[Tool('sales')]
class SalesTool extends BaseTool
{
    public function __construct(
        SalesService $service
    ) {
        $this->service = $service;
    }
}