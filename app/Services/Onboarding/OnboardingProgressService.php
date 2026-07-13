<?php

namespace App\Services\Onboarding;

use App\Enums\OnboardingTask;
use App\Models\OnboardingProgress;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OnboardingProgressService
{
    public function completeTask(User $user, OnboardingTask $task): OnboardingProgress
    {
        return DB::transaction(function () use ($user, $task) {
            $progress = OnboardingProgress::query()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->firstOrCreate(['user_id' => $user->id]);

            $column = $task->completedAtColumn();

            if ($progress->{$column} === null) {
                $progress->{$column} = now();
            }

            if ($progress->completed_at === null && $this->allTasksCompleted($progress)) {
                $progress->completed_at = now();
            }

            $progress->save();

            return $progress->fresh();
        });
    }

    public function getProgress(User $user): int
    {
        $completed = collect($this->getTasks($user))
            ->where('completed', true)
            ->count();

        return (int) round($completed / OnboardingTask::count() * 100);
    }

    public function getTasks(User $user): array
    {
        $progress = $this->progressFor($user);

        return collect(OnboardingTask::cases())
            ->map(fn (OnboardingTask $task) => [
                'key' => $task->value,
                'label' => $task->label(),
                'completed' => $progress->{$task->completedAtColumn()} !== null,
                'completed_at' => $progress->{$task->completedAtColumn()},
                'weight' => 25,
            ])
            ->all();
    }

    public function isCompleted(User $user): bool
    {
        return $this->progressFor($user)->completed_at !== null;
    }

    private function progressFor(User $user): OnboardingProgress
    {
        return OnboardingProgress::firstOrCreate([
            'user_id' => $user->id,
        ]);
    }

    private function allTasksCompleted(OnboardingProgress $progress): bool
    {
        foreach (OnboardingTask::cases() as $task) {
            if ($progress->{$task->completedAtColumn()} === null) {
                return false;
            }
        }

        return true;
    }
}
