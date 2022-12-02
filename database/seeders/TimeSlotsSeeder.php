<?php

namespace Database\Seeders;

use App\Models\TimeSlots;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TimeSlotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $slotArr = ['08.00 - 08.30', '08.30 - 09.00', '09.00 - 09.30', '09.30 - 10.00', '10.00 - 10.30', '10.30 - 11.30', '11.30 - 12.00', '12.00 - 12.30',
                    '12.30 - 13.00', '13.00 - 13.30', '13.30 - 14.00', '14.00 - 14.30', '14.30 - 15.00', '15.00 - 15.30', '15.30 - 16.00'];
        for($x=0; $x<5; $x++){
            if($x==0){$day = 'Monday';}elseif($x==1){$day = 'Tuesday';}elseif($x==2){$day = 'Wednesday';}elseif($x==2){$day = 'Thursday';}else{$day = 'Friday';}                    
            foreach($slotArr as $key => $value){
                $timeSlots = [
                    [
                        'user_id' => 1,
                        'stores_id' => 1,
                        'day' => $day, 
                        'time_slot' => $value
                    ]
                ];
                TimeSlots::insert($timeSlots);
            }
        }

        $slotArr = ['08.00 - 08.30', '08.30 - 09.00', '09.00 - 09.30', '09.30 - 10.00', '10.00 - 10.30', '10.30 - 11.30', '11.30 - 12.00', '12.00 - 12.30',
                    '12.30 - 13.00', '13.00 - 13.30', '13.30 - 14.00', '14.00 - 14.30', '14.30 - 15.00'];
        for($x=0; $x<5; $x++){
            if($x==0){$day = 'Monday';}elseif($x==1){$day = 'Tuesday';}elseif($x==2){$day = 'Wednesday';}elseif($x==2){$day = 'Thursday';}else{$day = 'Friday';}                    
            foreach($slotArr as $key => $value){
                $timeSlots = [
                    [
                        'user_id' => 1,
                        'stores_id' => 2,
                        'day' => $day, 
                        'time_slot' => $value
                    ]
                ];
                TimeSlots::insert($timeSlots);
            }
        }

    }
}