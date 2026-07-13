<?php

namespace App\AI\Core;

use Illuminate\Support\Facades\File;
use ReflectionClass;

class ToolRegistry
{
    /**
     * @var array<string,string>
     */
    protected array $tools = [];

    public function __construct()
    {
        $this->discover();
    }

    protected function discover(): void
    {
        $path = app_path('AI/Tools');

        if (!is_dir($path)) {
            return;
        }

        foreach (File::allFiles($path) as $file) {

            $class = 'App\\AI\\Tools\\' .
                str_replace('.php', '', $file->getFilename());

            if (!class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            $attributes = $reflection->getAttributes(Tool::class);

            if (empty($attributes)) {
                continue;
            }

            /** @var Tool $agent */
            $agent = $attributes[0]->newInstance();

            $this->tools[$agent->name] = $class;
        }
    }

    public function resolve(string $agent): ?BaseTool
    {
        if (!isset($this->tools[$agent])) {
            return null;
        }

        return app($this->tools[$agent]);
    }

    public function has(string $agent): bool
    {
        return isset($this->tools[$agent]);
    }

    public function all(): array
    {
        return $this->tools;
    }
}
