<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/manage-user', [UserController::class, '']);

Route::get('/add-user', [UserController::class, '']);

Route::post('/add-user', [UserController::class, '']);

Route::delete('/delete-user', [UserController::class, '']);

Route::put('/edit-user', [UserController::class, '']);
