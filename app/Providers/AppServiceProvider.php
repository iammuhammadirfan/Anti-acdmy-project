<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\View;
use App\Models\Setting;

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
        if ($this->app->environment('production'))
            { 
                \URL::forceScheme('https');
            }
        Schema::defaultStringLength(191);

        try {
            if (!$this->app->runningInConsole() && Schema::hasTable('settings')) {
                app(\App\Services\NotificationService::class)->configureDynamicSmtp();

                View::composer('*', function ($view) {
                    $view->with('globalSettings', Setting::pluck('value', 'key')->toArray());
                });
            }
        } catch (\Throwable $e) {
            // Silently ignore if DB connection is not initialized
        }
    }
}
