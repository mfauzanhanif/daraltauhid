<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');

Route::view('dashboard', 'pages.dashboard')

    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
