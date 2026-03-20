<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\GovernmentCategory;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

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
        View::share('categories', GovernmentCategory::all());




        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();

        // أو تخصيص التصميم
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-5');
    }
}
