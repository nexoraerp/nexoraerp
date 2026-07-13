<?php

namespace App\AI\Contracts;

interface AIProvider
{
    public function chat(array $messages): array;

    public function embeddings(string $text): array;

    public function image(string $prompt): string;
}