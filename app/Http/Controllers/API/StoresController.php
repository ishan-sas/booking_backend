<?php

namespace App\Http\Controllers\api;

use App\Models\Stores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = DB::table('stores')->where([
            ['status', '=', '1'],
        ])->get();
        return response()->json([
            'status'=>200,
            'stores'=>$stores
        ]);
    }

    public function getAllStoreForAdmin()
    {
        $stores = DB::table('stores')->get();
        return response()->json([
            'status'=>200,
            'get_data'=>$stores
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $storeProfile = Stores::where('slug', $slug)->first(); 
        if($storeProfile) {
            return response()->json([
                'status' => 200,
                'store_profile' => $storeProfile,
            ]);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'No store ID found.!',
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
        $data = Stores::where('id', '=', $id)->firstOrFail();
        if($data) {
            return response()->json([
                'status' => 200,
                'get_data' => $data
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'store_name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        }
        else {
            $stores = Stores::find($id);
            if($stores) {
                $stores->store_name = $request->input('store_name');
                $stores->no_of_ftrooms = $request->input('no_of_ftrooms');
                $stores->address = $request->input('address');
                $stores->contact_no = $request->input('contact_no');
                $stores->email = $request->input('email');

                $stores->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Successfully updated.',
                ]);
            }
            else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No ID found.',
                ]);
            } 
        }
    }

    public function updateStoreStatus(Request $request, $id)
    {
        $stores = Stores::find($id);
        if($stores) {
            $stores->status = $request->input('status');

            $stores->save();
            return response()->json([
                'status' => 200,
                'message' => 'Successfully updated.',
            ]);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'No ID found.',
            ]);
        } 
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
