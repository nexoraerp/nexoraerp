<?php

namespace App\AI\Core;

class TaskContext
{
    /**
     * Ortak veri havuzu
     */
    protected array $data = [];

    /**
     * Veri ekle
     */
    public function set(string $key, mixed $value): static
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Veri oku
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Veri var mı?
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Tüm veriler
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Temizle
     */
    public function clear(): void
    {
        $this->data = [];
    }
}