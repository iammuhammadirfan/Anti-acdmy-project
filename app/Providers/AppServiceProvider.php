<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use App\Mail\Transport\BrevoApiTransport;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\Event;
use Illuminate\Mail\Events\MessageSending;

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

        // Anti-spam: har email ke saath plain-text version aur Reply-To lagao
        Event::listen(MessageSending::class, function (MessageSending $event) {
            $message = $event->message;

            if ($message->getHtmlBody() && !$message->getTextBody()) {
                $html = (string) $message->getHtmlBody();
                $html = preg_replace('#<(style|script|head)\b[^>]*>.*?</\1>#is', '', $html);
                $html = preg_replace('#<br\s*/?>|</(p|div|h[1-6]|tr|li)>#i', "\n", $html);
                $html = preg_replace('#<a\s[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)</a>#is', '$2 ($1)', $html);
                $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $text = trim(preg_replace("/[ \t]+/", ' ', preg_replace("/\n\s*\n\s*\n+/", "\n\n", $text)));
                $message->text($text);
            }

            if (empty($message->getReplyTo())) {
                try {
                    $replyTo = Setting::get('contact_email') ?: Setting::get('admin_email');
                    if (!empty($replyTo) && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
                        $message->replyTo($replyTo);
                    }
                } catch (\Throwable $e) {
                    // ignore
                }
            }
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