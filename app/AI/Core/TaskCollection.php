<?php

namespace App\AI\Core;

use Countable;
use IteratorAggregate;
use ArrayIterator;

class TaskCollection implements Countable, IteratorAggregate
{
    protected array $tasks = [];

    public function add(Task $task): static
    {
        $this->tasks[] = $task;

        return $this;
    }

    public function first(): ?Task
    {
        return $this->tasks[0] ?? null;
    }

    public function last(): ?Task
    {
        return end($this->tasks) ?: null;
    }

    public function all(): array
    {
        return $this->tasks;
    }

    public function isEmpty(): bool
    {
        return empty($this->tasks);
    }

    public function count(): int
    {
        return count($this->tasks);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->tasks);
    }
}