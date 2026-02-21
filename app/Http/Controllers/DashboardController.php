<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with summary statistics.
     */
    public function index()
    {
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();

        $statusCounts = Employee::selectRaw('employment_status, COUNT(*) as total')
            ->groupBy('employment_status')
            ->pluck('total', 'employment_status');

        $departmentCounts = Department::withCount('employees')
            ->orderByDesc('employees_count')
            ->limit(10)
            ->get();

        $recentEmployees = Employee::with('department')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalEmployees',
            'totalDepartments',
            'statusCounts',
            'departmentCounts',
            'recentEmployees',
        ));
    }
}
