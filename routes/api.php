<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenueTypeController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketTypeController;
use App\Http\Controllers\TransactionController;

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::prefix('venuetypes')->group(function () {
    Route::get('/', [VenueTypeController::class, 'index']);
    Route::get('/{id}', [VenueTypeController::class, 'show']);
    Route::post('/', [VenueTypeController::class, 'store']);
    Route::put('/{id}', [VenueTypeController::class, 'update']);
    Route::delete('/{id}', [VenueTypeController::class, 'destroy']);
});

Route::prefix('eventtypes')->group(function () {
    Route::get('/', [EventTypeController::class, 'index']);
    Route::get('/{id}', [EventTypeController::class, 'show']);
    Route::post('/', [EventTypeController::class, 'store']);
    Route::put('/{id}', [EventTypeController::class, 'update']);
    Route::delete('/{id}', [EventTypeController::class, 'destroy']);
});

Route::prefix('venues')->group(function () {
    Route::get('/', [VenueController::class, 'index']);
    Route::get('/{id}', [VenueController::class, 'show']);
    Route::post('/', [VenueController::class, 'store']);
    Route::put('/{id}', [VenueController::class, 'update']);
    Route::delete('/{id}', [VenueController::class, 'destroy']);
});

Route::prefix('events')->group(function () {

    Route::get('/', action: [EventController::class, 'index']);      // GET    /api/events
    Route::get('{id}', [EventController::class, 'show']);    // GET    /api/events/{id}
    Route::post('/', [EventController::class, 'store']);     // POST   /api/events
    Route::put('{id}', [EventController::class, 'update']);  // PUT    /api/events/{id}
    Route::delete('{id}', [EventController::class, 'destroy']); // DELETE /api/events/{id}
});

Route::prefix('tickettypes')->group(function () {
    Route::get('/', [TicketTypeController::class, 'index']);
    Route::post('/', [TicketTypeController::class, 'store']);
    Route::get('/{id}', [TicketTypeController::class, 'show']);
    Route::put('/{id}', [TicketTypeController::class, 'update']);
    Route::delete('/{id}', [TicketTypeController::class, 'destroy']);
});

Route::prefix('transactions')->group(function () {
    Route::get('/', [TransactionController::class, 'index']);          // List all transactions
    Route::post('/', [TransactionController::class, 'store']);         // Create a new transaction
    Route::get('/{id}', [TransactionController::class, 'show']);       // Get a single transaction
    Route::put('/{id}', [TransactionController::class, 'update']);     // Update a transaction
    Route::delete('/{id}', [TransactionController::class, 'destroy']); // Soft-delete a transaction
});