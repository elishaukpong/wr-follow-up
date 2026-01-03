<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $members = Member::all();

        if ($users->isEmpty() || $members->isEmpty()) {
            $this->command->warn('No users or members found. Please run UserSeeder and MemberSeeder first.');
            return;
        }

        $noteTemplates = [
            // Follow-up notes
            'Called to welcome. Very friendly, expressed interest in joining a connect group.',
            'Sent WhatsApp message. Acknowledged and asked about service times.',
            'Met at church on Sunday. Looking for a church closer to home.',
            'Phone unreachable after 3 attempts. Will try again next week.',
            'Had a great conversation. Works nearby and found us through a colleague.',
            'Interested in the music ministry. Has experience singing in choir.',
            'Looking for a youth group for their teenage children.',
            'Recently relocated to the area. Coming from a church in Abuja.',
            'Wants to know more about water baptism.',
            'Requested prayer for job situation.',

            // General notes
            'Volunteered at the last community outreach.',
            'Celebrating wedding anniversary next month.',
            'Starting a new job, needs prayer for transition.',
            'Recovering from surgery, doing well.',
            'Has been bringing friends to church regularly.',
            'Asked about becoming a member officially.',
            'Interested in serving in the children\'s ministry.',
            'Suggested we start a prayer group in their neighborhood.',
            'Shared testimony of answered prayer.',
            'Going through a challenging season, needs pastoral care.',
        ];

        $noteCount = 0;

        // Add notes to some members (especially first timers and those with follow-up status)
        foreach ($members->take(15) as $member) {
            $numberOfNotes = rand(1, 3);

            for ($i = 0; $i < $numberOfNotes; $i++) {
                $user = $users->random();
                $note = $noteTemplates[array_rand($noteTemplates)];

                Note::create([
                    'member_id' => $member->id,
                    'user_id' => $user->id,
                    'content' => $note,
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(0, 10)),
                ]);

                $noteCount++;
            }
        }

        $this->command->info("Created {$noteCount} notes.");
    }
}
