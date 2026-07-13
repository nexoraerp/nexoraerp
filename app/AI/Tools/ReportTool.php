<?php

namespace App\AI\Tools;

use App\AI\Core\BaseTool;
use App\AI\Core\Tool;
use App\Services\ReportService;

#[Tool('report')]
class ReportTool extends BaseTool
{
    public function __construct(
        ReportService $service
    ) {
        $this->service = $service;
    }
}