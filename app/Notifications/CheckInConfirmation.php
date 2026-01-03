<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\Member;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CheckInConfirmation extends Notification implements ShouldQueue
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

        if (config('notifications.types.check_in_confirmation')) {
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
            ->subject('Check-in Confirmed - ' . $this->event->title)
            ->greeting('Hello ' . $this->member->name . '!')
            ->line('You\'ve successfully checked in to ' . $this->event->title . '.')
            ->line('Event Date: ' . $this->event->date->format('l, F j, Y'))
            ->line('Time: ' . $this->event->time->format('g:i A'))
            ->line('Thank you for joining us!')
            ->salutation('Blessings, ' . config('app.name'));
    }

    /**
     * Send SMS notification.
     */
    public function toSms(object $notifiable): void
    {
        $message = "Hi {$this->member->name}, you're checked in for {$this->event->title}! "
            . "See you at {$this->event->time->format('g:i A')}. - " . config('app.name');

        app(SmsService::class)->send($notifiable->phone, $message);
    }
}
