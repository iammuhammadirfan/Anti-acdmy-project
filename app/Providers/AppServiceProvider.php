<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Mail;
use App\Mail\Transport\BrevoApiTransport;

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
        Mail::extend('brevo', function () {
            return new BrevoApiTransport(config('services.brevo.key', ''));
        });
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

                View::composer('layouts.admin', function ($view) {
                    if (Schema::hasTable('appointments')) {
                        $recentBookings = \App\Models\Appointment::query()
                            ->latest('id')
                            ->take(8)
                            ->get();

                        $unreadCount = \App\Models\Appointment::query()
                            ->where(function($q) {
                                $q->where('status', 'pending')
                                  ->orWhere('created_at', '>=', now()->subHours(48));
                            })
                            ->count();

                        $view->with('adminRecentBookings', $recentBookings);
                        $view->with('adminUnreadBookingsCount', $unreadCount);
                    }
                });
            }
        } catch (\Throwable $e) {
            // Silently ignore if DB connection is not initialized
        }
    }
}
