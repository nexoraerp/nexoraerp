<?php

namespace App\AI\Core;

class WorkflowStep
{
    public function __construct(

        public string $key,

        public string $question,

        public ?string $field = null,

        public bool $required = true,

        public mixed $validator = null,

        public mixed $transformer = null,

    ) {}

    /*
    |--------------------------------------------------------------------------
    | Validate
    |--------------------------------------------------------------------------
    */

    public function validate(string $value): bool
    {
        if ($this->validator === null) {
            return true;
        }

        return call_user_func(
            $this->validator,
            $value
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Transform
    |--------------------------------------------------------------------------
    */

    public function transform(string $value): string
    {
        if ($this->transformer === null) {
            return $value;
        }

        return call_user_func(
            $this->transformer,
            $value
        );
    }
}