<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register (RegisterRequest $request)
    {
        try {

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
            ], 201);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }

    }

    public function login (LoginRequest $request)
    {
        try {

            if(!Auth::attempt($request->only(['username','password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Incorrect username or password'
                ],401);
            }

            $user = User::where('username' , $request->username)->first();
            $token = $user->createToken("API TOKEN")->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'token' => $token
            ],200);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }

    public function logout (Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
