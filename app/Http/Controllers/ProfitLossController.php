<?php

namespace App\Http\Controllers;

use App\Services\Reports\ProfitLossReportService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfitLossController extends Controller
{
    public function __construct(
        protected ProfitLossReportService $profitLossReport
    ) {
    }

    public function index(Request $request)
    {
        return Inertia::render('Reports/ProfitLoss', $this->profitLossReport->build($request));
    }
}
