<?php

namespace App\Http\Controllers\api;

use App\Models\TimeSlots;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StoresUnavailableSlots;
use Illuminate\Support\Facades\Validator;

class StoreUnavailableSlotsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($storeId, $requestDate)
    {
        $requestDay = Carbon::createFromFormat('d.m.Y', $requestDate)->format('l');
        $timeSlotes = TimeSlots::where('stores_id', $storeId)
            ->where('day', $requestDay)
            ->get();

        return response()->json([
            'status' => 200,
            'get_data' => $timeSlotes,
        ]);      
    }

    public function getUnavailableSlots($storeId, $requestDate)
    {
        $unavtimeSlotes = StoresUnavailableSlots::where('stores_id', $storeId)
            ->where('relate_date', $requestDate)
            ->first();
        return response()->json([
            'status' => 200,
            'unavailable_slots' => $unavtimeSlotes,
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'relate_date' => '',
        ]);

        if($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        }
        else { 
            $user_id = Auth::id(); 
            $rowId = StoresUnavailableSlots::select('id')
                ->where('stores_id', $request->storeId)
                ->where('relate_date', $request->selectedDate)
                ->first();

            if(empty($rowId->id)) {
                $unavaiSlots = StoresUnavailableSlots::create([
                    'user_id' => $user_id,
                    'stores_id' => $request->storeId,
                    'relate_date' =>  $request->selectedDate,
                    'time_slot_id' => json_encode($request->clickedSlots),
                ]);
            }
            else {
                $slots = StoresUnavailableSlots::find($rowId->id);
                if($slots) {
                    $slots->user_id = $user_id;
                    $slots->stores_id = $request->input('storeId');
                    $slots->relate_date = $request->input('selectedDate');
                    $slots->time_slot_id = json_encode($request->input('clickedSlots'));
    
                    $slots->save();
                }
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Unavailable time slots are successfully updated.',
        ]);
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
        //
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
