<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

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
        // En Laravel 10/11 el método correcto se escribe con letras: useBootstrapFour()
        Paginator::useBootstrapFour();

        // Forzar HTTPS en entorno de producción (Render)
        if ($this->app->environment('production') || config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}