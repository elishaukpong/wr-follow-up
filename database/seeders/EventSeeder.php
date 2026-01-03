<?php

namespace Database\Seeders;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Past events (for attendance history)
        $pastEvents = [
            [
                'title' => 'Sunday Worship Service',
                'description' => '<p>A powerful time of worship and teaching. We experienced the presence of God as we lifted our voices in praise.</p>',
                'date' => now()->subWeeks(4),
                'time' => '10:00:00',
                'location' => 'Main Sanctuary, 123 Worship Ave',
                'capacity' => 500,
                'status' => EventStatus::Published,
            ],
            [
                'title' => 'Sunday Worship Service',
                'description' => '<p>Weekly Sunday service with powerful worship and an encouraging message from the Word.</p>',
                'date' => now()->subWeeks(3),
                'time' => '10:00:00',
                'location' => 'Main Sanctuary, 123 Worship Ave',
                'capacity' => 500,
                'status' => EventStatus::Published,
            ],
            [
                'title' => 'Midweek Service - Bible Study',
                'description' => '<p>Deep dive into the scriptures as we study the book of Romans together.</p>',
                'date' => now()->subWeeks(2)->addDays(3),
                'time' => '18:30:00',
                'location' => 'Fellowship Hall, Building A',
                'capacity' => 200,
                'status' => EventStatus::Published,
            ],
            [
                'title' => 'Sunday Worship Service',
                'description' => '<p>Join us for praise, worship, and the Word of God. All are welcome!</p>',
                'date' => now()->subWeeks(2),
                'time' => '10:00:00',
                'location' => 'Main Sanctuary, 123 Worship Ave',
                'capacity' => 500,
                'status' => EventStatus::Published,
            ],
            [
                'title' => 'Youth Night - Ablaze',
                'description' => '<p>An energetic night of worship for the youth. Music, fellowship, and a relevant message.</p>',
                'date' => now()->subWeeks(1)->addDays(5),
                'time' => '18:00:00',
                'location' => 'Youth Center, Building B',
                'capacity' => 150,
                'status' => EventStatus::Published,
            ],
            [
                'title' => 'Sunday Worship Service',
                'description' => '<p>Our weekly gathering for worship and the Word. Come expecting a move of God!</p>',
                'date' => now()->subWeeks(1),
                'time' => '10:00:00',
                'location' => 'Main Sanctuary, 123 Worship Ave',
                'capacity' => 500,
                'status' => EventStatus::Published,
            ],
        ];

        // Future events
        $futureEvents = [
            [
                'title' => 'Sunday Worship Service',
                'description' => '<p>Join us for a powerful time of worship and teaching. Experience the presence of God as we lift our voices in praise and dive deep into His word.</p>',
                'date' => now()->next('Sunday'),
                'time' => '10:00:00',
                'location' => 'Main Sanctuary, 123 Worship Ave',
                'capacity' => 500,
                'status' => EventStatus::Published,
            ],
            [
                'title' => 'Midweek Service - Prayer Night',
                'description' => '<p>A dedicated time for corporate prayer and intercession. Come with your requests and let us agree together in faith.</p>',
                'date' => now()->next('Wednesday'),
                'time' => '18:30:00',
                'location' => 'Prayer Hall, Building A',
                'capacity' => 150,
                'status' => EventStatus::Published,
            ],
            [
                'title' => 'Youth Night - Fire & Glory',
                'description' => '<p>An electrifying night of worship designed for our youth. Bring your friends for an unforgettable encounter with God through modern worship and powerful messages.</p>',
                'date' => now()->addWeeks(1)->next('Friday'),
                'time' => '18:30:00',
                'location' => 'Youth Center, Building B',
                'capacity' => 200,
                'status' => EventStatus::Published,
            ],
            [
                'title' => 'Prayer & Intercession Night',
                'description' => '<p>A special night dedicated to prayer and intercession. Come with your burdens and leave with peace as we seek God together.</p>',
                'date' => now()->addWeeks(2),
                'time' => '19:00:00',
                'location' => 'Prayer Hall, Building A',
                'capacity' => 150,
                'status' => EventStatus::Published,
            ],
            [
                'title' => 'Worship Conference 2026',
                'description' => '<p>Three days of intensive worship, teaching, and impartation. Join worshippers from around the region for this annual gathering. Guest ministers and worship leaders will be joining us!</p>',
                'date' => now()->addWeeks(6),
                'time' => '09:00:00',
                'location' => 'Convention Center Downtown',
                'capacity' => 1000,
                'status' => EventStatus::Published,
            ],
            [
                'title' => 'Family Worship Celebration',
                'description' => '<p>Bring the whole family for a special celebration filled with worship, testimonies, and family activities. Children\'s ministry will have special programming.</p>',
                'date' => now()->addWeeks(4),
                'time' => '11:00:00',
                'location' => 'Main Sanctuary, 123 Worship Ave',
                'capacity' => 600,
                'status' => EventStatus::Published,
            ],
            [
                'title' => 'New Members Class',
                'description' => '<p>Welcome to the family! This class is designed for those who want to know more about Worship Realm, our vision, values, and how to get connected.</p>',
                'date' => now()->addWeeks(3),
                'time' => '14:00:00',
                'location' => 'Conference Room, Building A',
                'capacity' => 50,
                'status' => EventStatus::Draft,
            ],
        ];

        $allEvents = array_merge($pastEvents, $futureEvents);

        foreach ($allEvents as $eventData) {
            $eventData['unique_code'] = Str::random(32);

            Event::firstOrCreate(
                [
                    'title' => $eventData['title'],
                    'date' => $eventData['date']->format('Y-m-d'),
                ],
                $eventData
            );
        }

        $this->command->info('Created ' . count($allEvents) . ' events.');
    }
}
