<?php

namespace App\AI\Tools;

use App\AI\Core\BaseTool;
use App\AI\Core\Tool;
use App\Services\PurchaseService;

#[Tool('purchase')]
class PurchaseTool extends BaseTool
{
    public function __construct(
        PurchaseService $service
    ) {
        $this->service = $service;
    }
}