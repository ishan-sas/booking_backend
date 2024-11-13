<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Models\Organizations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:user',
            'contact_no' => 'required|unique:user',
            'password' => 'required|min:3',
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
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'contact_no' => $request->contact_no,
                'user_role' => $request->user_role, // 1: store user, 2: customers
                'is_subscribe' => $isSubscribe,
            ]);

            // if($request->user_role == 1) {
            //     $user = User::create([
            //         'user_id' => $user->id,
            //         'stores_id' => $request->user_role,
            //     ]);
            // }
        }
        
        $token = $user->createToken($user->email.'_Token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'username' => $user->name,
            'token' => $token,
            'message' => 'Registered successfully.',
        ]);
    }

}
