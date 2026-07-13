<?php

namespace App\AI\Contracts;

interface AgentContract
{
    /**
     * Agent çalıştırılır.
     */
    public function execute(string $message): array;
}