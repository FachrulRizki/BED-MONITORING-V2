<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['name' => 'Ruang Mawar',   'male_capacity' => 10, 'female_capacity' => 10],
            ['name' => 'Ruang Melati',  'male_capacity' => 8,  'female_capacity' => 12],
            ['name' => 'Ruang Anggrek', 'male_capacity' => 15, 'female_capacity' => 5],
            ['name' => 'Ruang ICU',     'male_capacity' => 6,  'female_capacity' => 6],
            ['name' => 'Ruang Dahlia',  'male_capacity' => 20, 'female_capacity' => 20],
        ];

        foreach ($rooms as $room) {
            DB::table('rooms')->updateOrInsert(
                ['name' => $room['name']],
                array_merge($room, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
