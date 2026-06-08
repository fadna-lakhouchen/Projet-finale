<?php

use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/incidents', [IncidentController::class, 'index']);
Route::get('/incidents/{id}', [IncidentController::class, 'show']);
Route::post('/incidents', [IncidentController::class, 'store']);

// Existing Mobile Endpoints
Route::get('/charges/{userId}', [App\Http\Controllers\Api\ChargeController::class, 'index']);
Route::get('/notifications/{userId}', [App\Http\Controllers\Api\NotificationController::class, 'index']);
Route::get('/documents/{userId}', [App\Http\Controllers\Api\DocumentController::class, 'index']);

// New Mobile Endpoints
Route::get('/annonces/{userId}', [App\Http\Controllers\Api\AnnonceController::class, 'index']);
Route::get('/paiements/{userId}', [App\Http\Controllers\Api\PaiementController::class, 'index']);
Route::get('/dashboard/{userId}', [App\Http\Controllers\Api\DashboardController::class, 'resident']);
Route::get('/profile/{userId}', [App\Http\Controllers\Api\ProfileController::class, 'show']);