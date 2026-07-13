<?php

namespace App\Domain\Kernel;

abstract class DTO
{
    public static function from(array $data): static
    {
        return new static(...$data);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}