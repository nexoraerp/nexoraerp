<?php

namespace App\AI\Core;

class ContextManager
{
    protected array $context = [];

    public function put(
        string $key,
        mixed $value
    ): void {
        $this->context[$key] = $value;
    }

    public function get(
        string $key,
        mixed $default = null
    ): mixed {
        return $this->context[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->context;
    }

    public function clear(): void
    {
        $this->context = [];
    }
}