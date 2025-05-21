<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

/* Route::get('/', function () {
    return view('welcome');
}); */

/* Route::get('/dashboard', [ticketController::class, 'index'], function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); */

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view("dashboard");
    })->name("dashboard");

    //UTENTI
    // Route::get('/utenti', function () {
    //     return view("users");
    // })->name("users");

    //TECNICI
    Route::get('/tecnici', [TechnicianController::class, 'index'])->name("technicians.index");
    Route::post('/user-to-technician', [TechnicianController::class, 'userToTechnician'])->name('user-to-technician');
    Route::post('/technician-to-user', [TechnicianController::class, 'technicianToUser'])->name('technician-to-user');

    //TICKET
    Route::get('/lista-ticket', [TicketController::class, 'index'])->name("tickets");
});


//PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
