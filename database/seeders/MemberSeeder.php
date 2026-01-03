<?php

namespace Database\Seeders;

use App\Enums\FollowUpStatus;
use App\Enums\Gender;
use App\Enums\ReferralSource;
use App\Models\Member;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = Zone::all();

        if ($zones->isEmpty()) {
            $this->command->warn('No zones found. Please run ZoneSeeder first.');
            return;
        }

        $members = [
            // Regular members (many attendances)
            [
                'name' => 'Adebayo Okonkwo',
                'phone' => '08012345678',
                'email' => 'adebayo.okonkwo@email.com',
                'gender' => Gender::Male,
                'birthday' => '1990-03-15',
                'referral_source' => ReferralSource::Friend,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
            [
                'name' => 'Chidinma Eze',
                'phone' => '08023456789',
                'email' => 'chidinma.eze@email.com',
                'gender' => Gender::Female,
                'birthday' => '1988-07-22',
                'referral_source' => ReferralSource::Family,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
            [
                'name' => 'Oluwaseun Adeleke',
                'phone' => '08034567890',
                'email' => 'seun.adeleke@email.com',
                'gender' => Gender::Male,
                'birthday' => '1995-01-08',
                'referral_source' => ReferralSource::SocialMedia,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
            [
                'name' => 'Ngozi Nnamdi',
                'phone' => '08045678901',
                'email' => 'ngozi.nnamdi@email.com',
                'gender' => Gender::Female,
                'birthday' => '1992-11-30',
                'referral_source' => ReferralSource::Website,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
            [
                'name' => 'Emeka Obi',
                'phone' => '08056789012',
                'email' => 'emeka.obi@email.com',
                'gender' => Gender::Male,
                'birthday' => '1985-05-18',
                'referral_source' => ReferralSource::Friend,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
            [
                'name' => 'Funke Adeyemi',
                'phone' => '08067890123',
                'email' => 'funke.adeyemi@email.com',
                'gender' => Gender::Female,
                'birthday' => '1993-09-12',
                'referral_source' => ReferralSource::Family,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
            [
                'name' => 'Chukwuemeka Ikenna',
                'phone' => '08078901234',
                'email' => 'chukwuemeka.ikenna@email.com',
                'gender' => Gender::Male,
                'birthday' => '1991-04-25',
                'referral_source' => ReferralSource::Flyer,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
            [
                'name' => 'Yetunde Bakare',
                'phone' => '08089012345',
                'email' => 'yetunde.bakare@email.com',
                'gender' => Gender::Female,
                'birthday' => '1989-12-03',
                'referral_source' => ReferralSource::SocialMedia,
                'follow_up_status' => FollowUpStatus::Connected,
            ],

            // Second/Third timers (moderate attendance)
            [
                'name' => 'Tunde Afolabi',
                'phone' => '08090123456',
                'email' => 'tunde.afolabi@email.com',
                'gender' => Gender::Male,
                'birthday' => '1994-06-20',
                'referral_source' => ReferralSource::Friend,
                'follow_up_status' => FollowUpStatus::Contacted,
            ],
            [
                'name' => 'Amara Chidi',
                'phone' => '08101234567',
                'email' => 'amara.chidi@email.com',
                'gender' => Gender::Female,
                'birthday' => '1996-02-14',
                'referral_source' => ReferralSource::PassingBy,
                'follow_up_status' => FollowUpStatus::Contacted,
            ],
            [
                'name' => 'Ifeanyi Okoro',
                'phone' => '08112345678',
                'email' => 'ifeanyi.okoro@email.com',
                'gender' => Gender::Male,
                'birthday' => '1987-08-07',
                'referral_source' => ReferralSource::Website,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
            [
                'name' => 'Folake Ogundimu',
                'phone' => '08123456789',
                'email' => 'folake.ogundimu@email.com',
                'gender' => Gender::Female,
                'birthday' => '1998-10-28',
                'referral_source' => ReferralSource::SocialMedia,
                'follow_up_status' => FollowUpStatus::Contacted,
            ],

            // First timers (1 attendance, need follow-up)
            [
                'name' => 'Kelechi Nwankwo',
                'phone' => '08134567890',
                'email' => 'kelechi.nwankwo@email.com',
                'gender' => Gender::Male,
                'birthday' => '2000-01-15',
                'referral_source' => ReferralSource::Friend,
                'follow_up_status' => FollowUpStatus::Pending,
            ],
            [
                'name' => 'Blessing Uche',
                'phone' => '08145678901',
                'email' => 'blessing.uche@email.com',
                'gender' => Gender::Female,
                'birthday' => '1999-03-22',
                'referral_source' => ReferralSource::SocialMedia,
                'follow_up_status' => FollowUpStatus::Pending,
            ],
            [
                'name' => 'Obinna Agu',
                'phone' => '08156789012',
                'email' => 'obinna.agu@email.com',
                'gender' => Gender::Male,
                'birthday' => '1997-07-11',
                'referral_source' => ReferralSource::PassingBy,
                'follow_up_status' => FollowUpStatus::Contacted,
            ],
            [
                'name' => 'Grace Omotosho',
                'phone' => '08167890123',
                'email' => 'grace.omotosho@email.com',
                'gender' => Gender::Female,
                'birthday' => '2001-05-05',
                'referral_source' => ReferralSource::Flyer,
                'follow_up_status' => FollowUpStatus::Pending,
            ],
            [
                'name' => 'Emmanuel Okafor',
                'phone' => '08178901234',
                'email' => 'emmanuel.okafor@email.com',
                'gender' => Gender::Male,
                'birthday' => '1986-09-18',
                'referral_source' => ReferralSource::Family,
                'follow_up_status' => FollowUpStatus::NoResponse,
            ],
            [
                'name' => 'Aisha Mohammed',
                'phone' => '08189012345',
                'email' => 'aisha.mohammed@email.com',
                'gender' => Gender::Female,
                'birthday' => '1995-11-27',
                'referral_source' => ReferralSource::Other,
                'follow_up_status' => FollowUpStatus::Pending,
            ],

            // Members with upcoming birthdays (for widget testing)
            [
                'name' => 'David Olaniyan',
                'phone' => '08190123456',
                'email' => 'david.olaniyan@email.com',
                'gender' => Gender::Male,
                'birthday' => now()->addDays(2)->format('Y-m-d'),
                'referral_source' => ReferralSource::Friend,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
            [
                'name' => 'Precious Igwe',
                'phone' => '08201234567',
                'email' => 'precious.igwe@email.com',
                'gender' => Gender::Female,
                'birthday' => now()->addDays(5)->format('Y-m-d'),
                'referral_source' => ReferralSource::Family,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
            [
                'name' => 'Samuel Adewale',
                'phone' => '08212345678',
                'email' => 'samuel.adewale@email.com',
                'gender' => Gender::Male,
                'birthday' => now()->format('Y-m-d'),
                'referral_source' => ReferralSource::SocialMedia,
                'follow_up_status' => FollowUpStatus::Connected,
            ],

            // Additional members for variety
            [
                'name' => 'Jennifer Nwosu',
                'phone' => '08223456789',
                'email' => 'jennifer.nwosu@email.com',
                'gender' => Gender::Female,
                'birthday' => '1990-04-17',
                'referral_source' => ReferralSource::Website,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
            [
                'name' => 'Peter Uzoma',
                'phone' => '08234567890',
                'email' => 'peter.uzoma@email.com',
                'gender' => Gender::Male,
                'birthday' => '1983-08-29',
                'referral_source' => ReferralSource::Friend,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
            [
                'name' => 'Mary Okonkwo',
                'phone' => '08245678901',
                'email' => 'mary.okonkwo@email.com',
                'gender' => Gender::Female,
                'birthday' => '1991-12-10',
                'referral_source' => ReferralSource::Family,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
            [
                'name' => 'Joshua Bankole',
                'phone' => '08256789012',
                'email' => 'joshua.bankole@email.com',
                'gender' => Gender::Male,
                'birthday' => '1994-02-28',
                'referral_source' => ReferralSource::SocialMedia,
                'follow_up_status' => FollowUpStatus::Connected,
            ],
        ];

        foreach ($members as $index => $memberData) {
            // Assign zones in a round-robin fashion
            $memberData['zone_id'] = $zones[$index % $zones->count()]->id;
            $memberData['birthday'] = $memberData['birthday'] ?? null;

            Member::firstOrCreate(
                ['phone' => $memberData['phone']],
                $memberData
            );
        }

        $this->command->info('Created ' . count($members) . ' members.');
    }
}
