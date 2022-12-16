<?php

namespace App\Http\Controllers\api;

use App\Models\Stores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StoresUnavailableDates;
use Illuminate\Support\Facades\Validator;

class StoreUnavailableDatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUnavailableDates($slug, $requestDate) {
        $unaveDate = DB::table('store_unavailable_dates')
            ->select('unave_date')
            ->where('unave_date', $requestDate)
            ->where('stores_id', 1)
            ->get();
        return response()->json([
            'status'=>200,
            'get_data'=>$unaveDate
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
    public function store(Stores $store, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unave_date' => '',
        ]);

        if($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        }
        else { 
            $user_id = Auth::id(); 
            foreach($request->dateTimeSlots as $row) {
                if(empty( $row['id'] )) {
                    $storeSchool = StoresUnavailableDates::create([
                        'user_id' => $user_id,
                        'stores_id' => $row['stores_id'],
                        'unave_date' => $row['unave_date'],
                    ]);
                }
                else {
                    $storeSchool = StoresUnavailableDates::find($row['id']);
                    if($storeSchool) {
                        $storeSchool->user_id = $user_id;
                        $storeSchool->stores_id = $row['stores_id'];
                        $storeSchool->unave_date = $row['unave_date'];

                        $storeSchool->save();
                    }
                }
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Unavailable dates are successfully updated.',
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
        $dataList = StoresUnavailableDates::where('stores_id', $id)->get();  
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
        $deleted = DB::table('store_unavailable_dates')->where('id', $id)->delete();
        return response()->json([
            'status' => 200,
            'messagen' => 'Record deleted successfully.',
        ]);
    }
}
