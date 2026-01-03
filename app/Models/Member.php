<?php

namespace App\Models;

use App\Enums\FollowUpStatus;
use App\Enums\Gender;
use App\Enums\ReferralSource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Member extends Model
{
    use Notifiable;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'gender',
        'birthday',
        'zone_id',
        'custom_location',
        'follow_up_status',
        'followed_up_at',
        'referral_source',
    ];

    protected $casts = [
        'gender' => Gender::class,
        'birthday' => 'date',
        'follow_up_status' => FollowUpStatus::class,
        'followed_up_at' => 'datetime',
        'referral_source' => ReferralSource::class,
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class)->latest();
    }

    public function getLocationAttribute(): ?string
    {
        if ($this->zone) {
            return $this->zone->name;
        }

        return $this->custom_location;
    }

    public function getVisitCountAttribute(): int
    {
        return $this->attendances()->whereNotNull('checked_in_at')->count();
    }

    public function getVisitStatusAttribute(): string
    {
        $count = $this->visit_count;

        return match (true) {
            $count === 0 => 'New',
            $count === 1 => 'First Timer',
            $count === 2 => 'Second Timer',
            $count === 3 => 'Third Timer',
            default => 'Regular',
        };
    }

    public function isFirstTimer(): bool
    {
        return $this->visit_count <= 1;
    }

    public function needsFollowUp(): bool
    {
        return $this->isFirstTimer() && empty($this->follow_up_status);
    }

    public function getLastAttendedAtAttribute(): ?\Carbon\Carbon
    {
        return $this->attendances()
            ->whereNotNull('checked_in_at')
            ->latest('checked_in_at')
            ->value('checked_in_at');
    }

    public function isBirthdayThisMonth(): bool
    {
        if (!$this->birthday) {
            return false;
        }

        return $this->birthday->month === now()->month;
    }
}
