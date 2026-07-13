<?php

namespace App\Services\Audit;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLogger
{
    public function logRequest(Request $request, Response $response): AuditLog
    {
        return AuditLog::create([
            'user_id' => $request->user()?->id,
            'action' => $this->resolveAction($request),
            'method' => $request->method(),
            'route_name' => $request->route()?->getName(),
            'url' => $request->path(),
            'response_status' => $response->getStatusCode(),
            'payload' => $this->cleanPayload($request->except([
                'password',
                'password_confirmation',
                'current_password',
                '_token',
            ])),
            'metadata' => [
                'referer' => $request->headers->get('referer'),
                'route_parameters' => $this->routeParameters($request),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    private function resolveAction(Request $request): string
    {
        $route = $request->route()?->getName();

        if ($route) {
            return $route;
        }

        return strtolower($request->method()).':'.$request->path();
    }

    private function cleanPayload(array $payload): array
    {
        return collect($payload)
            ->map(function ($value) {
                if (is_string($value) && mb_strlen($value) > 500) {
                    return mb_substr($value, 0, 500).'...';
                }

                return $value;
            })
            ->all();
    }

    private function routeParameters(Request $request): array
    {
        return collect($request->route()?->parameters() ?? [])
            ->mapWithKeys(function ($value, $key) {
                if (is_object($value) && method_exists($value, 'getKey')) {
                    return [
                        $key => [
                            'id' => $value->getKey(),
                            'label' => $this->modelLabel($value),
                        ],
                    ];
                }

                return [$key => $value];
            })
            ->all();
    }

    private function modelLabel(object $model): string
    {
        foreach (['name', 'company', 'subject', 'sale_no', 'payment_no', 'quote_no', 'code'] as $attribute) {
            if (isset($model->{$attribute}) && $model->{$attribute}) {
                return (string) $model->{$attribute};
            }
        }

        return class_basename($model) . ' #' . $model->getKey();
    }
}
