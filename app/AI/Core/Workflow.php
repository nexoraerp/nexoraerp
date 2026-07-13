<?php

namespace App\AI\Core;

class Workflow
{
    /**
     * @var WorkflowStep[]
     */
    protected array $steps = [];

    public function __construct(array $steps)
    {
        $this->steps = $steps;
    }

    public function first(): WorkflowStep
    {
        return $this->steps[0];
    }

    public function current(string $key): ?WorkflowStep
    {
        foreach ($this->steps as $step) {

            if ($step->key === $key) {
                return $step;
            }

        }

        return null;
    }

    public function next(string $key): ?WorkflowStep
    {
        foreach ($this->steps as $index => $step) {

            if ($step->key === $key) {

                return $this->steps[$index + 1] ?? null;

            }

        }

        return null;
    }

    public function all(): array
    {
        return $this->steps;
    }
}