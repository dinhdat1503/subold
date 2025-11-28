<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('social_services') && Schema::hasTable('services') && Schema::hasTable('activity')) {
            View::composer('client.*', function ($view) {
                $view->with('navbarSocials', \App\Models\SocialService::where('status', true)->get());
                $view->with('activities', \App\Models\Activity::orderBy('id', 'DESC')->limit(10)->get());
            });
        }
        if (Schema::hasTable('site_settings')) {
            View::share('siteSettings', \App\Models\SiteSetting::pluck('setting_value', 'setting_key')->toArray());
        }
    }
}
