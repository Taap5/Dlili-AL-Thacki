<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // إجبار المتصفح على استخدام روابط آمنة عند الرفع على ريندر
        if (config('app.env') !== 'local') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        view()->share(
            'categories',
            Schema::hasTable('government_categories')
                ? \App\Models\GovernmentCategory::all()
                : collect()
        );

        view()->share(
            'offer_services',
            Schema::hasTable('offer_services')
                ? \App\Models\OfferService::all()
                : collect()
        );
    }
}
