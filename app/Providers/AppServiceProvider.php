<?php

namespace App\Providers;

use App\Repositories\TranslationRepository;
use App\Repositories\TranslationRepositoryInterface;
use App\Services\TranslationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TranslationRepositoryInterface::class,
            TranslationRepository::class
        );
        $this->app->bind(
            TranslationService::class,
            TranslationService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
