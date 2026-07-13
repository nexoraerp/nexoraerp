<?php

namespace App\AI\Core;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Tool
{
    public function __construct(
        public string $name
    ) {
    }
}