<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('public.landing');
});

Auth::routes();

// Universal Dashboard Redirect
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/home', [DashboardController::class, 'index'])->middleware('auth')->name('home');

Route::middleware('auth')->group(function () {
    
    // Admin Routes
    Route::group(['prefix' => 'admin', 'middleware' => 'role:administrateur'], function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // Immeubles
        Route::get('/immeubles', [DashboardController::class, 'adminImmeubles'])->name('admin.immeubles');
        Route::post('/immeubles', [ImmeubleController::class, 'store'])->name('admin.immeubles.store');
        Route::put('/immeubles/{id}', [ImmeubleController::class, 'update'])->name('admin.immeubles.update');
        Route::delete('/immeubles/{id}', [ImmeubleController::class, 'destroy'])->name('admin.immeubles.destroy');

        // Residents
        Route::get('/residents', [DashboardController::class, 'adminResidents'])->name('admin.residents');
        Route::post('/residents', [UserController::class, 'storeResident'])->name('admin.residents.store');
        Route::put('/residents/{id}', [UserController::class, 'updateResident'])->name('admin.residents.update');
        Route::delete('/residents/{id}', [UserController::class, 'destroyUser'])->name('admin.residents.destroy');

        // Syndics
        Route::get('/syndics', [DashboardController::class, 'adminSyndics'])->name('admin.syndics');
        Route::post('/syndics', [UserController::class, 'storeSyndic'])->name('admin.syndics.store');
        Route::put('/syndics/{id}', [UserController::class, 'updateSyndic'])->name('admin.syndics.update');
        Route::delete('/syndics/{id}', [UserController::class, 'destroyUser'])->name('admin.syndics.destroy');

        Route::get('/paiements', [DashboardController::class, 'adminPaiements'])->name('admin.paiements');
        Route::get('/parametres', fn() => view('admin.administrateur.parametres'))->name('admin.parametres');
    });

    // Syndic Routes
    Route::group(['prefix' => 'syndic', 'middleware' => 'role:syndic'], function () {
        Route::get('/dashboard', [DashboardController::class, 'syndicDashboard'])->name('syndic.dashboard');
        
        // Immeubles (Syndic view)
        Route::get('/immeubles', [DashboardController::class, 'syndicImmeubles'])->name('syndic.immeubles');
        Route::post('/immeubles', [ImmeubleController::class, 'storeBySyndic'])->name('syndic.immeubles.store');
        Route::put('/immeubles/{id}', [ImmeubleController::class, 'updateBySyndic'])->name('syndic.immeubles.update');
        Route::delete('/immeubles/{id}', [ImmeubleController::class, 'destroyBySyndic'])->name('syndic.immeubles.destroy');

        // Residents (Syndic view)
        Route::get('/residents', [DashboardController::class, 'syndicResidents'])->name('syndic.residents');
        Route::post('/residents', [UserController::class, 'storeResidentBySyndic'])->name('syndic.residents.store');
        Route::put('/residents/{id}', [UserController::class, 'updateResidentBySyndic'])->name('syndic.residents.update');
        Route::delete('/residents/{id}', [UserController::class, 'destroyUserBySyndic'])->name('syndic.residents.destroy');

        Route::get('/paiements', [DashboardController::class, 'syndicPaiements'])->name('syndic.paiements');
        
        // Interventions
        Route::get('/interventions', [DashboardController::class, 'syndicInterventions'])->name('syndic.interventions');
        Route::post('/interventions', [IncidentController::class, 'store'])->name('syndic.interventions.store');
        Route::put('/interventions/{id}', [IncidentController::class, 'update'])->name('syndic.interventions.update');
        Route::delete('/interventions/{id}', [IncidentController::class, 'destroy'])->name('syndic.interventions.destroy');

        Route::get('/parametres', [DashboardController::class, 'syndicParametres'])->name('syndic.parametres');
    });

    // Resident Routes
    Route::group(['prefix' => 'resident', 'middleware' => 'role:resident'], function () {
        Route::get('/dashboard', [DashboardController::class, 'residentDashboard'])->name('resident.dashboard');
        Route::get('/paiements', [DashboardController::class, 'residentPaiements'])->name('resident.paiements');
        Route::get('/incidents', [DashboardController::class, 'residentIncidents'])->name('resident.incidents');
    });
});
