<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

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
            
          	$siteSettings = Cache::remember('_site-settings', 1440, function() use ($apiUrl){
              $siteSettingsResponse = Http::get($apiUrl . 'api/site-settings');
              return $siteSettingsResponse->json()['data']['site_settings'];
            });
          	

            $view->with(['siteSettings' => $siteSettings]);
        });
    }
}
