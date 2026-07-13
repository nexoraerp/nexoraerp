<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\AI\Contracts\AIProvider;
use App\AI\Providers\OpenAIProvider;

class AIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            AIProvider::class,
            OpenAIProvider::class
        );
    }

    public function boot(): void
    {
        //
    }
}