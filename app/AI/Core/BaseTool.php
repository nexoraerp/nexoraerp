<?php

namespace App\AI\Core;

abstract class BaseTool
{
    protected object $service;

    /**
     * Task çalıştır
     */
    public function execute(
        Task $task,
        TaskContext $context
    ): mixed {

        $action = $task->action;

        if (!method_exists($this, $action)) {

            throw new \RuntimeException(
                "Tool action [$action] bulunamadı."
            );

        }

        return $this->{$action}(
            $task,
            $context
        );
    }
}