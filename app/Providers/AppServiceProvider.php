<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
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
        // Establece la longitud de cadena predeterminada a 191 caracteres.
        // 191 * 4 bytes/carácter (en utf8mb4) = 764 bytes, lo cual es seguro bajo el límite de 1000.
        Schema::defaultStringLength(191);
    }
}
