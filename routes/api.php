<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    //Register
    Route::post('register', [AuthController::class , 'register']);
    //login
    Route::post('login', [AuthController::class , 'login']);
    //logout
    Route::post('logout' , [AuthController::class, 'logout'])->middleware('auth:sanctum');
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
