<?php

namespace App\AI\Core;

class ResponseBuilder
{
    public function success(
        string $message,
        array $data = []
    ): array {
        return [

            'success' => true,

            'message' => $message,

            'data' => $data,

        ];
    }

    public function error(
        string $message
    ): array {
        return [

            'success' => false,

            'message' => $message,

            'data' => [],

        ];
    }

    public function ask(
        string $step,
        string $question,
        array $data = []
    ): array {
        return [

            'success' => true,

            'action' => 'ask',

            'step' => $step,

            'message' => $question,

            'data' => $data,

        ];
    }
}