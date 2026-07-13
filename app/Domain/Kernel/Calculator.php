<?php

namespace App\Domain\Kernel;

abstract class Calculator
{
    abstract public function calculate(array $data): array;
}