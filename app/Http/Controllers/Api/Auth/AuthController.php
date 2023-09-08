<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConfirmRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        $token = $user->createToken('auth_token',['*'],now()->addHours(12))->plainTextToken;

        return response()->json([
            'message' => 'Register success',
            'data' => [
                ...$user->toArray(),
                'access_token' => $token
            ]
        ], 201);
    }

    public function login(Request $request)
    {
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

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout success'
        ]);
    }

    public function info()
    {
        $user = User::where("id", "=", Auth::user()->id)->with("roles")->first();
        return response()->json([
            'message' => 'Login success',
            'data' => [
                ...$user->toArray()
            ],
        ]);
    }

    public function confirm(ConfirmRequest $request)
    {

        $user = Auth::user();

        if (! Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 401);
        }

        $user->update([
            'password' => $request->input('new_password')
        ]);

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }
}
