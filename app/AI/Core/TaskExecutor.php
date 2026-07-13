<?php

namespace App\AI\Core;

class TaskExecutor
{
    public function __construct(
        protected ToolRegistry $tools
    ) {
    }

    /**
     * Task listesini çalıştır
     */
    public function execute(TaskCollection $tasks): TaskContext
    {
        $context = new TaskContext();

        foreach ($tasks as $task) {

            $tool = $this->tools->resolve(
                $task->agent
            );

            if (!$tool) {

                $context->set(
                    'error',
                    "Tool bulunamadı : {$task->agent}"
                );

                break;
            }

            $tool->execute(
                $task,
                $context
            );

        }

        return $context;
    }
}