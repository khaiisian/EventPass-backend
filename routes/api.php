<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    UserController,
    VenueTypeController,
    EventTypeController,
    VenueController,
    EventController,
    TicketTypeController,
    TransactionController
};

// Public Auth routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth:api')->group(function () {

    // Auth actions
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    // Users
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);

        // Specific routes before parameterized routes
        Route::put('/updatePassword', [UserController::class, 'UpdatePassword']);

        // Parameterized routes last
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // Venue Types
    Route::prefix('venuetypes')->group(function () {
        Route::get('/', [VenueTypeController::class, 'index']);
        Route::post('/', [VenueTypeController::class, 'store']);
        Route::get('/{id}', [VenueTypeController::class, 'show']);
        Route::put('/{id}', [VenueTypeController::class, 'update']);
        Route::delete('/{id}', [VenueTypeController::class, 'destroy']);
    });

    // Event Types
    Route::prefix('eventtypes')->group(function () {
        Route::get('/', [EventTypeController::class, 'index']);
        Route::post('/', [EventTypeController::class, 'store']);
        Route::get('/{id}', [EventTypeController::class, 'show']);
        Route::put('/{id}', [EventTypeController::class, 'update']);
        Route::delete('/{id}', [EventTypeController::class, 'destroy']);
    });

    // Venues
    Route::prefix('venues')->group(function () {
        Route::get('/', [VenueController::class, 'index']);
        Route::post('/', [VenueController::class, 'store']);
        Route::get('/{id}', [VenueController::class, 'show']);
        Route::put('/{id}', [VenueController::class, 'update']);
        Route::delete('/{id}', [VenueController::class, 'destroy']);
    });

    // Events
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index']);
        Route::post('/', [EventController::class, 'store']);
        Route::get('/{id}', [EventController::class, 'show']);
        Route::put('/{id}', [EventController::class, 'update']);
        Route::delete('/{id}', [EventController::class, 'destroy']);
    });

    // Ticket Types
    Route::prefix('tickettypes')->group(function () {
        Route::get('/', [TicketTypeController::class, 'index']);
        Route::post('/', [TicketTypeController::class, 'store']);
        Route::get('/{id}', [TicketTypeController::class, 'show']);
        Route::put('/{id}', [TicketTypeController::class, 'update']);
        Route::delete('/{id}', [TicketTypeController::class, 'destroy']);
    });

    // Transactions
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::get('/{id}', [TransactionController::class, 'show']);
        Route::put('/{id}', [TransactionController::class, 'update']);
        Route::delete('/{id}', [TransactionController::class, 'destroy']);
    });

});