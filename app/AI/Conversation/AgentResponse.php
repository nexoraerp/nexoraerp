<?php

namespace App\AI\Conversation;

class AgentResponse
{
    public static function ask(
        string $step,
        string $message,
        array $data = []
    ): array {

        return [

            'success' => true,

            'action' => 'ask',

            'step' => $step,

            'message' => $message,

            'data' => $data

        ];
    }

    public static function save(
        string $message,
        array $data = []
    ): array {

        return [

            'success' => true,

            'action' => 'save',

            'message' => $message,

            'data' => $data

        ];
    }

    public static function error(
        string $message
    ): array {

        return [

            'success' => false,

            'action' => 'error',

            'message' => $message

        ];
    }
}