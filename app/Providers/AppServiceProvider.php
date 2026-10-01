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

        if ($this->app->environment('production')) { 
            \URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);

        try {
            if (!$this->app->runningInConsole() && Schema::hasTable('settings')) {
                app(\App\Services\NotificationService::class)->configureDynamicSmtp();

                $allSettings = Setting::pluck('value', 'key')->toArray();
                if (!empty($allSettings['academy_name'])) {
                    config(['app.name' => $allSettings['academy_name']]);
                }

                View::composer('*', function ($view) use ($allSettings) {
                    $view->with('globalSettings', $allSettings);
                });

                View::composer('layouts.admin', function ($view) {
                    if (Schema::hasTable('appointments')) {
                        try {
                            $clearedAt = Setting::get('admin_notifications_cleared_at') 
                                ?: session()->get('admin_notifications_cleared_at');

                            $raw = Setting::get('admin_read_booking_ids', '[]');
                            $readIds = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : []);
                            if (!is_array($readIds)) {
                                $readIds = [];
                            }
                            $sessionRead = session()->get('admin_read_booking_ids', []);
                            if (is_array($sessionRead)) {
                                $readIds = array_unique(array_merge($readIds, $sessionRead));
                            }

                            $unreadQuery = \App\Models\Appointment::query()
                                ->when($clearedAt, function ($q) use ($clearedAt) {
                                    $q->where('created_at', '>', $clearedAt);
                                })
                                ->when(!empty($readIds), function ($q) use ($readIds) {
                                    $q->whereNotIn('id', $readIds);
                                })
                                ->latest('id');

                            $recentBookings = $unreadQuery->take(10)->get();
                            $unreadCount = $recentBookings->count();

                            $view->with('adminRecentBookings', $recentBookings);
                            $view->with('adminUnreadBookingsCount', $unreadCount);
                        } catch (\Throwable $e) {
                            $view->with('adminRecentBookings', collect());
                            $view->with('adminUnreadBookingsCount', 0);
                        }
                    }
                });
            }
        } catch (\Throwable $e) {
            // Silently ignore if DB connection is not initialized
        }
    }
}
