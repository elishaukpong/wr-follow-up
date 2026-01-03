<?php

namespace App\Services\Sms;

use App\Services\Sms\Drivers\HttpDriver;
use App\Services\Sms\Drivers\LogDriver;

class SmsService
{
    protected SmsDriverInterface $driver;

    public function __construct()
    {
        $this->driver = $this->resolveDriver();
    }

    /**
     * Resolve the SMS driver based on configuration.
     */
    protected function resolveDriver(): SmsDriverInterface
    {
        $driver = config('notifications.sms.driver', 'log');

        return match ($driver) {
            'http' => new HttpDriver(),
            default => new LogDriver(),
        };
    }

    /**
     * Send an SMS message.
     */
    public function send(string $to, string $message): bool
    {
        return $this->driver->send($to, $message);
    }

    /**
     * Get the current driver instance.
     */
    public function driver(): SmsDriverInterface
    {
        return $this->driver;
    }
}
