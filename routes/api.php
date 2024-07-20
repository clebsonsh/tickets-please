<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [Authcontroller::class, 'login'])->name('login');
Route::post('/register', [Authcontroller::class, 'register'])->name('register');
Route::delete('/logout', [Authcontroller::class, 'logout'])->middleware('auth:sanctum')->name('logout');
