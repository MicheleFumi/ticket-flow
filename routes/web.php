<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ticketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Auth.login');
});

/* Route::get('/', function () {
    return view('welcome');
}); */

/* Route::get('/dashboard', [ticketController::class, 'index'], function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); */

Route::middleware(['auth','verified'])->group(function(){
   Route::get('/dashboard', [ticketController::class, 'index'])->name("dashboard");
   Route::get('/lista-ticket',function(){return view("tickets");})->name("tickets");
   Route::get('/utenti', function(){return view("users");})->name("users");
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
