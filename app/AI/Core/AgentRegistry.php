<?php

namespace App\AI\Core;

use ReflectionClass;
use Illuminate\Support\Facades\File;

class AgentRegistry
{
    protected array $agents = [];

    public function __construct()
    {
        $this->discover();
    }

    protected function discover(): void
    {
        $path = app_path('AI/Agents');

        foreach (File::allFiles($path) as $file) {

            $class = 'App\\AI\\Agents\\' .
                str_replace('.php', '', $file->getFilename());

            if (!class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            $attributes = $reflection->getAttributes(Intent::class);

            if (empty($attributes)) {
                continue;
            }

            /** @var Intent $intent */
            $intent = $attributes[0]->newInstance();

            $this->agents[$intent->name] = $class;
        }
    }

    public function resolve(string $intent): ?string
    {
        return $this->agents[$intent] ?? null;
    }

    public function all(): array
    {
        return $this->agents;
    }
}