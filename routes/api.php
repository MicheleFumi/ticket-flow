<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//USERS
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post("/user/delete", [UserController::class, 'destroy'])->middleware('auth:sanctum');
Route::patch('/update-password', [UserController::class, 'updatePassword'])->middleware('auth:sanctum');
Route::post("/forgot-password", [PasswordResetController::class, 'sendResetLink']);
Route::post("/reset-password", [PasswordResetController::class, 'resetPassword']);


//TICKETS

Route::get('/tickets', [TicketController::class, 'index'])->middleware('auth:sanctum');
Route::post('/ticket/open', [TicketController::class, 'store'])->middleware('auth:sanctum');
Route::get('/ticket/detail/{ticket}', [TicketController::class, 'show'])->middleware('auth:sanctum');
Route::patch('/ticket/update/{ticket}', [TicketController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/ticket/delete/{ticket}', [TicketController::class, 'destroy'])->middleware('auth:sanctum');
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
