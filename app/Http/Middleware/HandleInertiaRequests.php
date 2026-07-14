<?php

namespace App\Http\Middleware;

use App\Services\AI\NexoraBriefingService;
use App\Services\Finance\ExchangeRateService;
use App\Services\License\LicenseService;
use App\Services\Onboarding\OnboardingProgressService;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Throwable;

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
            'license' => fn () => $this->safeShared(
                fn () => app(LicenseService::class)->summary($request->user()),
                null
            ),
            'exchangeRates' => fn () => $request->user()
                ? $this->safeShared(
                    fn () => app(ExchangeRateService::class)->latest(),
                    [
                        'source' => 'TCMB',
                        'date' => null,
                        'updated_at' => now()->format('d.m.Y H:i'),
                        'available' => false,
                        'items' => [],
                    ]
                )
                : null,
            'onboarding' => fn () => $request->user()
                ? $this->safeShared(
                    fn () => [
                        'progress' => app(OnboardingProgressService::class)->getProgress($request->user()),
                        'tasks' => app(OnboardingProgressService::class)->getTasks($request->user()),
                        'completed' => app(OnboardingProgressService::class)->isCompleted($request->user()),
                    ],
                    null
                )
                : null,
            'aiBriefing' => fn () => $request->user()
                ? $this->safeShared(
                    fn () => app(NexoraBriefingService::class)->dashboardBriefing($request->user()->name),
                    null
                )
                : null,
        ];
    }

    private function safeShared(callable $callback, mixed $fallback): mixed
    {
        try {
            return $callback();
        } catch (Throwable $exception) {
            report($exception);

            return $fallback;
        }
    }
}
