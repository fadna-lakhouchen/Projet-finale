<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;


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
        Blade::component('admin.components.button', 'admin.button');
        Blade::component('admin.components.stat-card', 'admin.stat-card');
        Blade::component('admin.components.table', 'admin.table');
    }
}
