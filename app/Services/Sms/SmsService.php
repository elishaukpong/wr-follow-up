<?php

namespace App\Services\Sms;

use App\Services\Sms\Drivers\HttpDriver;
use App\Services\Sms\Drivers\JusibeDriver;
use App\Services\Sms\Drivers\LogDriver;

class SmsService
{
    protected SmsDriverInterface $driver;

    public function __construct()
    {
        $this->driver = $this->resolveDriver();
    }

    protected function resolveDriver(): SmsDriverInterface
    {
        $driver = config('notifications.sms.driver', 'log');

        return match ($driver) {
            'http' => new HttpDriver(),
            'jusibe' => new JusibeDriver(),
            default => new LogDriver(),
        };
    }

    public function send(string $to, string $message): bool
    {
        return $this->driver->send($to, $message);
    }

    public function sendBulk(array $numbers, string $message): ?string
    {
        if ($this->driver instanceof JusibeDriver) {
            return $this->driver->sendBulk($numbers, $message);
        }

        // Fallback: send individually for non-bulk drivers
        $sent = 0;
        foreach ($numbers as $number) {
            if ($this->driver->send($number, $message)) {
                $sent++;
            }
        }

        return $sent > 0 ? "sent-{$sent}" : null;
    }

    public function getCredits(): ?int
    {
        if ($this->driver instanceof JusibeDriver) {
            return $this->driver->getCredits();
        }

        return null;
    }

    public function driver(): SmsDriverInterface
    {
        return $this->driver;
    }
}
