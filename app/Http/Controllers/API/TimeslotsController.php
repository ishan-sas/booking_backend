<?php

namespace App\Http\Controllers\api;

use App\Models\Stores;
use App\Models\TimeSlots;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Bookings;
class TimeslotsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug, $requestDate, $noofchild)
    {
        $requestDay = Carbon::createFromFormat('d.m.Y', $requestDate)->format('l');
        $stores_id = Stores::select('id')
            ->where('slug', $slug)
            ->first();

        $timeSlotes = TimeSlots::where('stores_id', $stores_id->id)
            ->where('day', $requestDay)
            ->get();
        
            foreach($timeSlotes as $key=>$val)
            {
               $id =(array)((string)$val->id);


               $book = Bookings::where('booking_date', $requestDate)->whereJsonContains('time_slots_id',$id)->get();
               $timeSlotes[$key]['kids'] = $book;
            }


            foreach($timeSlotes as $key=>$val)
            {   
                $kids = 0;

                foreach($val->kids as $kid)
                {
                    $kids = $kids + $kid->no_of_kids;
                }
                $timeSlotes[$key]['kids_count'] = $kids;
            }
        return response()->json([
            'status' => 200,
            'timeslots' => $timeSlotes,
        ]);      
    }


    public function getTimeLabel() {
        $getData = TimeSlots::all();
        return response()->json([
            'status'=>200,
            'get_data'=>$getData
        ]);
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $store_id)
    {
        $validator = Validator::make($request->all(),
            [
                'stores_id' => '',
            ]
        );
        if($validator->fails()){
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        }
        else {
            $user_id = Auth::user()->id;

            foreach($request->mondayTimeSlots as $row) {
                $slot = explode("-", $row['time_slot']);
                $startTime = (float)$slot[0];
                if($startTime < 8 ) { 
                    $session = 'PM'; 
                }
                else { 
                    $session = 'AM'; 
                }
                if($row['id'] == NULL) {
                    $timeSlots = TimeSlots::create([
                        'user_id' => $user_id,
                        'stores_id' => $row['stores_id'],
                        'day' => $row['day'],
                        'time_slot' => $row['time_slot'],
                        'session' => $session
                    ]);
                }
                else {
                    $timeSlotData = TimeSlots::find($row['id']);
                    if($timeSlotData) {
                        $timeSlotData->user_id = $user_id;
                        $timeSlotData->stores_id = $row['stores_id'];
                        $timeSlotData->day = $row['day'];
                        $timeSlotData->time_slot = $row['time_slot'];
                        $timeSlotData->session = $session;

                        $timeSlotData->save();
                    }
                }
            }

            foreach($request->tuesdayTimeSlots as $row) {
                $slot = explode("-", $row['time_slot']);
                $startTime = (float)$slot[0];
                if($startTime < 8 ) { 
                    $session = 'PM'; 
                }
                else { 
                    $session = 'AM'; 
                }
                if($row['id'] == NULL) {
                    $timeSlots = TimeSlots::create([
                        'user_id' => $user_id,
                        'stores_id' => $row['stores_id'],
                        'day' => $row['day'],
                        'time_slot' => $row['time_slot'],
                        'session' => $session
                    ]);
                }
                else {
                    $timeSlotData = TimeSlots::find($row['id']);
                    if($timeSlotData) {
                        $timeSlotData->user_id = $user_id;
                        $timeSlotData->stores_id = $row['stores_id'];
                        $timeSlotData->day = $row['day'];
                        $timeSlotData->time_slot = $row['time_slot'];
                        $timeSlotData->session = $session;

                        $timeSlotData->save();
                    }
                }
            }

            foreach($request->wednesdayTimeSlots as $row) {
                $slot = explode("-", $row['time_slot']);
                $startTime = (float)$slot[0];
                if($startTime < 8 ) { 
                    $session = 'PM'; 
                }
                else { 
                    $session = 'AM'; 
                }
                if($row['id'] == NULL) {
                    $timeSlots = TimeSlots::create([
                        'user_id' => $user_id,
                        'stores_id' => $row['stores_id'],
                        'day' => $row['day'],
                        'time_slot' => $row['time_slot'],
                        'session' => $session
                    ]);
                }
                else {
                    $timeSlotData = TimeSlots::find($row['id']);
                    if($timeSlotData) {
                        $timeSlotData->user_id = $user_id;
                        $timeSlotData->stores_id = $row['stores_id'];
                        $timeSlotData->day = $row['day'];
                        $timeSlotData->time_slot = $row['time_slot'];
                        $timeSlotData->session = $session;

                        $timeSlotData->save();
                    }
                }
            }

            foreach($request->thursdayTimeSlots as $row) {
                $slot = explode("-", $row['time_slot']);
                $startTime = (float)$slot[0];
                if($startTime < 8 ) { 
                    $session = 'PM'; 
                }
                else { 
                    $session = 'AM'; 
                }
                if($row['id'] == NULL) {
                    $timeSlots = TimeSlots::create([
                        'user_id' => $user_id,
                        'stores_id' => $row['stores_id'],
                        'day' => $row['day'],
                        'time_slot' => $row['time_slot'],
                        'session' => $session,
                    ]);
                }
                else {
                    $timeSlotData = TimeSlots::find($row['id']);
                    if($timeSlotData) {
                        $timeSlotData->user_id = $user_id;
                        $timeSlotData->stores_id = $row['stores_id'];
                        $timeSlotData->day = $row['day'];
                        $timeSlotData->time_slot = $row['time_slot'];
                        $timeSlotData->session = $session;

                        $timeSlotData->save();
                    }
                }
            }

            foreach($request->fridayTimeSlots as $row) {
                $slot = explode("-", $row['time_slot']);
                $startTime = (float)$slot[0];
                if($startTime < 8 ) { 
                    $session = 'PM'; 
                }
                else { 
                    $session = 'AM'; 
                }
                if($row['id'] == NULL) {
                    $timeSlots = TimeSlots::create([
                        'user_id' => $user_id,
                        'stores_id' => $row['stores_id'],
                        'day' => $row['day'],
                        'time_slot' => $row['time_slot'],
                        'session' => $session
                    ]);
                }
                else {
                    $timeSlotData = TimeSlots::find($row['id']);
                    if($timeSlotData) {
                        $timeSlotData->user_id = $user_id;
                        $timeSlotData->stores_id = $row['stores_id'];
                        $timeSlotData->day = $row['day'];
                        $timeSlotData->time_slot = $row['time_slot'];
                        $timeSlotData->session = $session;

                        $timeSlotData->save();
                    }
                }
            }
            return response()->json([
                'status' => 200,
                'message' => 'Time sheet successfully added.',
            ]);


        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataList = TimeSlots::where('stores_id', $id)->get();  
        return response()->json([
            'status' => 200,
            'get_data' => $dataList,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
