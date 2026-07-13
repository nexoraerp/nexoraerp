<?php

namespace App\Http\Controllers;

use App\Services\AI\DueReminderService;
use App\Services\Insights\RiskAnalysisService;
use Inertia\Inertia;

class RiskAnalysisController extends Controller
{
    public function __construct(
        protected DueReminderService $dueReminder,
        protected RiskAnalysisService $riskAnalysis
    ) {
    }

    public function index()
    {
        return Inertia::render('RiskAnalysis/Index', [
            'dueReminders' => $this->dueReminder->reminders(),
            'risks' => $this->riskAnalysis->risks(),
        ]);
    }
}
