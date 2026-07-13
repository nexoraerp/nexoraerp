<?php

namespace App\Services\License;

use App\Models\User;
use Carbon\Carbon;

class LicenseService
{
    public function startTrial(User $user): User
    {
        $startsAt = now();
        $endsAt = $startsAt->copy()->addDays(14);

        $user->forceFill([
            'trial_started_at' => $user->trial_started_at ?? $startsAt,
            'trial_ends_at' => $user->trial_ends_at ?? $endsAt,
            'license_started_at' => $user->license_started_at ?? $startsAt,
            'license_ends_at' => $user->license_ends_at ?? $endsAt,
        ])->save();

        return $user->fresh();
    }

    public function activateAnnual(User $user): User
    {
        $startsAt = now();
        $endsAt = $startsAt->copy()->addYear();

        $user->forceFill([
            'license_started_at' => $startsAt,
            'license_ends_at' => $endsAt,
            'is_active' => true,
        ])->save();

        return $user->fresh();
    }

    public function canWrite(User $user): bool
    {
        if ($this->isLegacyUser($user)) {
            return true;
        }

        return $this->isLicenseActive($user);
    }

    public function summary(?User $user): ?array
    {
        if ($user === null) {
            return null;
        }

        $trialEndsAt = $this->date($user->trial_ends_at);
        $licenseStartsAt = $this->date($user->license_started_at);
        $licenseEndsAt = $this->date($user->license_ends_at);
        $isActive = $this->canWrite($user);
        $remainingUntil = $licenseEndsAt ?? $trialEndsAt;
        $remainingDays = $remainingUntil
            ? (int) floor(max(0, now()->diffInDays($remainingUntil, false)))
            : null;

        return [
            'status' => $this->status($user),
            'can_write' => $isActive,
            'trial_started_at' => $this->format($user->trial_started_at),
            'trial_ends_at' => $this->format($user->trial_ends_at),
            'license_started_at' => $this->format($user->license_started_at),
            'license_ends_at' => $this->format($user->license_ends_at),
            'remaining_days' => $remainingDays,
            'remaining_days_label' => $remainingDays === null ? '-' : $remainingDays . ' gün',
            'message' => $isActive
                ? ($licenseEndsAt && $licenseEndsAt->greaterThan($trialEndsAt ?? now()->subDay())
                    ? 'Lisansınız aktif.'
                    : 'Deneme süreniz aktif.')
                : 'Deneme süreniz bitti. Mevcut bilgilere erişebilirsiniz, yeni kayıt ekleyemezsiniz.',
        ];
    }

    private function status(User $user): string
    {
        if ($this->isLegacyUser($user)) {
            return 'legacy';
        }

        if ($this->isLicenseActive($user) && $this->date($user->license_ends_at)?->greaterThan($this->date($user->trial_ends_at) ?? now()->subDay())) {
            return 'licensed';
        }

        return $this->isLicenseActive($user) ? 'trial' : 'expired';
    }

    private function isLicenseActive(User $user): bool
    {
        $endsAt = $this->date($user->license_ends_at);

        return $endsAt !== null && $endsAt->endOfDay()->greaterThanOrEqualTo(now());
    }

    private function isLegacyUser(User $user): bool
    {
        return $user->trial_ends_at === null && $user->license_ends_at === null;
    }

    private function date(mixed $value): ?Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }

    private function format(mixed $value): ?string
    {
        return $value ? Carbon::parse($value)->format('d.m.Y') : null;
    }
}
