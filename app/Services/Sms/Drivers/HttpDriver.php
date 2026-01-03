<?php

namespace App\Services\Sms\Drivers;

use App\Services\Sms\SmsDriverInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HttpDriver implements SmsDriverInterface
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('notifications.sms.http');
    }

    /**
     * Send an SMS message via HTTP API.
     */
    public function send(string $to, string $message): bool
    {
        if (empty($this->config['url'])) {
            Log::warning('SMS HTTP driver: No API URL configured');
            return false;
        }

        try {
            $fields = $this->config['fields'];
            $from = config('notifications.sms.from');

            $payload = [
                $fields['to'] => $to,
                $fields['message'] => $message,
            ];

            if (!empty($fields['from']) && !empty($from)) {
                $payload[$fields['from']] = $from;
            }

            $response = Http::withHeaders($this->config['headers'] ?? [])
                ->timeout(30)
                ->{strtolower($this->config['method'] ?? 'post')}(
                    $this->config['url'],
                    $payload
                );

            if ($response->successful()) {
                Log::info('SMS sent successfully', [
                    'to' => $to,
                    'driver' => 'http',
                ]);
                return true;
            }

            Log::error('SMS sending failed', [
                'to' => $to,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('SMS sending error', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
