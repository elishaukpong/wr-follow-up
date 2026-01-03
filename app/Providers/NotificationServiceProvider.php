<?php

namespace App\Providers;

use App\Services\Sms\SmsChannel;
use App\Services\Sms\SmsService;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SmsService::class, function ($app) {
            return new SmsService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register the SMS channel
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('sms', function ($app) {
                return new SmsChannel($app->make(SmsService::class));
            });
        });
    }
}
