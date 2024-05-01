<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [Authcontroller::class, 'login']);
Route::post('/register', [Authcontroller::class, 'register']);
Route::delete('/logout', [Authcontroller::class, 'logout'])->middleware('auth:sanctum');
