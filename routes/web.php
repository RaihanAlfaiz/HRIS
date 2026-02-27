<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeContractController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\EmployeeHistoryController;
use App\Http\Controllers\EmployeeKpiController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Guest (unauthenticated) ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ── Authenticated ──
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // All routes below require site assignment for non-admin users
    Route::middleware('site.assigned')->group(function () {
        // Dashboard (Accessible by all, but data scoped inside controller)
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // ── Karyawan Biasa / All Authenticated Users ──
        
        // Attendance (Personal)
        Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::post('/attendances/check-in', [AttendanceController::class, 'checkIn'])->name('attendances.check-in');
        Route::post('/attendances/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.check-out');
        Route::post('/attendances/corrections', [AttendanceController::class, 'storeCorrection'])->name('attendances.store-correction');

        // Leaves (Personal)
        Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
        Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
        Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');

        // Payrolls (Personal)
        Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
        Route::get('/payrolls/{payroll}', [PayrollController::class, 'show'])->name('payrolls.show')->where('payroll', '[0-9]+');
        Route::get('/payrolls/{payroll}/print', [PayrollController::class, 'print'])->name('payrolls.print')->where('payroll', '[0-9]+');


        // ── HR & Admin Only ──
        Route::middleware('role:admin,hr')->group(function () {
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

            // Sites & Departments
            Route::resource('sites', SiteController::class);
            Route::resource('departments', DepartmentController::class)->except(['show']);

            // Announcements
            Route::resource('announcements', AnnouncementController::class)->except(['show']);

            // Employee History
            Route::get('/histories', [EmployeeHistoryController::class, 'index'])->name('histories.index');
            Route::post('/histories', [EmployeeHistoryController::class, 'store'])->name('histories.store');

            // Export
            Route::get('/export/employees', [ExportController::class, 'employees'])->name('export.employees');

            // User Management
            Route::resource('users', UserController::class)->except(['show']);
            Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

            // ── Attendance (HR/Admin Management) ──
            Route::get('/attendances/recap', [AttendanceController::class, 'recap'])->name('attendances.recap');
            Route::put('/attendances/{attendance}/status', [AttendanceController::class, 'updateStatus'])->name('attendances.update-status');
            Route::post('/attendances/bulk-mark', [AttendanceController::class, 'bulkMark'])->name('attendances.bulk-mark');

            // Overtime management
            Route::get('/attendances/overtime', [AttendanceController::class, 'overtime'])->name('attendances.overtime');
            Route::put('/attendances/{attendance}/approve-overtime', [AttendanceController::class, 'approveOvertime'])->name('attendances.approve-overtime');
            Route::put('/attendances/{attendance}/reject-overtime', [AttendanceController::class, 'rejectOvertime'])->name('attendances.reject-overtime');

            // Attendance corrections
            Route::get('/attendances/corrections', [AttendanceController::class, 'corrections'])->name('attendances.corrections');
            Route::put('/attendances/corrections/{correction}/approve', [AttendanceController::class, 'approveCorrection'])->name('attendances.approve-correction');
            Route::put('/attendances/corrections/{correction}/reject', [AttendanceController::class, 'rejectCorrection'])->name('attendances.reject-correction');

            // Work shifts
            Route::get('/attendances/shifts', [AttendanceController::class, 'shifts'])->name('attendances.shifts');
            Route::post('/attendances/shifts', [AttendanceController::class, 'storeShift'])->name('attendances.store-shift');
            Route::put('/attendances/shifts/{shift}', [AttendanceController::class, 'updateShift'])->name('attendances.update-shift');
            Route::put('/attendances/shifts/{shift}/set-default', [AttendanceController::class, 'setDefaultShift'])->name('attendances.set-default-shift');

            // ── Leave / Cuti (HR/Admin Management) ──
            Route::get('/leaves/calendar', [LeaveController::class, 'calendar'])->name('leaves.calendar');
            Route::put('/leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
            Route::put('/leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');

            // Payrolls (HR/Admin Management)
            Route::get('/payrolls/create', [PayrollController::class, 'create'])->name('payrolls.create');
            Route::post('/payrolls', [PayrollController::class, 'store'])->name('payrolls.store');
            Route::delete('/payrolls/{payroll}', [PayrollController::class, 'destroy'])->name('payrolls.destroy');
        });
    });
});
