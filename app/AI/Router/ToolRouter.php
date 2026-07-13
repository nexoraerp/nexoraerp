<?php

namespace App\AI\Router;

use App\AI\Agents\CustomerAgent;
use App\AI\Agents\FinanceAgent;
use App\AI\Agents\ProductAgent;
use App\AI\Agents\SalesAgent;

class ToolRouter
{
    public function route(string $intent): mixed
    {
        return match ($intent) {

            'customer.create' => app(CustomerAgent::class),

            'sale.create' => app(SalesAgent::class),

            'product.create' => app(ProductAgent::class),

            'payment.create' => app(FinanceAgent::class),

            default => null,
        };
    }
}