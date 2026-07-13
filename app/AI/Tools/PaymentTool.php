<?php

namespace App\AI\Tools;

use App\AI\Core\BaseTool;
use App\AI\Core\Tool;
use App\Services\PaymentService;

#[Tool('payment')]
class PaymentTool extends BaseTool
{
    public function __construct(
        PaymentService $service
    ) {
        $this->service = $service;
    }
}