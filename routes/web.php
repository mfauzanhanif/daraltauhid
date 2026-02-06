<?php

use App\Http\Controllers\InstitutionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// Institution Selection Routes (requires auth)
Route::middleware(['auth'])->group(function () {
    Route::get('institution/select', [InstitutionController::class, 'select'])
        ->name('institution.select');

    Route::post('institution/switch', [InstitutionController::class, 'switch'])
        ->name('institution.switch');
});

Route::get('dashboard', function () {
    return Inertia::render('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
