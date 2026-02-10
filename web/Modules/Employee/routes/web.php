<?php

use Illuminate\Support\Facades\Route;
use Modules\Employee\Http\Controllers\EmployeeAssignmentController;
use Modules\Employee\Http\Controllers\EmployeeController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Employee Resource Routes
    Route::resource('employees', EmployeeController::class)->names('employee');

    // Employee Assignment Routes (nested under employees)
    Route::prefix('employees/{employee}')->name('employee.')->group(function () {
        Route::get('assignments', [EmployeeAssignmentController::class, 'index'])->name('assignments.index');
        Route::get('assignments/create', [EmployeeAssignmentController::class, 'create'])->name('assignments.create');
        Route::post('assignments', [EmployeeAssignmentController::class, 'store'])->name('assignments.store');
        Route::get('assignments/{assignment}/edit', [EmployeeAssignmentController::class, 'edit'])->name('assignments.edit');
        Route::put('assignments/{assignment}', [EmployeeAssignmentController::class, 'update'])->name('assignments.update');
        Route::delete('assignments/{assignment}', [EmployeeAssignmentController::class, 'destroy'])->name('assignments.destroy');
        Route::patch('assignments/{assignment}/toggle-active', [EmployeeAssignmentController::class, 'toggleActive'])->name('assignments.toggle-active');
    });
});
