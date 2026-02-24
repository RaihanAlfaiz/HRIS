<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\Site;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with summary statistics.
     */
    public function index()
    {
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        $totalSites = Site::count();

        $statusCounts = Employee::selectRaw('employment_status, COUNT(*) as total')
            ->groupBy('employment_status')
            ->pluck('total', 'employment_status');

        $departmentCounts = Department::withCount('employees')
            ->orderByDesc('employees_count')
            ->limit(10)
            ->get();

        $siteCounts = Site::withCount('employees')
            ->orderByDesc('employees_count')
            ->limit(10)
            ->get();

        $recentEmployees = Employee::with('department')
            ->latest()
            ->limit(5)
            ->get();

        // Contracts expiring within 30 days
        $expiringContracts = EmployeeContract::with('employee.site')
            ->where('end_date', '>=', now())
            ->where('end_date', '<=', now()->addDays(30))
            ->orderBy('end_date')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'totalEmployees',
            'totalDepartments',
            'totalSites',
            'statusCounts',
            'departmentCounts',
            'siteCounts',
            'recentEmployees',
            'expiringContracts',
        ));
    }
}
