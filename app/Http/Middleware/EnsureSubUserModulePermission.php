<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubUserModulePermission
{
    private const ROUTE_PERMISSION_MAP = [
        'customers.' => 'customers',
        'quotes.' => 'quotes',
        'sales.' => 'sales',
        'payments.' => 'payments',
        'products.' => 'products',
        'warehouses.' => 'warehouses',
        'stock-movements.' => 'stock',
        'cash-accounts.' => 'finance',
        'expenses.' => 'expenses.view',
        'reports.profit-loss' => 'profit_loss.view',
        'reports.' => 'reports',
        'risk-analysis.' => 'reports',
        'settings.' => 'settings',
        'audit-logs.' => 'settings',
        'support-tickets.' => 'support',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || $user->parent_user_id === null) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if ($routeName === null) {
            return $next($request);
        }

        foreach (self::ROUTE_PERMISSION_MAP as $prefix => $permission) {
            if (str_starts_with($routeName, $prefix) && ! $user->canPerform($permission)) {
                abort(403, 'Bu bölüme erişim yetkiniz yok.');
            }
        }

        return $next($request);
    }
}
