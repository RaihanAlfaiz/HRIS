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
     * Scoped to user's site (admin sees all).
     */
    public function index()
    {
        $user = auth()->user();
        $employeeQuery = $user->scopedEmployeeQuery();

        $totalEmployees = (clone $employeeQuery)->count();
        $totalDepartments = Department::count();
        $totalSites = Site::count();

        $statusCounts = (clone $employeeQuery)->selectRaw('employment_status, COUNT(*) as total')
            ->groupBy('employment_status')
            ->pluck('total', 'employment_status');

        // Department counts — scoped to site
        if ($user->isAdmin()) {
            $departmentCounts = Department::withCount('employees')
                ->orderByDesc('employees_count')
                ->limit(10)
                ->get();
        } else {
            $departmentCounts = Department::withCount(['employees' => fn($q) => $q->where('site_id', $user->site_id)])
                ->having('employees_count', '>', 0)
                ->orderByDesc('employees_count')
                ->limit(10)
                ->get();
        }

        // Site counts — only for admin (they see all sites)
        $siteCounts = $user->isAdmin()
            ? Site::withCount('employees')->orderByDesc('employees_count')->limit(10)->get()
            : collect();

        $recentEmployees = (clone $employeeQuery)->with('department')
            ->latest()
            ->limit(5)
            ->get();

        // Contracts expiring within 30 days — scoped to site
        $expiringContracts = EmployeeContract::with('employee.site')
            ->whereHas('employee', function ($q) use ($user) {
                if (!$user->isAdmin()) {
                    $q->where('site_id', $user->site_id);
                }
            })
            ->where('end_date', '>=', now())
            ->where('end_date', '<=', now()->addDays(30))
            ->orderBy('end_date')
            ->limit(10)
            ->get();

        // Today's attendance summary — scoped to site
        $todayDate = now()->toDateString();
        $attendanceQuery = $user->scopedAttendanceQuery()->where('date', $todayDate);
        $todayPresent = (clone $attendanceQuery)->whereIn('status', ['present', 'late'])->count();
        $todayLate    = (clone $attendanceQuery)->where('status', 'late')->count();
        $todayAbsent  = $totalEmployees - (clone $attendanceQuery)->count();

        // Pending leaves — scoped to site
        $leaveBaseQuery = $user->scopedLeaveQuery();
        $pendingLeaves = (clone $leaveBaseQuery)->with('employee')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();
        $pendingLeavesCount = (clone $leaveBaseQuery)->where('status', 'pending')->count();

        // Active announcements (global — no site scoping)
        $announcements = Announcement::active()
            ->latest()
            ->limit(3)
            ->get();

        // Birthday this month — scoped to site
        $birthdayQuery = (clone $employeeQuery);
        $birthdayEmployees = $birthdayQuery->whereHas('profile', function ($q) {
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
