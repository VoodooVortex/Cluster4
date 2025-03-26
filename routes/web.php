<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index']);
Route::get('/manage-user', [UserController::class, 'index']);

Route::get('/add-user', [UserController::class, 'addUser']);

Route::delete('/delete-user', [UserController::class, '']);


Route::get('/edit-user/{id}', [UserController::class, 'edit']);
// Route::get('/edit-user', function(){
//     return view('edit');
// });
Route::put('/edit-user', [UserController::class, 'edit_action']);

Route::get('/login', function () {
    return view('login');
});

Route::get('auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('redirect.google');

Route::get('auth/google/callback', [GoogleLoginController::class, 'googleCallback'])->name('callback.google');

