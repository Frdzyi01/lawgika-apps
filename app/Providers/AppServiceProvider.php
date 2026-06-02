<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (isset($_COOKIE['lw_language']) && in_array($_COOKIE['lw_language'], ['id', 'en', 'zh'])) {
            app()->setLocale($_COOKIE['lw_language']);
        }
    }
}
