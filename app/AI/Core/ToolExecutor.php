<?php

namespace App\AI\Core;

class ToolExecutor
{
    public function __construct(
        protected ToolRegistry $registry
    ) {
    }

    public function call(
        string $tool,
        string $method,
        array $arguments = []
    ): mixed {

        $class = $this->registry->resolve($tool);

        if (!$class) {
            throw new \RuntimeException(
                "Tool bulunamadı : {$tool}"
            );
        }

        $instance = app($class);

        if (!method_exists($instance, $method)) {
            throw new \RuntimeException(
                "{$tool}::{$method} metodu bulunamadı."
            );
        }

        return $instance->{$method}(...$arguments);
    }
}