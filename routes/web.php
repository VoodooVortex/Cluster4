<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleLoginController;
use App\Http\Controllers\HomeController;
use App\Livewire\MapLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\DashboardController;
use PHPUnit\Runner\HookMethod;



Route::get('/', [UserController::class, 'index']);

//kuy mork
Route::get('/dashboard', [DashboardController::class, 'branchGrowthRate'])->name('dashboard.branch.growth');

Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::get('/manage-user', [UserController::class, 'index'])->name('manage.user');

Route::get('/add-user', [UserController::class, 'add_user'])->name('add.user');

Route::delete('/delete-user', [UserController::class, 'delete_user'])->name('delete.user');

Route::get('/edit-user/{id}', [UserController::class, 'edit_user']);

Route::put('/edit-user', [UserController::class, 'edit_action'])->name('edit.user');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/logout', function () {
    Session::forget('google_user');
    Session::flush();
    Auth::logout();
    return Redirect('/login');
})->name('logout');


Route::get('/map', MapLocation::class)->name('map');


Route::get('auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('redirect.google');

Route::get('auth/google/callback', [GoogleLoginController::class, 'googleCallback'])->name('callback.google');
