<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\VerifyAdmin;
use App\Http\Middleware\VerifySuperAdmin;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', function () {
        return redirect('/login');
    });
    //DASHBOARD
    Route::get("/dashboard", [DashboardController::class, 'index'])->name("dashboard.index");
    Route::get("/dashboard/history", [DashboardController::class, 'history'])->name("dashboard.history");

    //UTENTI
    Route::get("/users", [UserController::class, 'index'])->name("users.index");
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    //TECNICI
    Route::get('/technicians', [TechnicianController::class, 'index'])->name("technicians.index");
    Route::post('/technician/create', [TechnicianController::class, 'create'])->name('technician.create')->middleware([VerifyAdmin::class, VerifySuperAdmin::class]);
    Route::post('/technician/destroy', [TechnicianController::class, 'destroy'])->name('technician.destroy')->middleware(VerifyAdmin::class);
    Route::post("/technician/restore", [TechnicianController::class, 'restore'])->name("technician.restore")->middleware(VerifySuperAdmin::class);
    Route::post("/technician-to-admin", [AdminController::class, 'technicianToAdmin'])->name("technician-to-admin")->middleware(VerifySuperAdmin::class);
    Route::post("/admin-to-technician", [AdminController::class, 'adminToTechnician'])->name("admin-to-technician")->middleware(VerifySuperAdmin::class);

    //TICKET
    Route::get('/tickets', [TicketController::class, 'index'])->name("tickets.index");
    Route::post("/ticket/report/{ticket}", [TicketController::class, 'report'])->name("tickets.report");
    Route::get('/ticket/detail/{ticket}', [TicketController::class, 'show'])->name("tickets.show");
    Route::post('/ticket/assign/{ticket}', [TicketController::class, 'assign'])->name("tickets.assign");
    Route::post("/ticket/close", [TicketController::class, 'close'])->name("tickets.close");
    Route::post("/ticket/unassignTo", [TicketController::class, 'unassign'])->name("tickets.unassign")->middleware([VerifyAdmin::class]);
    Route::post("/ticket/delete/{ticket}", [TicketController::class, 'destroy'])->name("tickets.delete")->middleware([VerifyAdmin::class]);
    Route::post("/ticket/assign-to/{ticket}", [TicketController::class, 'assignTo'])->name("tickets.assignTo")->middleware([VerifyAdmin::class]);
    Route::post("/ticket/reopen/{ticket}", [TicketController::class, 'reopen'])->name("tickets.reopen")->middleware([VerifyAdmin::class]);
});
//PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
