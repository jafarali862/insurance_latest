<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\CompanyLogo;
use Illuminate\Support\Facades\Schema;


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
    public function boot()
    {
         Paginator::useBootstrap();
        //  View::share('companyLogo', CompanyLogo::latest()->first());
          if (Schema::hasTable('company_logos')) {
        View::share('companyLogo', CompanyLogo::latest()->first());
    }
    }
}
