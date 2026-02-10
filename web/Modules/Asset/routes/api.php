<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('api/v1/assets')->name('api.asset.')->group(function () {
    // API routes for Asset module can be added here
});
