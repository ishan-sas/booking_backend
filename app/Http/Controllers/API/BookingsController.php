<?php
namespace App\Http\Controllers\API;

use Illuminate\Support\Carbon;
use App\Models\Users;
use App\Models\Stores;
use App\Mail\NewBooking;
use App\Models\Bookings;
use App\Models\TimeSlots;
use App\Models\StoreUsers;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ClientConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
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
        $getCurrentDate = Carbon::now();
        $currentDate = $getCurrentDate->format('d.m.Y'); 

        $bookingList = DB::table('bookings')
            ->select('bookings.id', 'bookings.status', 'bookings.time_slots_id', 'bookings.no_of_kids', 'bookings.booking_date', 'users.name', 'users.email', 'users.contact_no')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.stores_id', $slug)
            //->where('booking_date', '<', $currentDate)
            ->orderBy('bookings.booking_date', 'desc')
            ->get()->toArray();  

            foreach($bookingList as $key=>$booking) {
                $booking = (array)$booking;
                $timeSlots = json_decode($booking['time_slots_id']);
                $booking['slots'] = $this->getTimeSlots($timeSlots);  
                $bookingList[$key] = $booking;    
            }  

        return response()->json([
            'status'=>200,
            'get_data'=>$bookingList,
        ]);
    }

    
    public function getBookingsByDate($slug, $filterDate) {
        $bookingList = DB::table('bookings')
            ->select('bookings.id', 'bookings.status', 'bookings.time_slots_id', 'bookings.no_of_kids', 'bookings.booking_date', 'users.name', 'users.email', 'users.contact_no')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.stores_id', $slug)
            ->where('bookings.booking_date', $filterDate)
            ->get();

        foreach($bookingList as $key=>$booking) {
            $booking = (array)$booking;
            $timeSlots = json_decode($booking['time_slots_id']);
            $booking['slots'] = $this->getTimeSlots($timeSlots);  
            $bookingList[$key] = $booking;    
        }     

        return response()->json([
            'status'=>200,
            'get_data'=>$bookingList,
        ]);
    }


    public function bookingsByString($slug, $filterString) {
        $bookingList = DB::table('bookings')
            ->select('bookings.id', 'bookings.status', 'bookings.time_slots_id', 'bookings.no_of_kids', 'bookings.booking_date', 'users.name', 'users.email', 'users.contact_no')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.stores_id', $slug)
            ->where('bookings.id', $filterString)
            ->get();
        
        foreach($bookingList as $key=>$booking) {
            $booking = (array)$booking;
            $timeSlots = json_decode($booking['time_slots_id']);
            $booking['slots'] = $this->getTimeSlots($timeSlots);  
            $bookingList[$key] = $booking;    
        }     

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

        $CheckEmail = Users::where('email', '=', $request->email)->first();

        if($CheckEmail) {
            return response()->json([
                'status' => 401,
                'message' => 'Email address already used.'
            ]);
        }
        else {
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
    
            $bookingData['booking_id'] = $booking->id;
            $bookingData['customer_email'] = $request->email;
            $bookingData['store_email'] = $storeData->email;
            $bookingData['time_slots'] = $timeSlotData[0]['time_slot'];
            $bookingData['store_name'] = $storeData->store_name; 
    
            // Mail::to($storeData->email)->send(new NewBooking($bookingData));
            // Mail::to($request->email)->send(new ClientConfirmation($bookingData));
    
            return response()->json([
                'status' => 200,
                'get_data' => $booking['id'],
                'message' => 'Registered successfully.',
            ]);
        }
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
                    'message' => 'Invalid credentials'
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

        $bookingData['booking_id'] = $booking->id;
        $bookingData['customer_email'] = $request->email;
        $bookingData['store_email'] = $storeData->email;
        $bookingData['time_slots'] = $timeSlotData[0]['time_slot'];
        $bookingData['store_name'] = $storeData->store_name; 

        Mail::to($storeData->email)->send(new NewBooking($bookingData));
        Mail::to($request->email)->send(new ClientConfirmation($bookingData));

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
        $bookingData = Bookings::where('id', $id)->first(); 
        $timeSlotData = [];
        foreach (json_decode($bookingData->time_slots_id) as $timeSlot) {
            $timeLabel = TimeSlots::select('time_slot')->where('id', $timeSlot)->first();
            array_push($timeSlotData, $timeLabel);
        }
        $bookingData['customer_data'] = Users::select('name', 'email', 'contact_no')->where('id', $bookingData->user_id)->first();
        if($bookingData) {
            return response()->json([
                'status' => 200,
                'get_data' => $bookingData,
                'timeSlot_data' => $timeSlotData
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

    public function downloadPDF($storeId, $date){
        if($date != 'null'){
            $bookings = DB::table('bookings')
            ->select('bookings.id', 'bookings.time_slots_id', 'bookings.no_of_kids', 'bookings.booking_date', 'users.name', 'users.email', 'users.contact_no')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.stores_id', $storeId)
            ->where('bookings.booking_date', $date)
            ->get()->toArray();  
            foreach($bookings as $key=>$booking) {
                $booking = (array)$booking;
                $timeSlots = json_decode($booking['time_slots_id']);
                $booking['slots'] = $this->getTimeSlots($timeSlots);  
                $bookings[$key] = $booking;    
            }
        }
        else {
            $bookings = DB::table('bookings')
            ->select('bookings.id', 'bookings.time_slots_id', 'bookings.no_of_kids', 'bookings.booking_date', 'users.name', 'users.email', 'users.contact_no')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.stores_id', $storeId)
            ->get()->toArray();  
            foreach($bookings as $key=>$booking) {
                $booking = (array)$booking;
                $timeSlots = json_decode($booking['time_slots_id']);
                $booking['slots'] = $this->getTimeSlots($timeSlots);  
                $bookings[$key] = $booking;    
            }
        }
        $pdf = PDF::loadView('bookingList', ['bookings' => $bookings]);
        return $pdf->stream('invoice.pdf');
    }


    private function getTimeSlots(array $slotIds):array {
        $slots = DB::table('time_slots')
            ->select('time_slot')
            ->whereIn('id', $slotIds)
            ->get(); 
        $output = [];
        foreach ($slots as $slot) {
            $output[] = $slot->time_slot;
        }  
        return $output; 
    }

}
