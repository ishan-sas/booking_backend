<?php

namespace App\Http\Controllers\api;

use App\Models\StoreUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreUsersController extends Controller
{
    public function getStoreIdByUser($id) {
        $storeProfile = StoreUsers::where('user_id', $id)->first(); 
        return response()->json([
            'status'=>200,
            'get_data'=>$storeProfile
        ]);
    }
}
