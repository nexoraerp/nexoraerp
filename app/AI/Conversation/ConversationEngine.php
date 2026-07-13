<?php

namespace App\AI\Conversation;

class ConversationEngine
{
    public function __construct(
        protected ConversationState $state
    ) {}

    public function start(string $agent): void
    {
        $this->state->put([
            'agent' => $agent,
            'step' => 'customer_name',
            'data' => [],
        ]);
    }

    public function current(): array
    {
        return $this->state->get();
    }

    public function finish(): void
    {
        $this->state->clear();
    }
}