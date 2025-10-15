<?php
use App\Http\Controllers\API\AdmissionsController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Secured routes
Route::middleware('auth:sanctum')->group(function() {
    // Admissions CRUD
    Route::get('/admissions', [AdmissionsController::class, 'index']);
    Route::post('/admissions', [AdmissionsController::class, 'store']);
    Route::get('/admissions/{id}', [AdmissionsController::class, 'show']);
    Route::put('/admissions/{id}', [AdmissionsController::class, 'update']);
    Route::delete('/admissions/{id}', [AdmissionsController::class, 'destroy']);
    Route::get('/dashboard-stats', [AdmissionsController::class, 'stats']);

    // User profile routes
    Route::get('/me', [UserController::class, 'me']);           // Fetch logged-in user
    Route::put('/users/{id}', [UserController::class, 'update']); // Update profile
});
