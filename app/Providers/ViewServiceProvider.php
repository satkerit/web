<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CompanyInfo;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Use fresh query instead of cached to ensure all attributes are available
            $companyInfo = CompanyInfo::first();
            $view->with('companyInfo', $companyInfo);
        });
    }
}
