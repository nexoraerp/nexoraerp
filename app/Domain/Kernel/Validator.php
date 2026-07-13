<?php

namespace App\Domain\Kernel;

abstract class Validator
{
    abstract public function validate(array $data): array;
}