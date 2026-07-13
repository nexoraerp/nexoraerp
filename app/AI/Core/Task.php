<?php

namespace App\AI\Core;

final readonly class Task
{
    public function __construct(

        /*
        |--------------------------------------------------------------------------
        | Hangi Tool?
        |--------------------------------------------------------------------------
        */

        public string $agent,

        /*
        |--------------------------------------------------------------------------
        | Hangi Action?
        |--------------------------------------------------------------------------
        */

        public string $action,

        /*
        |--------------------------------------------------------------------------
        | Parametreler
        |--------------------------------------------------------------------------
        */

        public array $payload = [],

        /*
        |--------------------------------------------------------------------------
        | Öncelik
        |--------------------------------------------------------------------------
        */

        public int $priority = 0,

        /*
        |--------------------------------------------------------------------------
        | Zorunlu mu?
        |--------------------------------------------------------------------------
        */

        public bool $required = true,

        /*
        |--------------------------------------------------------------------------
        | AI Güven Skoru
        |--------------------------------------------------------------------------
        */

        public float $confidence = 1.0,

        /*
        |--------------------------------------------------------------------------
        | Kullanıcı Onayı Gerekli mi?
        |--------------------------------------------------------------------------
        */

        public bool $requiresConfirmation = false,

        /*
        |--------------------------------------------------------------------------
        | Context'e hangi isimle yazılacak?
        |--------------------------------------------------------------------------
        */

        public ?string $resultKey = null,

        /*
        |--------------------------------------------------------------------------
        | Ek Bilgiler
        |--------------------------------------------------------------------------
        */

        public array $metadata = []

    ) {
    }

    public function key(): string
    {
        return "{$this->agent}.{$this->action}";
    }

    public function payload(
        string $key,
        mixed $default = null
    ): mixed {

        return $this->payload[$key] ?? $default;

    }

    public function meta(
        string $key,
        mixed $default = null
    ): mixed {

        return $this->metadata[$key] ?? $default;

    }

    public function toArray(): array
    {
        return [

            'agent' => $this->agent,

            'action' => $this->action,

            'payload' => $this->payload,

            'priority' => $this->priority,

            'required' => $this->required,

            'confidence' => $this->confidence,

            'requiresConfirmation' => $this->requiresConfirmation,

            'resultKey' => $this->resultKey,

            'metadata' => $this->metadata,

        ];
    }
}