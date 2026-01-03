<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = [
            [
                'name' => 'Lekki',
                'description' => 'Members residing in Lekki, Victoria Island, and Ikoyi areas',
                'is_active' => true,
            ],
            [
                'name' => 'Ikeja',
                'description' => 'Members residing in Ikeja, Maryland, and Opebi areas',
                'is_active' => true,
            ],
            [
                'name' => 'Yaba',
                'description' => 'Members residing in Yaba, Surulere, and Ebute Metta areas',
                'is_active' => true,
            ],
            [
                'name' => 'Ajah',
                'description' => 'Members residing in Ajah, Sangotedo, and Badore areas',
                'is_active' => true,
            ],
            [
                'name' => 'Mainland',
                'description' => 'Members residing in other Lagos Mainland areas',
                'is_active' => true,
            ],
            [
                'name' => 'Island',
                'description' => 'Members residing in other Lagos Island areas',
                'is_active' => true,
            ],
            [
                'name' => 'Outside Lagos',
                'description' => 'Members residing outside Lagos State',
                'is_active' => true,
            ],
        ];

        foreach ($zones as $zone) {
            Zone::firstOrCreate(
                ['name' => $zone['name']],
                $zone
            );
        }
    }
}
