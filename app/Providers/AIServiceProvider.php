<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\AI\Contracts\AIProvider;
use App\AI\Providers\OpenAIProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            AIProvider::class,
            OpenAIProvider::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}