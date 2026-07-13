<?php

namespace App\Http\Middleware;

use App\Services\License\LicenseService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLicenseAllowsWrite
{
    private const PROTECTED_ROUTE_PREFIXES = [
        'customers.',
        'products.',
        'warehouses.',
        'stock-movements.',
        'quotes.',
        'sales.',
        'payments.',
        'cash-accounts.',
        'settings.',
    ];

    public function __construct(
        protected LicenseService $license
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethodSafe() || $request->user() === null) {
            return $next($request);
        }

        if (! $this->isProtectedBusinessRoute($request)) {
            return $next($request);
        }

        if ($this->license->canWrite($request->user())) {
            return $next($request);
        }

        return back()->with(
            'error',
            'Deneme süreniz bitti. Mevcut bilgilere erişebilirsiniz fakat yeni kayıt ekleyemez veya işlem yapamazsınız.'
        );
    }

    private function isProtectedBusinessRoute(Request $request): bool
    {
        $routeName = $request->route()?->getName();

        if ($routeName === null) {
            return false;
        }

        foreach (self::PROTECTED_ROUTE_PREFIXES as $prefix) {
            if (str_starts_with($routeName, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
