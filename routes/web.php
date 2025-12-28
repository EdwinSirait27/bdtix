<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;

Route::middleware('throttle:15,1')->group(function () {
    Route::get('/', [AuthController::class, 'loginPage'])->name('login')->middleware('guest');

    Route::get('/lang/{lang}', function ($lang) {
        session(['applocale' => $lang]);
        return back();
    })->name('lang.switch');
    Route::get('/about', [dashboardController::class, 'aboutUs'])->name('about');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');
Route::get('/dashboard', [dashboardController::class, 'dashboardPage'])->name('dashboard')->middleware('auth');
Route::get('/profile', [ProfileController::class, 'profile'])->name('profile')->middleware('auth');
Route::get('/openticket', [TicketController::class, 'openTicket'])->name('openticket')->middleware('auth');
Route::get('/mytickets', [TicketController::class, 'myTicket'])->name('mytickets')->middleware('auth');
Route::get('/users', [UserController::class, 'users'])->name('users')->middleware('auth');
Route::match(['GET', 'POST'], '/users/users', [UserController::class, 'getUsers'])->name('users.users')->middleware('auth');
Route::match(['GET', 'POST'], '/allticketforadmins/allticketforadmins', [dashboardController::class, 'getAllticketforadmins'])->name('allticketforadmins.allticketforadmins')->middleware('auth');
Route::get('/alltickets', [TicketController::class, 'allTickets'])->name('alltickets')->middleware('auth');
Route::match(['GET', 'POST'], '/allmytickets/allmytickets', [TicketController::class, 'getAllmytickets'])->name('allmytickets.allmytickets')->middleware('auth');
Route::match(['GET', 'POST'], '/alltickets/alltickets', [TicketController::class, 'getAlltickets'])->name('alltickets.alltickets')->middleware('auth');
Route::get('/editusers/{hash}', [UserController::class, 'edit'])->name('editusers')->middleware('auth');
Route::post('/updateusers/{hash}/update', [UserController::class, 'update'])->name('updateusers')->middleware('auth');
Route::get('/editopenticket/{hash}', [dashboardController::class, 'edit'])->name('editopenticket')->middleware('auth');
Route::get('/showopenticket/{hash}', [dashboardController::class, 'show'])->name('showopenticket')->middleware('auth');
// Route::get('/editusers/{hashedId}', [UserController::class, 'edit'])->name('editusers');
Route::post('/ticketreq', [TicketController::class, 'store'])->name('ticketreq')->middleware('auth');
Route::get('/showmytickets/{hash}', [TicketController::class, 'show'])->name('showmytickets')->middleware('auth');
