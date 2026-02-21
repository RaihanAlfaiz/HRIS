<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

// ── Guest (unauthenticated) ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ── Authenticated ──
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Employees CRUD
    Route::resource('employees', EmployeeController::class);

    // Employee Documents (upload, download, delete)
    Route::post('employees/{employee}/documents', [EmployeeDocumentController::class, 'store'])->name('employee-documents.store');
    Route::get('employees/{employee}/documents/{document}/download', [EmployeeDocumentController::class, 'download'])->name('employee-documents.download');
    Route::delete('employees/{employee}/documents/{document}', [EmployeeDocumentController::class, 'destroy'])->name('employee-documents.destroy');

    // Departments CRUD
    Route::resource('departments', DepartmentController::class)->except(['show']);

    // Export
    Route::get('/export/employees', [ExportController::class, 'employees'])->name('export.employees');
});
