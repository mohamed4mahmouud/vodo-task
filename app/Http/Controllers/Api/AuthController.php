<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

            event(new Registered($user));

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
            ],200)->header('Authorization', $token);

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

    public function updatePassword (UpdatePasswordRequest $request)
    {
        try {

            $user = $request->user();

            if(!Hash::check($request->currentPassword, $user->password)) {
                return response()->json([
                    'status' => false,
                    'error' => 'The current password is incorrect'
                ], 403);
            }

            $user->update([
                'password' => Hash::make($request->newPassword),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Password updated successfully',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }

    }

    public function verifyEmail(EmailVerificationRequest $request)
{
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json([
            'status' => false,
            'message' => 'Email already verified.'
        ], 400);
    }

    if ($request->user()->markEmailAsVerified()) {
        event(new Verified($request->user()));
    }

    return response()->json([
        'status' => true,
        'message' => 'Email verified successfully.'
    ], 200);
}
}
