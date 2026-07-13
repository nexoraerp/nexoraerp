<?php

namespace App\Services\Admin;

use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\Quote;
use App\Models\Sale;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\License\LicenseService;

class UserProgressService
{
    public function __construct(
        protected LicenseService $license
    ) {
    }

    public function summary(): array
    {
        return User::query()
            ->orderBy('name')
            ->get()
            ->map(function (User $user) {
                $salesCount = Sale::where('user_id', $user->id)->count();
                $paymentCount = Payment::where('user_id', $user->id)->count();
                $quoteCount = class_exists(Quote::class)
                    ? Quote::where('user_id', $user->id)->count()
                    : 0;
                $auditCount = AuditLog::where('user_id', $user->id)->count();
                $supportCount = class_exists(SupportTicket::class)
                    ? SupportTicket::where('tenant_user_id', $user->tenantOwnerId())->count()
                    : 0;
                $lastAction = AuditLog::where('user_id', $user->id)
                    ->latest()
                    ->first();
                $license = $this->license->summary($user);

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'company_name' => $user->company_name,
                    'role' => $user->role,
                    'is_active' => $user->is_active,
                    'sales_count' => $salesCount,
                    'payments_count' => $paymentCount,
                    'quotes_count' => $quoteCount,
                    'audit_count' => $auditCount,
                    'support_count' => $supportCount,
                    'progress_score' => $this->score($salesCount, $paymentCount, $quoteCount, $auditCount),
                    'license' => $license,
                    'can_activate_license' => $user->role !== 'admin',
                    'last_action' => $lastAction?->action,
                    'last_action_at' => $lastAction?->created_at?->diffForHumans(),
                ];
            })
            ->values()
            ->all();
    }

    private function score(int $sales, int $payments, int $quotes, int $audits): int
    {
        return min(100, ($sales * 8) + ($payments * 6) + ($quotes * 5) + min(30, $audits));
    }
}
