<?php

namespace App\Http\Controllers\API;

use App\Models\Users;
use App\Models\Stores;
use App\Models\StoreUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function getCustomers() {
        $getData = Users::all();
        return response()->json([
            'status'=>200,
            'get_data'=>$getData
        ]);
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
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:3',
            'contact_no' => 'required|unique:users',
        ]);  

        if($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        }
        else {
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
        }
        
        $token = $user->createToken($user->email.'_Token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'username' => $user->name,
            'token' => $token,
            'message' => 'Registered successfully.',
        ]);
    }


    public function storeRegister(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:3',
            'contact_no' => 'required|unique:users',
        ]);  

        if($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        }
        else {
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
            
            $store = Stores::create([
                'user_id' => $user['id'],
                'store_name' => $request->name,
                'slug' =>  strtolower(str_replace(' ', '-', $request->name)),
                'no_of_ftrooms' => $request->no_of_ftrooms,
                'contact_no' => $request->contact_no,
                'address' => $request->address,
            ]);

            $store = StoreUsers::create([
                'user_id' => $user['id'],
                'stores_id' => $store['id'],
                'store_role' =>  1,
            ]);
        }
        
        $token = $user->createToken($user->email.'_Token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'username' => $user->name,
            'token' => $token,
            'message' => 'Registered successfully.',
        ]);
    }


    public function authenticate(Request $request)
    {
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
                //     $token = $user->createToken($user->email.'_SuperUserToken', ['server:superUser'])->plainTextToken;
                // }
                // if($user->user_role === 1) {
                //     $token = $user->createToken($user->email.'_AdminUserToken', ['server:adminUser'])->plainTextToken;
                // }
                // else {
                    $token = $user->createToken($user->email.'_Token')->plainTextToken;
                // }

                $storeProfile = StoreUsers::where('user_id', $user->id)->first();
                if(isset($storeProfile)) {
                    $storeId = $storeProfile->id;
                } 
                else {
                    $storeId = 0;
                }
                return response()->json([
                    'status' => 200,
                    'username' => $user->name,
                    'profileId' => $user->id,
                    'token' => $token,
                    'usertype' => $user->user_role,
                    'storeId' => $storeId,
                    'message' => 'Done. Logged in successfully.',
                ]);
            }
        }
    }


    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Logged out successfully.',
        ]);
    }
}
