<?php

namespace App\Http\Controllers\api;

use App\Models\Stores;
use App\Models\Bookings;
use App\Models\TimeSlots;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StoresUnavailableSlots;
use Illuminate\Support\Facades\Validator;

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

        $timeSlotesIDs = TimeSlots::select('id')
            ->where('stores_id', $stores_id->id)
            ->where('day', $requestDay)
            ->get();    
        $unavaiSlotes = StoresUnavailableSlots::select('time_slot_id')
            ->where('stores_id', $stores_id->id)
            ->where('relate_date', $requestDate)
            ->first(); 
            
        if(isset($unavaiSlotes)){
            $unavai_slots = json_decode($unavaiSlotes['time_slot_id'], true);  
            foreach($timeSlotesIDs as $key=>$val) {
                foreach ($unavai_slots as $slot) { 
                    if($val->id == $slot) {
                        unset($timeSlotes[$key]);
                    }
                }
            }
        }    

        foreach($timeSlotes as $key=>$val) {
            $id =(array)((string)$val->id);

            $book = Bookings::where('booking_date', $requestDate)
                ->where('status','!=', 0)
                ->whereJsonContains('time_slots_id', $id)
                ->get();
            $timeSlotes[$key]['kids'] = $book;
        }

        foreach($timeSlotes as $key=>$val) {   
            $kids = 0;

            foreach($val->kids as $kid) {
                $kids = $kids + $kid->no_of_kids;
            }
            $timeSlotes[$key]['kids_count'] = $kids;
        }
        $t = [];
        foreach($timeSlotes as $tt) {
            $t[] = $tt;
        }
        return response()->json([
            'status' => 200,
            'timeslots' => $t,
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
                $session = NULL;
                $slot = explode("-", $row['time_slot']);
                $startTime = (float)$slot[0];
                if($startTime > 12 ) { 
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
                $tusession = NULL;
                $tuslot = explode("-", $row['time_slot']);
                $tustartTime = (float)$tuslot[0];
                if($tustartTime > 12 ) { 
                    $tusession = 'PM'; 
                }
                else { 
                    $tusession = 'AM'; 
                }
                if($row['id'] == NULL) {
                    $tutimeSlots = TimeSlots::create([
                        'user_id' => $user_id,
                        'stores_id' => $row['stores_id'],
                        'day' => $row['day'],
                        'time_slot' => $row['time_slot'],
                        'session' => $tusession
                    ]);
                }
                else {
                    $tutimeSlotData = TimeSlots::find($row['id']);
                    if($tutimeSlotData) {
                        $tutimeSlotData->user_id = $user_id;
                        $tutimeSlotData->stores_id = $row['stores_id'];
                        $tutimeSlotData->day = $row['day'];
                        $tutimeSlotData->time_slot = $row['time_slot'];
                        $tutimeSlotData->session = $tusession;

                        $tutimeSlotData->save();
                    }
                }
            }

            foreach($request->wednesdayTimeSlots as $row) {
                $slot = explode("-", $row['time_slot']);
                $wdstartTime = (float)$slot[0];
                if($wdstartTime > 12 ) { 
                    $wdsession = 'PM'; 
                }
                else { 
                    $wdsession = 'AM'; 
                }
                if($row['id'] == NULL) {
                    $timeSlots = TimeSlots::create([
                        'user_id' => $user_id,
                        'stores_id' => $row['stores_id'],
                        'day' => $row['day'],
                        'time_slot' => $row['time_slot'],
                        'session' => $wdsession
                    ]);
                }
                else {
                    $timeSlotData = TimeSlots::find($row['id']);
                    if($timeSlotData) {
                        $timeSlotData->user_id = $user_id;
                        $timeSlotData->stores_id = $row['stores_id'];
                        $timeSlotData->day = $row['day'];
                        $timeSlotData->time_slot = $row['time_slot'];
                        $timeSlotData->session = $wdsession;

                        $timeSlotData->save();
                    }
                }
            }

            foreach($request->thursdayTimeSlots as $row) {
                $slot = explode("-", $row['time_slot']);
                $thstartTime = (float)$slot[0];
                if($thstartTime > 12 ) { 
                    $thsession = 'PM'; 
                }
                else { 
                    $thsession = 'AM'; 
                }
                if($row['id'] == NULL) {
                    $timeSlots = TimeSlots::create([
                        'user_id' => $user_id,
                        'stores_id' => $row['stores_id'],
                        'day' => $row['day'],
                        'time_slot' => $row['time_slot'],
                        'session' => $thsession,
                    ]);
                }
                else {
                    $timeSlotData = TimeSlots::find($row['id']);
                    if($timeSlotData) {
                        $timeSlotData->user_id = $user_id;
                        $timeSlotData->stores_id = $row['stores_id'];
                        $timeSlotData->day = $row['day'];
                        $timeSlotData->time_slot = $row['time_slot'];
                        $timeSlotData->session = $thsession;

                        $timeSlotData->save();
                    }
                }
            }

            foreach($request->fridayTimeSlots as $row) {
                $slot = explode("-", $row['time_slot']);
                $frstartTime = (float)$slot[0];
                if($frstartTime > 12 ) { 
                    $frsession = 'PM'; 
                }
                else { 
                    $frsession = 'AM'; 
                }
                if($row['id'] == NULL) {
                    $timeSlots = TimeSlots::create([
                        'user_id' => $user_id,
                        'stores_id' => $row['stores_id'],
                        'day' => $row['day'],
                        'time_slot' => $row['time_slot'],
                        'session' => $frsession
                    ]);
                }
                else {
                    $timeSlotData = TimeSlots::find($row['id']);
                    if($timeSlotData) {
                        $timeSlotData->user_id = $user_id;
                        $timeSlotData->stores_id = $row['stores_id'];
                        $timeSlotData->day = $row['day'];
                        $timeSlotData->time_slot = $row['time_slot'];
                        $timeSlotData->session = $frsession;

                        $timeSlotData->save();
                    }
                }
            }

            
            foreach($request->saturdayTimeSlots as $row) {
                $slot = explode("-", $row['time_slot']);
                $ststartTime = (float)$slot[0];
                if($ststartTime > 12 ) { 
                    $stsession = 'PM'; 
                }
                else { 
                    $stsession = 'AM'; 
                }
                if($row['id'] == NULL) {
                    $timeSlots = TimeSlots::create([
                        'user_id' => $user_id,
                        'stores_id' => $row['stores_id'],
                        'day' => $row['day'],
                        'time_slot' => $row['time_slot'],
                        'session' => $stsession
                    ]);
                }
                else {
                    $timeSlotData = TimeSlots::find($row['id']);
                    if($timeSlotData) {
                        $timeSlotData->user_id = $user_id;
                        $timeSlotData->stores_id = $row['stores_id'];
                        $timeSlotData->day = $row['day'];
                        $timeSlotData->time_slot = $row['time_slot'];
                        $timeSlotData->session = $stsession;

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
        $deleted = DB::table('time_slots')->where('id', $id)->delete();
        return response()->json([
            'status' => 200,
            'messagen' => 'Record deleted successfully.',
        ]);
    }
}
