<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\CheckTokenExpiration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Pubbliche
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [PasswordResetController::class, 'sendResetLink']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);

// Protette
Route::group(['middleware' => ['auth:sanctum', CheckTokenExpiration::class]], function () {
    // User
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/delete', [UserController::class, 'destroy']);
    Route::patch('/password/update', [UserController::class, 'updatePassword']);

    // Tickets
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::post('/ticket/open', [TicketController::class, 'store']);
    Route::get('/ticket/detail/{ticket}', [TicketController::class, 'show']);
    Route::patch('/ticket/update/{ticket}', [TicketController::class, 'update']);
    Route::delete('/ticket/delete/{ticket}', [TicketController::class, 'destroy']);
});
