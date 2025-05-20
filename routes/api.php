<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//USERS
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/apri-ticket', [TicketController::class, 'store'])->middleware('auth:sanctum');
Route::get('/lista-ticket', [TicketController::class, 'index'])->middleware('auth:sanctum');
Route::get('/lista-ticket/{ticket}', [TicketController::class, 'show'])->middleware('auth:sanctum');
Route::delete('/elimina-ticket/{ticket}', [TicketController::class, 'destroy'])->middleware('auth:sanctum');
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
 