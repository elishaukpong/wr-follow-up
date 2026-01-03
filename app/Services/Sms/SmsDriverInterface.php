<?php

namespace App\Services\Sms;

interface SmsDriverInterface
{
    /**
     * Send an SMS message.
     *
     * @param string $to The recipient phone number
     * @param string $message The message content
     * @return bool Whether the message was sent successfully
     */
    public function send(string $to, string $message): bool;
}
