<?php

namespace App\Imports;

use App\Enums\Gender;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\Member;
use App\Models\Zone;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;

class MembersImport implements ToModel, WithHeadingRow, SkipsOnError, SkipsOnFailure, WithValidation
{
    use SkipsErrors, SkipsFailures;

    protected Event $event;
    protected int $importedCount = 0;
    protected int $skippedCount = 0;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function model(array $row): ?Member
    {
        $phone = $this->normalizePhone($row['phone'] ?? '');

        if (empty($row['name']) || empty($phone)) {
            $this->skippedCount++;
            return null;
        }

        $zone = null;
        $customLocation = null;
        if (!empty($row['zone'])) {
            $zone = Zone::where('name', $row['zone'])->first();
            if (!$zone) {
                $customLocation = $row['zone'];
            }
        }

        $gender = null;
        if (!empty($row['gender'])) {
            $gender = Gender::tryFrom(strtolower($row['gender']));
        }

        $member = Member::where('phone', $phone)->first();

        if (!$member) {
            $member = Member::create([
                'name' => $row['name'],
                'phone' => $phone,
                'email' => $row['email'] ?? null,
                'gender' => $gender,
                'zone_id' => $zone?->id,
                'custom_location' => $customLocation,
                'birthday' => !empty($row['birthday']) ? $row['birthday'] : null,
            ]);
        }

        $existingAttendee = Attendee::where('event_id', $this->event->id)
            ->where('member_id', $member->id)
            ->exists();

        if ($existingAttendee) {
            $this->skippedCount++;
            return null;
        }

        Attendee::create([
            'event_id' => $this->event->id,
            'member_id' => $member->id,
            'name' => $member->name,
            'phone' => $member->phone,
            'checked_in_at' => now(),
        ]);

        $this->importedCount++;

        return null;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'phone' => 'required',
        ];
    }

    protected function normalizePhone(string $phone): string
    {
        return preg_replace('/[\s\-\(\)]/', '', $phone);
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }
}
