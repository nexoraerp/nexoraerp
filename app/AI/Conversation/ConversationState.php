<?php

namespace App\AI\Conversation;

class ConversationState
{
    protected string $key = 'ai_conversation';

    public function get(): array
    {
        return session()->get($this->key, []);
    }

    public function put(array $state): void
    {
        session()->put($this->key, $state);
    }

    public function clear(): void
    {
        session()->forget($this->key);
    }

    /**
     * Aktif workflow var mı?
     */
    public function has(): bool
    {
        return !empty($this->get());
    }

    /**
     * Aktif agent sınıfı
     */
    public function agent(): ?string
    {
        return $this->get()['agent'] ?? null;
    }
}