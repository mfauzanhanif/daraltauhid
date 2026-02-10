<?php

use Illuminate\Support\Facades\Route;
use Modules\Asset\Http\Controllers\AssetController;
use Modules\Asset\Http\Controllers\BuildingController;
use Modules\Asset\Http\Controllers\RoomController;
use Modules\Asset\Http\Controllers\AssetLendingController;
use Modules\Asset\Http\Controllers\AssetMaintenanceController;

Route::middleware(['auth', 'verified'])->prefix('assets')->name('asset.')->group(function () {
    
    // Building Management
    Route::resource('buildings', BuildingController::class)->names('buildings');
    
    // Room Management
    Route::resource('rooms', RoomController::class)->names('rooms');
    
    // Asset Inventory
    Route::resource('items', AssetController::class)->names('assets');
    
    // Asset Lending/Borrowing
    Route::prefix('lendings')->name('lendings.')->group(function () {
        Route::get('/', [AssetLendingController::class, 'index'])->name('index');
        Route::get('/create', [AssetLendingController::class, 'create'])->name('create');
        Route::post('/', [AssetLendingController::class, 'store'])->name('store');
        Route::get('/{lending}', [AssetLendingController::class, 'show'])->name('show');
        Route::patch('/{lending}/approve', [AssetLendingController::class, 'approve'])->name('approve');
        Route::patch('/{lending}/reject', [AssetLendingController::class, 'reject'])->name('reject');
        Route::patch('/{lending}/pickup', [AssetLendingController::class, 'pickup'])->name('pickup');
        Route::patch('/{lending}/return', [AssetLendingController::class, 'return'])->name('return');
    });
    
    // Asset Maintenance/Repair
    Route::prefix('maintenances')->name('maintenances.')->group(function () {
        Route::get('/', [AssetMaintenanceController::class, 'index'])->name('index');
        Route::get('/create', [AssetMaintenanceController::class, 'create'])->name('create');
        Route::post('/', [AssetMaintenanceController::class, 'store'])->name('store');
        Route::get('/{maintenance}', [AssetMaintenanceController::class, 'show'])->name('show');
        Route::patch('/{maintenance}/review', [AssetMaintenanceController::class, 'review'])->name('review');
        Route::patch('/{maintenance}/start-repair', [AssetMaintenanceController::class, 'startRepair'])->name('start-repair');
        Route::patch('/{maintenance}/resolve', [AssetMaintenanceController::class, 'resolve'])->name('resolve');
        Route::patch('/{maintenance}/irreparable', [AssetMaintenanceController::class, 'irreparable'])->name('irreparable');
    });
});
