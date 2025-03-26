<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleLoginController;
use App\Livewire\MapLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('addUser');
});


Route::get('/manage-user', [UserController::class, '']);

Route::get('/add-user', [UserController::class, '']);

Route::post('/add-user', [UserController::class, '']);

Route::delete('/delete-user', [UserController::class, '']);

Route::put('/edit-user', [UserController::class, '']);

Route::get('/login', function () {
    return view('login');
});

Route::get('/logout', function () {
    Session::forget('google_user');
    Session::flush();
    Auth::logout();
    return Redirect('/login');
});


Route::get('/map', MapLocation::class)->name('map');



Route::get('auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('redirect.google');

Route::get('auth/google/callback', [GoogleLoginController::class, 'googleCallback'])->name('callback.google');
Route::get('/manageuser', [UserController::class, 'index']);
