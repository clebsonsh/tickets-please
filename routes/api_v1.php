<?php

use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\AuthorTicketsController;
use App\Http\Controllers\Api\V1\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tickets', TicketController::class);
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('authors.tickets', AuthorTicketsController::class);
});
