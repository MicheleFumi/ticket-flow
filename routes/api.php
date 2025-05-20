<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//USERS
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/create-ticket', [TicketController::class, 'store'])->middleware('auth:sanctum');
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
