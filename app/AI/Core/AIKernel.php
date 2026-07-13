<?php

namespace App\AI\Core;

use App\AI\Contracts\AIProvider;
use App\AI\Conversation\ConversationState;
use App\AI\Router\IntentDetector;

class AIKernel
{
    public function __construct(
        protected AIProvider $provider,
        protected IntentDetector $detector,
        protected AgentRegistry $agents,
        protected ConversationState $state
    ) {
    }

    public function handle(array $messages): array
    {
        /*
        |--------------------------------------------------------------------------
        | Son Mesaj
        |--------------------------------------------------------------------------
        */

        $last = end($messages);

        $message = $last['content'] ?? '';

        /*
        |--------------------------------------------------------------------------
        | Devam Eden Workflow Var mı?
        |--------------------------------------------------------------------------
        */

        if ($this->state->has()) {

            $agent = $this->state->agent();

            if ($agent && class_exists($agent)) {

                return app($agent)->execute($message);

            }

            $this->state->clear();

        }

        /*
        |--------------------------------------------------------------------------
        | Intent Tespit Et
        |--------------------------------------------------------------------------
        */

        $intent = $this->detector->detect($message);

        /*
        |--------------------------------------------------------------------------
        | Agent Bul
        |--------------------------------------------------------------------------
        */

        $agentClass = $this->agents->resolve($intent);

        /*
        |--------------------------------------------------------------------------
        | Agent Çalıştır
        |--------------------------------------------------------------------------
        */

        if ($agentClass !== null) {

            return app($agentClass)->execute($message);

        }

        /*
        |--------------------------------------------------------------------------
        | GPT Provider
        |--------------------------------------------------------------------------
        */

        return $this->provider->chat($messages);
    }
}