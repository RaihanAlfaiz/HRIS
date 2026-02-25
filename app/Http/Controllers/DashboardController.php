<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\Leave;
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

        // Today's attendance summary
        $todayDate = now()->toDateString();
        $todayPresent = Attendance::where('date', $todayDate)->whereIn('status', ['present', 'late'])->count();
        $todayLate    = Attendance::where('date', $todayDate)->where('status', 'late')->count();
        $todayAbsent  = $totalEmployees - Attendance::where('date', $todayDate)->count();

        // Pending leaves
        $pendingLeaves = Leave::with('employee')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();
        $pendingLeavesCount = Leave::where('status', 'pending')->count();

        // Active announcements
        $announcements = Announcement::active()
            ->latest()
            ->limit(3)
            ->get();

        // Birthday this month
        $birthdayEmployees = Employee::whereHas('profile', function ($q) {
            $q->whereMonth('date_of_birth', now()->month);
        })->with('profile')->limit(5)->get();

        return view('dashboard.index', compact(
            'totalEmployees',
            'totalDepartments',
            'totalSites',
            'statusCounts',
            'departmentCounts',
            'siteCounts',
            'recentEmployees',
            'expiringContracts',
            'todayPresent',
            'todayLate',
            'todayAbsent',
            'pendingLeaves',
            'pendingLeavesCount',
            'announcements',
            'birthdayEmployees',
        ));
    }
}
