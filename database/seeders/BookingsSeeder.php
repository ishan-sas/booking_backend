<?php

namespace Database\Seeders;

use App\Models\Bookings;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bookings = [
            [
                'user_id' => 1,
                'stores_id' => 1,
                'time_slots_id' => json_encode([1]),
                'no_of_kids' => 1, 
                'booking_date' => '11.11.2022',
                'ftroom' => 2,
                'extra_note' => 'Test'
            ],
            [
                'user_id' => 1,
                'stores_id' => 1,
                'time_slots_id' => json_encode([1]),
                'no_of_kids' => 2, 
                'booking_date' => '11.11.2022',
                'ftroom' => 2,
                'extra_note' => 'Test 2' 
            ]
        ];

        Bookings::insert($bookings);
    }
}
