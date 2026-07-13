<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\UserProgressService;
use App\Services\License\LicenseService;
use Inertia\Inertia;

class ProgressController extends Controller
{
    public function __construct(
        protected UserProgressService $progress,
        protected LicenseService $license
    ) {
    }

    public function index()
    {
        abort_unless(request()->user()?->role === 'admin', 403);

        return Inertia::render('Admin/Progress/Index', [
            'users' => $this->progress->summary(),
        ]);
    }

    public function activateAnnualLicense(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin kullanıcısı için bu işlem uygulanamaz.');
        }

        $this->license->activateAnnual($user);

        return back()->with('success', "{$user->name} için 1 yıllık lisans aktif edildi.");
    }
}
