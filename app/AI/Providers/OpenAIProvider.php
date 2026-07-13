<?php

namespace App\AI\Providers;

use App\AI\Contracts\AIProvider;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIProvider implements AIProvider
{
    public function chat(array $messages): array
    {
        $response = OpenAI::chat()->create([
            'model' => config('ai.providers.openai.model'),
            'messages' => $messages,
            'temperature' => config('ai.temperature'),
            'max_tokens' => config('ai.max_tokens'),
        ]);

        return [
            'content' => $response->choices[0]->message->content,
        ];
    }

    public function embeddings(string $text): array
    {
        return [];
    }

    public function image(string $prompt): string
    {
        return '';
    }
}