<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeContractController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\EmployeeKpiController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SiteController;
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

    // Employee Contracts
    Route::post('employees/{employee}/contracts', [EmployeeContractController::class, 'store'])->name('employee-contracts.store');
    Route::put('employees/{employee}/contracts/{contract}', [EmployeeContractController::class, 'update'])->name('employee-contracts.update');
    Route::delete('employees/{employee}/contracts/{contract}', [EmployeeContractController::class, 'destroy'])->name('employee-contracts.destroy');

    // Employee KPIs
    Route::post('employees/{employee}/kpis', [EmployeeKpiController::class, 'store'])->name('employee-kpis.store');
    Route::delete('employees/{employee}/kpis/{kpi}', [EmployeeKpiController::class, 'destroy'])->name('employee-kpis.destroy');

    // Sites CRUD — admin & HR only for CUD, viewer can read
    Route::resource('sites', SiteController::class);

    // Departments CRUD
    Route::resource('departments', DepartmentController::class)->except(['show']);

    // Export
    Route::get('/export/employees', [ExportController::class, 'employees'])->name('export.employees');
});
