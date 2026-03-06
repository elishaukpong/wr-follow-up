<?php

namespace App\Services\Sms\Drivers;

use App\Services\Sms\SmsDriverInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JusibeDriver implements SmsDriverInterface
{
    protected string $baseUrl = 'https://jusibe.com/smsapi';
    protected string $publicKey;
    protected string $accessToken;
    protected string $senderId;

    public function __construct()
    {
        $config = config('notifications.sms.jusibe');
        $this->publicKey = $config['public_key'] ?? '';
        $this->accessToken = $config['access_token'] ?? '';
        $this->senderId = $config['sender_id'] ?? config('notifications.sms.from', 'WorshipRealm');
    }

    public function send(string $to, string $message): bool
    {
        try {
            $response = $this->request()->post("{$this->baseUrl}/send_sms", [
                'to' => $to,
                'from' => $this->senderId,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('SMS sent via Jusibe', ['to' => $to]);
                return true;
            }

            Log::error('Jusibe SMS failed', [
                'to' => $to,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Jusibe SMS error', ['to' => $to, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendBulk(array $numbers, string $message): ?string
    {
        if (empty($numbers)) {
            return null;
        }

        try {
            $response = $this->request()->post("{$this->baseUrl}/bulk/send_sms", [
                'to' => implode(',', $numbers),
                'from' => $this->senderId,
                'message' => $message,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $bulkMessageId = $data['bulk_message_id'] ?? null;

                Log::info('Bulk SMS sent via Jusibe', [
                    'count' => count($numbers),
                    'bulk_message_id' => $bulkMessageId,
                ]);

                return $bulkMessageId;
            }

            Log::error('Jusibe bulk SMS failed', [
                'count' => count($numbers),
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Jusibe bulk SMS error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function getCredits(): ?int
    {
        try {
            $response = $this->request()->post("{$this->baseUrl}/get_credits");

            if ($response->successful()) {
                return (int) ($response->json('sms_credits') ?? 0);
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Jusibe credits check error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    protected function request(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withBasicAuth($this->publicKey, $this->accessToken)->timeout(30);
    }
}
