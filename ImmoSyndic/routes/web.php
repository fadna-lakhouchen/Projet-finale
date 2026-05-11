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
        Route::get('/immeubles', [DashboardController::class, 'adminImmeubles'])->name('admin.immeubles');
        Route::get('/residents', [DashboardController::class, 'adminResidents'])->name('admin.residents');
        Route::get('/syndics', [DashboardController::class, 'adminSyndics'])->name('admin.syndics');
        Route::get('/paiements', [DashboardController::class, 'adminPaiements'])->name('admin.paiements');
        Route::get('/parametres', fn() => view('admin.administrateur.parametres'))->name('admin.parametres');
    });

    // Syndic Routes
    Route::group(['prefix' => 'syndic', 'middleware' => 'role:syndic'], function () {
        Route::get('/dashboard', [DashboardController::class, 'syndicDashboard'])->name('syndic.dashboard');
        Route::get('/immeubles', [DashboardController::class, 'syndicImmeubles'])->name('syndic.immeubles');
        Route::get('/residents', [DashboardController::class, 'syndicResidents'])->name('syndic.residents');
        Route::get('/paiements', [DashboardController::class, 'syndicPaiements'])->name('syndic.paiements');
        Route::get('/interventions', [DashboardController::class, 'syndicInterventions'])->name('syndic.interventions');
        Route::get('/parametres', [DashboardController::class, 'syndicParametres'])->name('syndic.parametres');
    });

    // Resident Routes
    Route::group(['prefix' => 'resident', 'middleware' => 'role:resident'], function () {
        Route::get('/dashboard', [DashboardController::class, 'residentDashboard'])->name('resident.dashboard');
        Route::get('/paiements', [DashboardController::class, 'residentPaiements'])->name('resident.paiements');
        Route::get('/incidents', [DashboardController::class, 'residentIncidents'])->name('resident.incidents');
    });
});
