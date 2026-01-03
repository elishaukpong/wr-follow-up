<?php

namespace App\Services\Sms;

use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function __construct(
        protected SmsService $smsService
    ) {}

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (method_exists($notification, 'toSms')) {
            $notification->toSms($notifiable);
        }
    }
}
