<?php

namespace App\AI\Core;

use App\AI\Conversation\AgentResponse;
use App\AI\Conversation\ConversationState;

class WorkflowEngine
{
    public function __construct(
        protected ConversationState $state
    ) {}

    public function handle(
        Workflow $workflow,
        string $agent,
        string $message,
        callable $onFinish
    ): array {

        $conversation = $this->state->get();

        /*
        |--------------------------------------------------------------------------
        | Yeni konuşma
        |--------------------------------------------------------------------------
        */

        if (empty($conversation)) {

            $first = $workflow->first();

            $this->state->put([

                'agent' => $agent,

                'step' => $first->key,

                'data' => [],

            ]);

            return AgentResponse::ask(

                $first->key,

                $first->question

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Mevcut adım
        |--------------------------------------------------------------------------
        */

        $current = $workflow->current(
            $conversation['step']
        );

        if (!$current) {

            $this->state->clear();

            return AgentResponse::error(
                'Workflow bozuldu.'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Veriyi kaydet
        |--------------------------------------------------------------------------
        */

        if ($current->field) {

            $conversation['data'][$current->field] = trim($message);

        }

        /*
        |--------------------------------------------------------------------------
        | Sonraki adım
        |--------------------------------------------------------------------------
        */

        $next = $workflow->next(
            $current->key
        );

        if (!$next) {

            $result = $onFinish(
                $conversation['data']
            );

            $this->state->clear();

            return $result;

        }

        $conversation['step'] = $next->key;

        $this->state->put(
            $conversation
        );

        return AgentResponse::ask(

            $next->key,

            $next->question

        );
    }
}