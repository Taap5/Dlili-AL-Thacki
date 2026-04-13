<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
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
