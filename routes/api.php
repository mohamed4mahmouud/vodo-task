<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    //Register
    Route::post('register', [AuthController::class , 'register'])->name('register');
    //login
    Route::post('login', [AuthController::class , 'login'])->name('login');
    //logout
    Route::post('logout' , [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::put('update-password', [AuthController::class, 'updatePassword'])->middleware('auth:sanctum');

    Route::post('/forgot-password',[ResetPasswordController::class, 'forgotPassword']);
    Route::post('/reset-password',[ResetPasswordController::class, 'resetPassword']);
});

Route::apiResource('/notes', NoteController::class)->middleware('auth:sanctum');
Route::put('users', [UserController::class, 'update'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['auth:sanctum', 'signed'])
    ->name('verification.verify');

