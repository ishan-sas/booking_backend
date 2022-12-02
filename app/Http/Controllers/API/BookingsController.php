<?php

namespace App\Http\Controllers\api;

use App\Models\Users;
use App\Models\Stores;
use App\Models\Bookings;
use App\Models\StoreUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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
        $storeId = Stores::select('id')->where('slug', $slug)->first();
        $bookingList = DB::table('bookings')
            ->select('*')
            ->where('stores_id', $storeId['id'])
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
        $user = Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact_no' => $request->contact_no,
            'user_role' => $request->user_role,
            'is_subscribe' => $request->is_subscribe,
        ]);

        // foreach($request->time_slots_id as $timeSlot) {
        //     return $timeSlot;
        //     die();
            $booking = Bookings::create([
                'user_id' => $user['id'],
                'stores_id' => 1,
                'time_slots_id' => json_encode($request->time_slots_id),
                'no_of_kids' => $request->no_of_kids,
                'booking_date' => $request->booking_date,
                'ftroom' => $request->ftroom,
                'extra_note' => $request->extra_note,
            ]);
        //}

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
                // if($user->user_role === 0) {
                //     $token = $user->createToken($user->email.'_SuperUsertoken', ['server:superUser'])->plainTextToken;
                // }
                // if($user->user_role === 1) {
                //     $token = $user->createToken($user->email.'_AdminUsertoken', ['server:adminUser'])->plainTextToken;
                // }
                // else {
                    $token = $user->createToken($user->email.'_Token')->plainTextToken;
                //}



                $storeProfile = StoreUsers::where('user_id', $user->id)->first();
                if(isset($storeProfile)) {
                    $storeId = $storeProfile->id;
                } 
                else {
                    $storeId = 0;
                }
            }
        }

        
        //foreach($request->time_slots_id as $timeSlot) {
            $booking = Bookings::create([
                'user_id' => $user->id,
                'stores_id' => 1,
                'time_slots_id' => json_encode($request->time_slots_id),
                'no_of_kids' => $request->no_of_kids,
                'booking_date' => $request->booking_date,
                'ftroom' => $request->ftroom,
                'extra_note' => $request->extra_note,
            ]);
        //}
     

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
