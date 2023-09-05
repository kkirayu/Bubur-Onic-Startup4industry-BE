<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {

//set validation
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        
        }

        
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $token = $user->createToken('auth_token',['*'],now()->addHours(12))->plainTextToken;

            return response()->json([
                'message' => 'Login success',
                'data' => [
                    ...$user->toArray(),
                    'access_token' => $token,
                ],
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

    }

    public
    function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout success'
        ]);
    }

    public
    function info()
    {
        $user = User::where("id", "=", Auth::user()->id)->with("roles")->first();
        return response()->json([
            'message' => 'Login success',
            'data' => [
                ...$user->toArray()
            ],
        ]);
    }
}
