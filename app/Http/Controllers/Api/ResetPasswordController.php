<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use Ichtrojan\Otp\Otp;
use App\Notifications\ResetPasswordNotification;

class ResetPasswordController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {

            $user = User::where('email' , $request->email)->first();

            if(!$user){
                return response()->json([
                    'error'=> 'There is no user with that email'
                ], 404);
            }

            $user->notify(new ResetPasswordNotification());


            return response()->json([
                'status' => true,
                'message' => 'Email sent With otp'
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }

    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {

            $otp = new Otp;
            $code = $otp->validate($request->email,$request->otp);

            if(!$code->status){
                return response()->json([
                    'status' => false,
                    'error' => 'Incorrect otp'
                ],401);
            }
            $user = User::where('email',$request->email)->first();

            if(!$user){
                return response()->json([
                    'status' => false,
                    'error' => 'Incorrect Email'
                ],404);
            }
                $user->update([
                    'password' => Hash::make($request->newPassword)
                ]);
                $user->save();

            //delete perv tokens
            $user->tokens()->delete();

            $token = $user->createToken('API TOKEN')->plainTextToken;

            return response()->json([
                'token' => $token,
                'message' => 'password reset success'
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }

    }
}
