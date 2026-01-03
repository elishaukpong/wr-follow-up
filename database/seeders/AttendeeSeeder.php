<?php

namespace Database\Seeders;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Database\Seeder;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = Member::all();
        $pastEvents = Event::where('date', '<', today())
            ->orderBy('date')
            ->get();

        if ($members->isEmpty() || $pastEvents->isEmpty()) {
            $this->command->warn('No members or past events found. Please run MemberSeeder and EventSeeder first.');
            return;
        }

        // Define member attendance patterns
        // Regular members (first 8) - attend most events
        // Moderate members (next 4) - attend some events
        // First timers (rest) - only attend 1 event

        $regularMembers = $members->take(8);
        $moderateMembers = $members->skip(8)->take(4);
        $firstTimers = $members->skip(12)->take(6);
        $upcomingBirthdayMembers = $members->skip(18)->take(3);
        $additionalRegulars = $members->skip(21);

        $attendanceCount = 0;

        // Regular members attend 80% of past events
        foreach ($regularMembers as $member) {
            foreach ($pastEvents as $event) {
                if (rand(1, 100) <= 80) {
                    $this->createAttendance($member, $event);
                    $attendanceCount++;
                }
            }
        }

        // Moderate members attend 40% of past events
        foreach ($moderateMembers as $member) {
            $attendedCount = 0;
            foreach ($pastEvents as $event) {
                if (rand(1, 100) <= 40 && $attendedCount < 3) {
                    $this->createAttendance($member, $event);
                    $attendedCount++;
                    $attendanceCount++;
                }
            }
        }

        // First timers only attend the most recent event
        if ($pastEvents->isNotEmpty()) {
            $latestEvent = $pastEvents->last();
            foreach ($firstTimers as $member) {
                $this->createAttendance($member, $latestEvent);
                $attendanceCount++;
            }
        }

        // Upcoming birthday members - regular attenders
        foreach ($upcomingBirthdayMembers as $member) {
            foreach ($pastEvents as $event) {
                if (rand(1, 100) <= 70) {
                    $this->createAttendance($member, $event);
                    $attendanceCount++;
                }
            }
        }

        // Additional regulars attend 60% of events
        foreach ($additionalRegulars as $member) {
            foreach ($pastEvents as $event) {
                if (rand(1, 100) <= 60) {
                    $this->createAttendance($member, $event);
                    $attendanceCount++;
                }
            }
        }

        $this->command->info("Created {$attendanceCount} attendance records.");
    }

    private function createAttendance(Member $member, Event $event): void
    {
        // Check if already exists
        $exists = Attendee::where('member_id', $member->id)
            ->where('event_id', $event->id)
            ->exists();

        if ($exists) {
            return;
        }

        // Create attendance with a check-in time during the event
        $eventDateTime = $event->date->setTimeFromTimeString($event->time ?? '10:00:00');
        $checkInTime = $eventDateTime->copy()->addMinutes(rand(-15, 30));

        Attendee::create([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'name' => $member->name,
            'phone' => $member->phone,
            'checked_in_at' => $checkInTime,
        ]);
    }
}
