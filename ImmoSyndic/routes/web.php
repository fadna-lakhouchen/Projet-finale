<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImmeubleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DepenseController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('public.landing');
});

Auth::routes(['verify' => true]);
Route::get('/register/check-email', [\App\Http\Controllers\Auth\RegisterController::class, 'checkEmail'])->name('register.check-email');

// Universal Dashboard Redirect
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/home', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/notifications/read', [DashboardController::class, 'markNotificationsAsRead'])->name('notifications.read');
    Route::post('/notifications/{id}/read', [DashboardController::class, 'markSingleNotificationAsRead'])->name('notifications.read.single');
    
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
        Route::post('/syndics/{id}/toggle-status', [UserController::class, 'toggleSyndicStatus'])->name('admin.syndics.toggle-status');

        Route::get('/paiements', [DashboardController::class, 'adminPaiements'])->name('admin.paiements');
        Route::get('/signalements', [DashboardController::class, 'adminSignalements'])->name('admin.signalements');
        Route::get('/parametres', fn() => view('admin.administrateur.parametres'))->name('admin.parametres');
        
        // Documents
        Route::get('/documents', [DashboardController::class, 'adminDocuments'])->name('admin.documents');
        Route::post('/documents', [DocumentController::class, 'storeByAdmin'])->name('admin.documents.store');
        Route::delete('/documents/{id}', [DocumentController::class, 'destroyByAdmin'])->name('admin.documents.destroy');

        // Dépenses (Charges Immeuble)
        Route::get('/depenses', [DashboardController::class, 'adminDepenses'])->name('admin.depenses');

        // Logs Système
        Route::get('/logs', [DashboardController::class, 'adminLogs'])->name('admin.logs');
        Route::post('/depenses', [DepenseController::class, 'storeByAdmin'])->name('admin.depenses.store');
        Route::delete('/depenses/{id}', [DepenseController::class, 'destroyByAdmin'])->name('admin.depenses.destroy');
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
        Route::post('/residents/{id}/activate', [UserController::class, 'activateResidentBySyndic'])->name('syndic.residents.activate');

        Route::get('/paiements', [DashboardController::class, 'syndicPaiements'])->name('syndic.paiements');
        Route::post('/paiements', [PaiementController::class, 'store'])->name('syndic.paiements.store');
        Route::put('/paiements/{id}', [PaiementController::class, 'update'])->name('syndic.paiements.update');
        Route::delete('/paiements/{id}', [PaiementController::class, 'destroy'])->name('syndic.paiements.destroy');
        Route::get('/paiements/{id}/receipt', [PaiementController::class, 'generateReceipt'])->name('syndic.paiements.receipt');
        Route::get('/paiements/export/excel', [PaiementController::class, 'exportExcel'])->name('syndic.paiements.export.excel');
        Route::get('/paiements/export/pdf', [PaiementController::class, 'exportPdf'])->name('syndic.paiements.export.pdf');
        
        // Interventions
        Route::get('/interventions', [DashboardController::class, 'syndicInterventions'])->name('syndic.interventions');
        Route::post('/interventions', [IncidentController::class, 'store'])->name('syndic.interventions.store');
        Route::put('/interventions/{id}', [IncidentController::class, 'update'])->name('syndic.interventions.update');
        Route::delete('/interventions/{id}', [IncidentController::class, 'destroy'])->name('syndic.interventions.destroy');

        Route::get('/parametres', [DashboardController::class, 'syndicParametres'])->name('syndic.parametres');

        // Annonces
        Route::get('/annonces', [DashboardController::class, 'syndicAnnonces'])->name('syndic.annonces');
        Route::post('/annonces', [AnnonceController::class, 'store'])->name('syndic.annonces.store');
        Route::put('/annonces/{id}', [AnnonceController::class, 'update'])->name('syndic.annonces.update');
        Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy'])->name('syndic.annonces.destroy');

        // Documents
        Route::get('/documents', [DashboardController::class, 'syndicDocuments'])->name('syndic.documents');
        Route::post('/documents', [DocumentController::class, 'storeBySyndic'])->name('syndic.documents.store');
        Route::delete('/documents/{id}', [DocumentController::class, 'destroyBySyndic'])->name('syndic.documents.destroy');

        // Dépenses (Charges Immeuble)
        Route::get('/depenses', [DashboardController::class, 'syndicDepenses'])->name('syndic.depenses');
        Route::post('/depenses', [DepenseController::class, 'storeBySyndic'])->name('syndic.depenses.store');
        Route::delete('/depenses/{id}', [DepenseController::class, 'destroyBySyndic'])->name('syndic.depenses.destroy');

        // Secondary Syndics Management
        Route::get('/secondary-syndics', [DashboardController::class, 'syndicSecondarySyndics'])->name('syndic.secondary-syndics');
        Route::post('/secondary-syndics', [UserController::class, 'storeSecondarySyndicBySyndic'])->name('syndic.secondary-syndics.store');
        Route::delete('/secondary-syndics/{id}', [UserController::class, 'destroySecondarySyndicBySyndic'])->name('syndic.secondary-syndics.destroy');
        Route::post('/secondary-syndics/{id}/transfer-primary', [UserController::class, 'transferPrimaryBySyndic'])->name('syndic.secondary-syndics.transfer-primary');

        // Activity Logs for Syndic 1
        Route::get('/logs', [DashboardController::class, 'syndicLogs'])->name('syndic.logs');
    });

    // Resident Routes
    Route::group(['prefix' => 'resident', 'middleware' => 'role:resident'], function () {
        Route::get('/dashboard', [DashboardController::class, 'residentDashboard'])->name('resident.dashboard');
        Route::post('/ready-to-pay', [DashboardController::class, 'signalReadyToPay'])->name('resident.ready-to-pay');
        Route::get('/paiements', [DashboardController::class, 'residentPaiements'])->name('resident.paiements');
        Route::get('/incidents', [DashboardController::class, 'residentIncidents'])->name('resident.incidents');
        Route::post('/incidents', [IncidentController::class, 'storeResidentIncident'])->name('resident.incidents.store');
        Route::get('/annonces', [DashboardController::class, 'residentAnnonces'])->name('resident.annonces');
        Route::get('/documents', [DashboardController::class, 'residentDocuments'])->name('resident.documents');
        Route::get('/parametres', [DashboardController::class, 'residentParametres'])->name('resident.parametres');
    });
});
