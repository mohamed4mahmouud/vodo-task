<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NoteController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login',function(){
    return view('auth.login');
});

Route::get('/notes',function(){
    return view('notes.index');
});
Route::get('/notes/create',function(){
    return view('notes.create');
})->name('notes.create');
Route::get('/notes/{id}/edit',function($id){
    $note=App\Models\Note::findOrFail($id);
    return view('notes.edit',compact('note'));
});
Route::get('register', function () {
    return view('auth.register');
});
Route::get('/user/profile', function() {
    return view('user.profile');
});

