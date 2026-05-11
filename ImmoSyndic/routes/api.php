<?php

use App\Http\Controllers\Api\IncidentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/incidents', [IncidentController::class, 'index']);
Route::get('/incidents/{id}', [IncidentController::class, 'show']);
Route::post('/incidents', [IncidentController::class, 'store']);

// New Mobile Consultation API Endpoints
Route::get('/charges/{userId}', [App\Http\Controllers\Api\ChargeController::class, 'index']);
Route::get('/notifications/{userId}', [App\Http\Controllers\Api\NotificationController::class, 'index']);
Route::get('/documents/{userId}', [App\Http\Controllers\Api\DocumentController::class, 'index']);