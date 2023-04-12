<?php

namespace App\Providers;

use App\Models\Passport\Client;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\ClientRepository;

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
        Vite::macro('images', fn(string $asset) => $this->asset("resources/assets/images/{$asset}"));
    }
}
