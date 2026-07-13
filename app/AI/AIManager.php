<?php

namespace App\AI;

use App\AI\Core\AIKernel;

class AIManager
{
    public function __construct(
        protected AIKernel $kernel
    ) {
    }

    public function chat(array $messages): array
    {
        return $this->kernel->handle($messages);
    }
}