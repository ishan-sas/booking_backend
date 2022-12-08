<?php

namespace App\Http\Controllers\api;

use App\Models\StoreSchools;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StoreSchoolsController extends Controller
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
        $validator = Validator::make($request->all(),
            [
                'school_name' => '',
            ]
        );
        if($validator->fails()){
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        }
        else {
            $user_id = Auth::user()->id;
            foreach($request->schoolTimeSlots as $row) {
                if($row['id'] == NULL) {
                    $storeSchool = StoreSchools::create([
                        'user_id' => $user_id,
                        'stores_id' => $row['stores_id'],
                        'school_name' => $row['school_name'],
                    ]);
                }
                else {
                    $storeSchool = StoreSchools::find($row['id']);
                    if($storeSchool) {
                        $storeSchool->user_id = $user_id;
                        $storeSchool->stores_id = $row['stores_id'];
                        $storeSchool->school_name = $row['school_name'];

                        $storeSchool->save();
                    }
                }
            }
            return response()->json([
                'status' => 200,
                'message' => 'School successfully added.',
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
        $dataList = StoreSchools::where('stores_id', $id)->get();  
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
