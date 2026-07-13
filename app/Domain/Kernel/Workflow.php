<?php

namespace App\Domain\Kernel;

abstract class Workflow
{
    abstract public function handle(array $data);
}