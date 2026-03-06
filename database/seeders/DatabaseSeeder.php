<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Base data (no dependencies)
            UserSeeder::class,
            ProductionMemberSeeder::class,
//            ZoneSeeder::class,

            // Members depend on Zones
//            MemberSeeder::class,

            // Events are independent
//            EventSeeder::class,

            // Attendees depend on Members and Events
//            AttendeeSeeder::class,

            // Notes depend on Members and Users
//            NoteSeeder::class,
        ]);
    }
}
