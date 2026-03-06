<?php

namespace App\Jobs;

use App\Models\Member;
use App\Models\SmsBroadcast;
use App\Services\Sms\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSmsBroadcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public SmsBroadcast $broadcast,
    ) {}

    public function handle(SmsService $smsService): void
    {
        $query = Member::whereNotNull('phone')->where('phone', '!=', '');

        if ($this->broadcast->recipient_type === 'zone' && $this->broadcast->zone_id) {
            $query->where('zone_id', $this->broadcast->zone_id);
        }

        $numbers = $query->pluck('phone')->unique()->values()->all();

        if (empty($numbers)) {
            $this->broadcast->update(['status' => 'failed', 'recipient_count' => 0]);
            Log::warning('SMS broadcast has no recipients', ['broadcast_id' => $this->broadcast->id]);
            return;
        }

        $this->broadcast->update(['recipient_count' => count($numbers)]);

        // Jusibe bulk SMS - chunk into batches of 500
        $allMessageIds = [];
        foreach (array_chunk($numbers, 500) as $chunk) {
            $bulkMessageId = $smsService->sendBulk($chunk, $this->broadcast->message);
            if ($bulkMessageId) {
                $allMessageIds[] = $bulkMessageId;
            }
        }

        if (!empty($allMessageIds)) {
            $this->broadcast->update([
                'status' => 'sent',
                'bulk_message_id' => implode(',', $allMessageIds),
            ]);
        } else {
            $this->broadcast->update(['status' => 'failed']);
        }
    }
}
