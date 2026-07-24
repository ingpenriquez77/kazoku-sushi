<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator; // Importamos el Paginador

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
        // Forzar HTTPS en entorno de producción (Render)
        if ($this->app->environment('production') || config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Forzar a Laravel a usar Bootstrap 5 para los botones de paginación
        Paginator::useBootstrapFive();
    }
}
