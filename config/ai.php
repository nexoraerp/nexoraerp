<?php

return [

    'default' => env('AI_PROVIDER', 'openai'),

    'providers' => [

        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('AI_MODEL', 'gpt-4.1'),
        ],

    ],

    'temperature' => env('AI_TEMPERATURE', 0.7),

    'max_tokens' => env('AI_MAX_TOKENS', 2000),

];