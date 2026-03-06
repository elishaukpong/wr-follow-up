<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ProductionMemberSeeder extends Seeder
{
    public function run(): void
    {
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

            Member::updateOrCreate(
                ['phone' => $phone],
                [
                    'name' => $name,
                    'zone_id' => $zoneId,
                ]
            );

            $imported++;
        }

        fclose($handle);

        $this->command->info("Imported: {$imported}, Skipped: {$skipped}");
        $this->command->info("Zones created/found: " . count($zoneCache));
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/[\s\-]/', '', $phone);
    }
}
