<?php

namespace App\Notifications;

use App\Models\Member;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BirthdayWish extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Member $member
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        $channels = [];

        if (config('notifications.types.birthday_wish')) {
            $channels[] = 'sms';

            if (config('notifications.email.enabled') && $notifiable->email) {
                $channels[] = 'mail';
            }
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Happy Birthday from ' . config('app.name') . '!')
            ->greeting('Happy Birthday, ' . $this->member->name . '!')
            ->line('Wishing you a wonderful birthday filled with joy and blessings.')
            ->line('We\'re grateful to have you as part of our community.')
            ->line('May this new year of your life bring you peace, happiness, and all the desires of your heart.')
            ->salutation('With love, ' . config('app.name'));
    }

    /**
     * Send SMS notification.
     */
    public function toSms(object $notifiable): void
    {
        $message = "Happy Birthday, {$this->member->name}! "
            . "Wishing you a blessed day filled with joy. "
            . "We're grateful to have you! - " . config('app.name');

        app(SmsService::class)->send($notifiable->phone, $message);
    }
}
