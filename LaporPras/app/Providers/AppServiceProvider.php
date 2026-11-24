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
        // Ignore harmless PHP tempnam() notice on Windows when creating view cache
        set_error_handler(function ($severity, $message, $file, $line) {
            if ($severity === E_NOTICE && str_contains($message, 'tempnam()')) {
                return true;
            }
            return false;
        });
    }
}
