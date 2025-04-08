<?php

use App\Http\Controllers\NearbyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleLoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\branchController;
use App\Http\Controllers\HomeController;

use App\Livewire\MapLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesTeamController;
use App\Http\Middleware\CheckGoogleLogin;
use Doctrine\DBAL\Driver\Middleware;
use PHPUnit\Runner\HookMethod;



// @author : Pakkapon Chomchoey 66160080

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/logout', function () {
    Session::forget('google_user');
    Session::flush();
    Auth::logout();
    return Redirect()->route('login');
})->name('logout');

Route::get('/cluster4/auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('redirect.google');

Route::get('/cluster4/auth/google/callback', [GoogleLoginController::class, 'googleCallback'])->name('callback.google');

Route::middleware([CheckGoogleLogin::class])->group(
    function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        Route::get('/report/sales-team', [SalesTeamController::class, 'index'])->name('team');

        Route::get('/report/sales-team{id}', [SalesTeamController::class, 'detail'])->name('team.detail');


        Route::get('/manage-user', [UserController::class, 'index'])->name('manage.user');


        Route::get('/add-user', [UserController::class, 'add_user'])->name('add.user');
        Route::post('/add-user', [UserController::class, 'create'])->name('create.user');


        Route::delete('/delete-user', [UserController::class, 'delete_user'])->name('delete.user');


        Route::get('/edit-user/{id}', [UserController::class, 'edit_user'])->name('edit.user.id');

        Route::put('/edit-user', [UserController::class, 'edit_action'])->name('edit.user');

        Route::get('/branchMyMap', [branchController::class, 'index'])->name('branchMyMap');

        Route::get('/map', MapLocation::class)->name('map');

        // Aninthita 66160381
        Route::get('/order-detail/{br_id}', [OrderController::class, 'order_detail']);


        Route::get('/order', [OrderController::class, 'index'])->name('order');

        Route::get('/add-order', [OrderController::class, 'add_order']);

        Route::get('/nearby/{branchId}', [NearbyController::class, 'index'])->name('nearby');

        Route::get('/order-status', [OrderController::class, 'status'])->name('order.status');

        Route::get('/order-status', [OrderController::class, 'status'])->name('order.status');

        Route::get('/report/team/{id}', [SalesTeamController::class, 'detail']);
    }
);
