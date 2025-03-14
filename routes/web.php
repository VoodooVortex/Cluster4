<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index']);
Route::get('/manage-user', [UserController::class, 'index']);

Route::get('/add-user', [UserController::class, 'addUser']);

// Route::post('/add-user', [UserController::class, '']);

Route::delete('/delete-user', [UserController::class, '']);

Route::get('/edit-user', [UserController::class, '']);
Route::put('/edit-user', [UserController::class, '']);

Route::get('/login', function () {
    return view('login');
});

Route::get('auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('redirect.google');

Route::get('auth/google/callback', [GoogleLoginController::class, 'googleCallback'])->name('callback.google');

