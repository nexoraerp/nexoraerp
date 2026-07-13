<?php

namespace App\Http\Middleware;

use App\Services\AI\NexoraBriefingService;
use App\Services\Finance\ExchangeRateService;
use App\Services\License\LicenseService;
use App\Services\Onboarding\OnboardingProgressService;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'license' => fn () => app(LicenseService::class)->summary($request->user()),
            'exchangeRates' => fn () => $request->user()
                ? app(ExchangeRateService::class)->latest()
                : null,
            'onboarding' => fn () => $request->user()
                ? [
                    'progress' => app(OnboardingProgressService::class)->getProgress($request->user()),
                    'tasks' => app(OnboardingProgressService::class)->getTasks($request->user()),
                    'completed' => app(OnboardingProgressService::class)->isCompleted($request->user()),
                ]
                : null,
            'aiBriefing' => fn () => $request->user()
                ? app(NexoraBriefingService::class)->dashboardBriefing($request->user()->name)
                : null,
        ];
    }
}
