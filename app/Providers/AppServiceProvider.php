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
        if (Client::all()->count() < 1) {
            app('Laravel\Passport\ClientRepository')->create(
                null,
                config('app.front_name'),
                config('app.front_url') . '/auth/callback',
                null,
                false,
                false,
                false
            );
        }
        // ClientRepository::create()
        Vite::macro('images', fn(string $asset) => $this->asset("resources/assets/images/{$asset}"));
    }
}
