<?php

namespace App\AI\Tools;

use App\AI\Core\BaseTool;
use App\AI\Core\Tool;
use App\Services\DashboardService;

#[Tool('dashboard')]
class DashboardTool extends BaseTool
{
    public function __construct(
        DashboardService $service
    ) {
        $this->service = $service;
    }
}