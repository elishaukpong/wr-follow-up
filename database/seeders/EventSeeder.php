<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Sunday Worship Service',
                'description' => '<p>Join us for a powerful time of worship and teaching. Experience the presence of God as we lift our voices in praise and dive deep into His word.</p>',
                'date' => now()->addDays(7),
                'time' => '10:00:00',
                'location' => 'Main Sanctuary, 123 Worship Ave',
                'capacity' => 500,
                'status' => 'published',
            ],
            [
                'title' => 'Youth Night - Fire & Glory',
                'description' => '<p>An electrifying night of worship designed for our youth. Bring your friends for an unforgettable encounter with God through modern worship and powerful messages.</p>',
                'date' => now()->addDays(14),
                'time' => '18:30:00',
                'location' => 'Youth Center, Building B',
                'capacity' => 200,
                'status' => 'published',
            ],
            [
                'title' => 'Prayer & Intercession Night',
                'description' => '<p>A special night dedicated to prayer and intercession. Come with your burdens and leave with peace as we seek God together.</p>',
                'date' => now()->addDays(21),
                'time' => '19:00:00',
                'location' => 'Prayer Hall, Building A',
                'capacity' => 150,
                'status' => 'published',
            ],
            [
                'title' => 'Worship Conference 2025',
                'description' => '<p>Three days of intensive worship, teaching, and impartation. Join worshippers from around the region for this annual gathering.</p>',
                'date' => now()->addDays(45),
                'time' => '09:00:00',
                'location' => 'Convention Center Downtown',
                'capacity' => 1000,
                'status' => 'published',
            ],
            [
                'title' => 'Family Worship Celebration',
                'description' => '<p>Bring the whole family for a special celebration filled with worship, testimonies, and family activities.</p>',
                'date' => now()->addDays(28),
                'time' => '11:00:00',
                'location' => 'Main Sanctuary, 123 Worship Ave',
                'capacity' => 600,
                'status' => 'published',
            ],
        ];

        foreach ($events as $event) {
            $event['unique_code'] = \Illuminate\Support\Str::random(32);
            \App\Models\Event::create($event);
        }
    }
}
