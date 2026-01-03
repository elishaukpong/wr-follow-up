<?php

namespace App\Services\Sms\Drivers;

use App\Services\Sms\SmsDriverInterface;
use Illuminate\Support\Facades\Log;

class LogDriver implements SmsDriverInterface
{
    /**
     * Send an SMS message (logs it instead of actually sending).
     */
    public function send(string $to, string $message): bool
    {
        Log::channel('daily')->info('SMS Notification', [
            'to' => $to,
            'message' => $message,
            'driver' => 'log',
        ]);

        return true;
    }
}
