<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import V1 Controllers
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\SurveyorController;
use App\Http\Controllers\Api\V1\TimTeknisController;
use App\Http\Controllers\Api\V1\PublicController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Auth Routes
    Route::post('/login', [AuthController::class, 'login']);

    // Public Routes (No Auth Required)
    Route::post('/public/laporan', [PublicController::class, 'submitLaporan']);
    Route::get('/public/wilayah', [PublicController::class, 'getWilayah']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        
        // Surveyor Routes
        Route::prefix('surveyor')->group(function () {
            Route::get('/tugas', [SurveyorController::class, 'index']);
            Route::post('/store', [SurveyorController::class, 'store']);
            Route::post('/sync', [SurveyorController::class, 'sync']); // Offline batch sync
        });

        // Tim Teknis Routes
        Route::prefix('tim-teknis')->group(function () {
            Route::get('/validasi', [TimTeknisController::class, 'index']);
            Route::post('/validasi/{id}', [TimTeknisController::class, 'validasi']);
        });
    });
});
