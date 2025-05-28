<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    //DASHBOARD
    Route::get("/dashboard", [DashboardController::class, 'index'])->name("dashboard.index");
    Route::get("/dashboard/history", [DashboardController::class, 'history'])->name("dashboard.history");

    //UTENTI
    Route::get("/utenti", [UserController::class, 'index'])->name("users.index");

    //TECNICI
    Route::get('/tecnici', [TechnicianController::class, 'index'])->name("technicians.index");
    Route::post('/user-to-technician', [TechnicianController::class, 'userToTechnician'])->name('user-to-technician');
    Route::post('/technician-to-user', [TechnicianController::class, 'technicianToUser'])->name('technician-to-user');

    //TICKET
    Route::get('/lista-ticket', [TicketController::class, 'index'])->name("tickets.index");
    Route::get('/lista-ticket/{ticket}', [TicketController::class, 'show'])->name("tickets.show");
    Route::post('/assegna-ticket/{ticket}', [TicketController::class, 'assign'])->name("tickets.assign");
    Route::post("/ticket/unassign", [TicketController::class, 'unassign'])->name("tickets.unassign");
    Route::post("/ticket/close", [TicketController::class, 'close'])->name("tickets.close");
    Route::post("/ticket/delete/{ticket}", [TicketController::class, 'destroy'])->name("tickets.delete");
    Route::post("/ticket/assegna-a/{ticket}", [TicketController::class, 'assignTo'])->name("tickets.assignTo");
});
//PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
