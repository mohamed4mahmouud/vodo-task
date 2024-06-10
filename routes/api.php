<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    //Register
    Route::post('register', [AuthController::class , 'register']);
    //login
    Route::post('login', [AuthController::class , 'login']);
    //logout
    Route::post('logout' , [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::put('update-password', [AuthController::class, 'updatePassword'])->middleware('auth:sanctum');
});

Route::apiResource('notes', NoteController::class)->middleware('auth:sanctum');
Route::put('users', [UserController::class, 'update'])->middleware('auth:sanctum');

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
