<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

class ViewServiceProvider extends ServiceProvider
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
        // Kategorileri tüm view'larda kullanılabilir hale getir
        View::composer('*', function ($view) {
            $apiUrl = config('services.api_url');
            $allCategory = Cache::remember('all_categories', 120, function () use ($apiUrl) {
                $allCategoryResponse = Http::get($apiUrl . 'api/category');
                return $allCategoryResponse->json()['data']['categories'];
            });

            $allAgreement = Cache::remember('all_agreements', 120, function () use ($apiUrl) {
                $allAgreementResponse = Http::get($apiUrl . 'api/agreement');
                return $allAgreementResponse->json()['agreements'];
            });

            $view->with(['allCategory' => $allCategory, 'allAgreement' => $allAgreement]);
        });


        //! Alttaki kodda yukardakinin cacheli versiyonu aşağıdaki benim windows bilgisayarımda performanslı ama mac bilgisayarda veyada canlıda yukarıdaki nasıl bir performans gösterir bilmiyorum.
        /*
        View::composer('*', function ($view) {
            $allCategory = Cache::remember('all_categories', 60, function () {
                $apiUrl = config('services.api_url');
                $allCategoryResponse = Http::get($apiUrl . 'api/category');
                return $allCategoryResponse->json()['categories'];
            });

            $view->with('allCategory', $allCategory);
        });
        */
    }
}
