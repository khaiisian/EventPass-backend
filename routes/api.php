<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenueTypeController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\VenueController;

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