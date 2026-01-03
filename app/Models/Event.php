<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'location',
        'image',
        'capacity',
        'status',
        'qr_code_path',
        'unique_code',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'status' => EventStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->unique_code)) {
                $event->unique_code = Str::random(32);
            }
        });
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }

    public function getCheckedInCountAttribute(): int
    {
        return $this->attendees()->whereNotNull('checked_in_at')->count();
    }
}
