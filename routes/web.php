<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleLoginController;
use App\Livewire\MapLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;


Route::get('/', [UserController::class, 'index']);

Route::get('/manage-user', [UserController::class, 'index']);

Route::get('/add-user', [UserController::class, 'add_user']);

Route::delete('/delete-user', [UserController::class, '']);

Route::get('/edit-user/{id}', [UserController::class, 'edit_user']);

Route::put('/edit-user', [UserController::class, 'edit_action']);

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
