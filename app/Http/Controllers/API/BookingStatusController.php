<?php

namespace App\Http\Controllers\api;

use App\Models\Bookings;
use Illuminate\Http\Request;
use App\Models\BookingStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'status' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        }
        else {
            $user_id = Auth::user()->id;
            $status = BookingStatus::create([
                'user_id' => $user_id,
                'booking_id' => $request->booking_id,
                'extra_note' => $request->extra_note,
                'status' => $request->status,
            ]);

            $bookings = Bookings::find($request->booking_id);
            if($bookings) {
                $bookings->status = $request->status;

                $bookings->save();
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Status successfully updated.',
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
        $statusList = BookingStatus::where('booking_id', $id)->get(); 
        if($statusList) {
            return response()->json([
                'status' => 200,
                'get_data' => $statusList,
            ]);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'No ID found.!',
            ]);
        }
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
