<?php

namespace App\Actions\Onboarding;

use App\Enums\OnboardingTask;
use App\Models\User;
use App\Services\Onboarding\OnboardingProgressService;

class CompleteFirstProductTaskAction
{
    public function __construct(
        protected OnboardingProgressService $onboarding
    ) {
    }

    public function execute(?User $user): void
    {
        if ($user === null) {
            return;
        }

        $this->onboarding->completeTask($user, OnboardingTask::FirstProduct);
    }
}
