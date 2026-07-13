<?php

namespace App\AI\Core;

use App\AI\Contracts\AgentContract;
use App\AI\Conversation\ConversationState;

abstract class BaseAgent implements AgentContract
{
    public function __construct(
        protected ConversationState $state,
        protected WorkflowEngine $workflow
    ) {
    }

    /**
     * Agent Workflow
     */
    abstract public function workflow(): Workflow;

    /**
     * Workflow tamamlandığında çalışır.
     */
    abstract public function finish(array $data): array;

    /**
     * Agent giriş noktası
     */
    public function execute(string $message): array
    {
        try {

            return $this->workflow->handle(

                workflow: $this->workflow(),

                agent: static::class,

                message: $message,

                onFinish: fn (array $data) => $this->finish($data)

            );

        } catch (\Throwable $e) {

            dd([
                'agent'   => static::class,
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

        }
    }
}