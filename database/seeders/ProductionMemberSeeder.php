<?php

namespace Database\Seeders;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Member;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class ProductionMemberSeeder extends Seeder
{
    public function run(): void
    {
        // Create events
        $januaryEvent = Event::firstOrCreate(
            ['title' => 'Worship Realm, January Edition'],
            [
                'date' => '2026-01-18',
                'time' => '17:00',
                'location' => 'Uyo',
                'status' => 'published',
            ]
        );

        $februaryEvent = Event::firstOrCreate(
            ['title' => 'Worship Realm, February Edition'],
            [
                'date' => '2026-02-15',
                'time' => '17:00',
                'location' => 'Uyo',
                'status' => 'published',
            ]
        );

        $this->command->info("Events created: January (#{$januaryEvent->id}), February (#{$februaryEvent->id})");

        // Import members from CSV
        $csvPath = base_path('csv/WR February Attendance - February 2026.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found: {$csvPath}");
            return;
        }

        $handle = fopen($csvPath, 'r');
        $header = fgetcsv($handle);

        $zoneCache = [];
        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $name = trim($row[0] ?? '');
            $phone = $this->normalizePhone(trim($row[1] ?? ''));
            $zoneName = trim($row[2] ?? '');

            if (empty($name) || empty($phone)) {
                $skipped++;
                continue;
            }

            // Resolve zone
            $zoneId = null;
            if (!empty($zoneName)) {
                if (!isset($zoneCache[$zoneName])) {
                    $zoneCache[$zoneName] = Zone::firstOrCreate(
                        ['name' => $zoneName],
                        ['is_active' => true]
                    );
                }
                $zoneId = $zoneCache[$zoneName]->id;
            }

            $member = Member::updateOrCreate(
                ['phone' => $phone],
                [
                    'name' => $name,
                    'zone_id' => $zoneId,
                ]
            );

            // Link member to February event as attendee
            Attendee::firstOrCreate(
                [
                    'event_id' => $februaryEvent->id,
                    'member_id' => $member->id,
                ],
                [
                    'name' => $member->name,
                    'phone' => $member->phone,
                    'checked_in_at' => $februaryEvent->date,
                ]
            );

            $imported++;
        }

        fclose($handle);

        $this->command->info("Members imported: {$imported}, Skipped: {$skipped}");
        $this->command->info("Zones created/found: " . count($zoneCache));
        $this->command->info("All imported members linked to February Edition as attendees.");
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/[\s\-]/', '', $phone);
    }
}
