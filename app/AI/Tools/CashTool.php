<?php

namespace App\AI\Tools;

use App\AI\Core\BaseTool;
use App\AI\Core\Tool;
use App\Services\CashService;

#[Tool('cash')]
class CashTool extends BaseTool
{
    public function __construct(
        CashService $service
    ) {
        $this->service = $service;
    }
}