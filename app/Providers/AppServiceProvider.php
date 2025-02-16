<?php

namespace App\Providers;

use App\Interfaces\ArticleInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\UserPreferenceInterface;
use App\Repository\ArticleRepository;
use App\Repository\UserPreferenceRepository;
use App\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ArticleInterface::class, ArticleRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(UserPreferenceInterface::class, UserPreferenceRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
