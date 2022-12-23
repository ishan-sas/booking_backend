<?php

namespace App\Http\Controllers\API;

use App\Models\Users;
use App\Models\Stores;
use App\Mail\NewBooking;
use App\Mail\ClientConfirmation;
use App\Models\Bookings;
use App\Models\TimeSlots;
use App\Models\StoreUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BookingsController extends Controller
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

    public function appointmentsByStore($slug) {
        $bookingList = DB::table('bookings')
            ->select('*')
            ->where('stores_id', $slug)
            ->get();

        return response()->json([
            'status'=>200,
            'get_data'=>$bookingList,
        ]);
    }

    
    public function getBookingsByDate($slug, $filterDate) {
        $bookingList = DB::table('bookings')
            ->select('*')
            ->where('stores_id', $slug)
            ->where('booking_date', $filterDate)
            ->get();

        return response()->json([
            'status'=>200,
            'get_data'=>$bookingList,
        ]);
    }


    public function bookingsByString($slug, $filterString) {
        $bookingList = DB::table('bookings')
            ->select('*')
            ->where('stores_id', $slug)
            ->where('id', $filterString)
            ->get();

        return response()->json([
            'status'=>200,
            'get_data'=>$bookingList,
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
    public function regWithBooking(Request $request)
    {
        $isSubscribe = 0;
        if(json_encode($request->is_subscribe) == true) {
            $isSubscribe = 1; 
        }
        $user = Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact_no' => $request->contact_no,
            'user_role' => $request->user_role,
            'is_subscribe' => $isSubscribe,
        ]);

        $storeData = Stores::select('id', 'store_name', 'email')->where('slug', $request->stores_id)->first();
        $timeSlotData = [];
        foreach ($request->time_slots_id as $timeSlot) {
            $timeLabel = TimeSlots::select('time_slot')->where('id', $timeSlot)->first();
            array_push($timeSlotData, $timeLabel);
        }

        $bookingData = [
            'user_id' => $user->id,
            'stores_id' =>  $storeData->id,
            'time_slots_id' => json_encode($request->time_slots_id),
            'no_of_kids' => $request->no_of_kids,
            'booking_date' => $request->booking_date,
            'ftroom' => $request->ftroom,
            'extra_note' => $request->extra_note,
        ];

        $booking = Bookings::create($bookingData);

        $bookingData['customer_email'] = $request->email;
        $bookingData['store_email'] = $storeData->email;
        $bookingData['time_slots'] = json_encode($timeSlotData);
        $bookingData['store_name'] = $storeData->store_name; 

        // Mail::to($storeData->email)->send(new NewBooking($bookingData));
        // Mail::to($request->email)->send(new ClientConfirmation($bookingData));

        return response()->json([
            'status' => 200,
            'get_data' => $booking['id'],
            'message' => 'Registered successfully.',
        ]);
    }


    public function loginWithBooking(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);
        if($validator->fails()){
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        }
        else {
            $user = Users::where([
                ['email', '=', $request->email],
                ['status', '=', 1]
            ])->first();

            if(! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'No. Invalid credentials'
                ]);
            }
            else {
                $token = $user->createToken($user->email.'_Token')->plainTextToken;

                $storeProfile = StoreUsers::where('user_id', $user->id)->first();
                if(isset($storeProfile)) {
                    $storeId = $storeProfile->id;
                } 
                else {
                    $storeId = 0;
                }
            }
        }

        $storeData = Stores::select('id', 'store_name', 'email')->where('slug', $request->stores_id)->first();
        $timeSlotData = [];
        foreach ($request->time_slots_id as $timeSlot) {
            $timeLabel = TimeSlots::select('time_slot')->where('id', $timeSlot)->first();
            array_push($timeSlotData, $timeLabel);
        }

        $bookingData = [
            'user_id' => $user->id,
            'stores_id' =>  $storeData->id,
            'time_slots_id' => json_encode($request->time_slots_id),
            'no_of_kids' => $request->no_of_kids,
            'booking_date' => $request->booking_date,
            'ftroom' => $request->ftroom,
            'extra_note' => $request->extra_note,
        ];

        $booking = Bookings::create($bookingData);

        $bookingData['customer_email'] = $request->email;
        $bookingData['store_email'] = $storeData->email;
        $bookingData['time_slots'] = json_encode($timeSlotData);
        $bookingData['store_name'] = $storeData->store_name; 

        // Mail::to($storeData->email)->send(new NewBooking($bookingData));
        // Mail::to($request->email)->send(new ClientConfirmation($bookingData));

        return response()->json([
            'status' => 200,
            'username' => $user->name,
            'profileId' => $user->id,
            'token' => $token,
            'usertype' => $user->user_role,
            'storeId' => $storeId,
            'get_data' => $booking['id'],
            'message' => 'Booking successfully.',
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
