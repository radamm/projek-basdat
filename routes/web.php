<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;

// == LANDING PAGE ==
Route::get('/', [ReportController::class, 'publicIndex'])->name('landing');
Route::get('/reports/public', [ReportController::class, 'publicReportsIndex'])->name('reports.public_index');

// == AUTH ==
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

// == AUTHENTICATED ROUTES ==
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // == PROFILE (SHARED BY ALL ROLES) ==
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // == PENGGUNA (ROLE: PENGGUNA) ==
    Route::middleware('role:pengguna')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
        Route::post('/reports/{report}/claims', [ClaimController::class, 'store'])->name('claims.store');
        Route::resource('reports', ReportController::class)->except(['destroy']);
        Route::resource('claims', ClaimController::class)->except(['destroy']);
    });

    // == PETUGAS (ROLE: PETUGAS) ==
    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
        
        // Reports Management by Petugas
        Route::get('/reports', [PetugasController::class, 'reports'])->name('reports');
        Route::get('/reports/create', [PetugasController::class, 'createReport'])->name('reports.create');
        Route::post('/reports', [PetugasController::class, 'storeReport'])->name('reports.store');
        Route::get('/reports/{report}', [PetugasController::class, 'showReport'])->name('reports.show');
        Route::get('/reports/{report}/edit', [PetugasController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}', [PetugasController::class, 'update'])->name('reports.update');
        Route::put('/reports/{report}/validate', [PetugasController::class, 'validateReport'])->name('reports.validate');
        Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

        // Claims Management by Petugas
        Route::get('/claims', [PetugasController::class, 'claims'])->name('claims');
        Route::get('/claims/{claim}', [ClaimController::class, 'show'])->name('claims.show');
        Route::put('/claims/{claim}/validate', [PetugasController::class, 'validateClaim'])->name('claims.validate');
        Route::delete('/claims/{claim}', [ClaimController::class, 'destroy'])->name('claims.destroy');
    });

    // == ADMIN (ROLE: ADMIN) ==
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Validation Pages
        Route::get('/reports/validation', [PetugasController::class, 'reports'])->name('reports.validation');
        Route::get('/claims/validation', [ClaimController::class, 'validation'])->name('claims.validation');
        
        // Report Actions
        Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
        Route::put('/reports/{report}/validate', [ReportController::class, 'validate'])->name('reports.validate');
        Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

        // Claim Actions
        Route::get('/claims/{claim}', [ClaimController::class, 'show'])->name('claims.show'); // Tambahan jika admin perlu lihat detail klaim
        Route::put('/claims/{claim}/validate', [ClaimController::class, 'validate'])->name('claims.validate');
        Route::delete('/claims/{claim}', [ClaimController::class, 'destroy'])->name('claims.destroy');

        // User Management (CRUD)
        Route::resource('users', UserController::class);
        
        // Category, Building, Room Management
        Route::resource('categories', CategoryController::class);
        Route::resource('buildings', BuildingController::class);
        Route::resource('rooms', RoomController::class);

        // Export Reports
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    });
});
