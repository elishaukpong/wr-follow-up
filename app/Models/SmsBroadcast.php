<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsBroadcast extends Model
{
    protected $fillable = [
        'message',
        'recipient_type',
        'zone_id',
        'member_id',
        'recipient_count',
        'bulk_message_id',
        'status',
        'sent_by',
    ];

    protected $casts = [
        'recipient_count' => 'integer',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
