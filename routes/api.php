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
    TransactionController,
    OrganizerController
};

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::get('/topevents', action: [EventController::class, 'getTopEvents']);


Route::middleware('auth:api')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    // Users
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/updatePassword', [UserController::class, 'UpdatePassword']);
        Route::delete('/me', [UserController::class, 'destroyMe']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/', [UserController::class, 'infoUpdate']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    Route::prefix('venuetypes')->group(function () {
        Route::get('/', [VenueTypeController::class, 'index']);
        Route::post('/', [VenueTypeController::class, 'store']);
        Route::get('/{id}', [VenueTypeController::class, 'show']);
        Route::put('/{id}', [VenueTypeController::class, 'update']);
        Route::delete('/{id}', [VenueTypeController::class, 'destroy']);
    });

    Route::prefix('eventtypes')->group(function () {
        Route::get('/', [EventTypeController::class, 'index']);
        Route::post('/', [EventTypeController::class, 'store']);
        Route::get('/{id}', [EventTypeController::class, 'show']);
        Route::put('/{id}', [EventTypeController::class, 'update']);
        Route::delete('/{id}', [EventTypeController::class, 'destroy']);
    });

    Route::prefix('venues')->group(function () {
        Route::get('/', [VenueController::class, 'index']);
        Route::post('/', [VenueController::class, 'store']);
        Route::get('/{id}', [VenueController::class, 'show']);
        Route::put('/{id}', [VenueController::class, 'update']);
        Route::delete('/{id}', [VenueController::class, 'destroy']);
    });

    Route::prefix('events')->group(function () {
        Route::get('/topevents', action: [EventController::class, 'getTopEvents']);
        Route::get('/', [EventController::class, 'index']);
        Route::post('/', [EventController::class, 'store']);
        Route::get('/{id}', [EventController::class, 'show']);
        Route::put('/{id}', [EventController::class, 'update']);
        Route::delete('/{id}', [EventController::class, 'destroy']);
    });

    Route::prefix('organizers')->group(function () {
        Route::get('/', [OrganizerController::class, 'index']);
        Route::post('/', [OrganizerController::class, 'store']);
        Route::get('/{id}', [OrganizerController::class, 'show']);
        Route::put('/{id}', [OrganizerController::class, 'update']);
        Route::delete('/{id}', [OrganizerController::class, 'destroy']);
    });

    Route::prefix('tickettypes')->group(function () {
        Route::get('/', [TicketTypeController::class, 'index']);
        Route::post('/', [TicketTypeController::class, 'store']);
        Route::get('/{id}', [TicketTypeController::class, 'show']);
        Route::put('/{id}', [TicketTypeController::class, 'update']);
        Route::delete('/{id}', [TicketTypeController::class, 'destroy']);
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::get('/{id}', [TransactionController::class, 'show']);
        Route::put('/{id}', [TransactionController::class, 'update']);
        Route::delete('/{id}', [TransactionController::class, 'destroy']);
    });
});