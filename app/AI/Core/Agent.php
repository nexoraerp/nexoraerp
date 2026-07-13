<?php

namespace App\AI\Core;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Agent
{
    public function __construct(
        public string $name
    ) {}
}