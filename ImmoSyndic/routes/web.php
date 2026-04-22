<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('public.landing');
});

// Auth Routes (Laravel UI)
Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Role-based Dashboards (Protected by Auth)
Route::middleware('auth')->group(function () {
    
    // Parent Admin Folder Organization
    Route::prefix('admin')->group(function () {
        
        // Administrateur Views
        Route::prefix('administrateur')->group(function () {
            Route::get('/dashboard', function () {
                return view('admin.administrateur.dashboard');
            })->name('admin.dashboard');
            
            Route::get('/residents', function () {
                return view('admin.administrateur.residents');
            })->name('admin.residents');

            Route::get('/syndics', function () {
                return view('admin.administrateur.syndics');
            })->name('admin.syndics');

            Route::get('/paiements', function () {
                return view('admin.administrateur.paiements');
            })->name('admin.paiements');

            Route::get('/signalements', function () {
                return view('admin.administrateur.signalements');
            })->name('admin.signalements');

            Route::get('/documents', function () {
                return view('admin.administrateur.documents');
            })->name('admin.documents');

            Route::get('/rapports', function () {
                return view('admin.administrateur.rapports');
            })->name('admin.rapports');

            Route::get('/parametres', function () {
                return view('admin.administrateur.parametres');
            })->name('admin.parametres');
        });

        // Syndic Views
        Route::prefix('syndic')->group(function () {
            Route::get('/dashboard', function () {
                return view('admin.syndic.dashboard');
            })->name('syndic.dashboard');
        });

        // Resident Views
        Route::prefix('resident')->group(function () {
            Route::get('/dashboard', function () {
                return view('admin.resident.dashboard');
            })->name('resident.dashboard');
        });
    });
});
