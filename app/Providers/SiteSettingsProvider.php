<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SiteSettingsProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $apiUrl = config('services.api_url');
            $siteSettingsResponse = Http::get($apiUrl . 'api/site-settings');
            $siteSettings = $siteSettingsResponse->json()['data']['site_settings'];

            $view->with(['siteSettings' => $siteSettings]);
        });
    }
}
