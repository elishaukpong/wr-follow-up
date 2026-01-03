<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\Member;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeFirstTimer extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Event $event,
        protected Member $member
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        $channels = [];

        if (config('notifications.types.welcome_first_timer')) {
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
            ->subject('Welcome to ' . config('app.name') . '!')
            ->greeting('Hello ' . $this->member->name . '!')
            ->line('Thank you for joining us at ' . $this->event->title . '.')
            ->line('We\'re so glad you came! We hope you felt welcomed and we look forward to seeing you again.')
            ->line('If you have any questions, feel free to reach out.')
            ->salutation('Blessings, ' . config('app.name'));
    }

    /**
     * Send SMS notification.
     */
    public function toSms(object $notifiable): void
    {
        $message = "Welcome to " . config('app.name') . ", {$this->member->name}! "
            . "Thank you for joining us at {$this->event->title}. "
            . "We're so glad you came and look forward to seeing you again!";

        app(SmsService::class)->send($notifiable->phone, $message);
    }
}
