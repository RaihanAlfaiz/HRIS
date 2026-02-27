<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $status = $request->input('status');
        $query  = $user->scopedLeaveQuery()->with(['employee.department', 'approver'])->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $leaves = $query->paginate(20);
        $pendingCount  = (clone $user->scopedLeaveQuery())->where('status', 'pending')->count();
        $approvedCount = (clone $user->scopedLeaveQuery())->where('status', 'approved')->count();

        return view('leaves.index', compact('leaves', 'pendingCount', 'approvedCount', 'status'));
    }

    public function create()
    {
        $employees = auth()->user()->scopedEmployeeQuery()->orderBy('full_name')->get();
        return view('leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type'        => 'required|in:cuti_tahunan,cuti_sakit,cuti_melahirkan,cuti_menikah,cuti_khusus,izin',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'required|string|max:1000',
        ]);

        // Calculate days
        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end   = \Carbon\Carbon::parse($validated['end_date']);
        $validated['days'] = $start->diffInDays($end) + 1;

        Leave::create($validated);
        cache()->forget('pending_leaves_count');

        return redirect()->route('leaves.index')->with('success', 'Pengajuan cuti berhasil dibuat.');
    }

    public function approve(Leave $leave)
    {
        auth()->user()->authorizeSiteAccess($leave);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Cuti ini sudah diproses.');
        }

        $leave->update([
            'status'       => 'approved',
            'approved_by'  => auth()->id(),
            'responded_at' => now(),
        ]);

        // Update leave balance
        if ($leave->type === 'cuti_tahunan') {
            $balance = LeaveBalance::firstOrCreate(
                ['employee_id' => $leave->employee_id, 'year' => now()->year],
                ['total_quota' => 12, 'used' => 0, 'remaining' => 12]
            );
            $balance->increment('used', $leave->days);
            $balance->decrement('remaining', $leave->days);
        }

        cache()->forget('pending_leaves_count');

        return back()->with('success', 'Cuti berhasil disetujui.');
    }

    public function reject(Request $request, Leave $leave)
    {
        auth()->user()->authorizeSiteAccess($leave);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Cuti ini sudah diproses.');
        }

        $request->validate(['rejection_reason' => 'required|string|max:500']);

        $leave->update([
            'status'           => 'rejected',
            'approved_by'      => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
            'responded_at'     => now(),
        ]);

        cache()->forget('pending_leaves_count');

        return back()->with('success', 'Cuti berhasil ditolak.');
    }

    /**
     * Leave calendar — calendar data as JSON
     */
    public function calendar(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $start = \Carbon\Carbon::parse($month . '-01')->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $user = auth()->user();

        $leaves = $user->scopedLeaveQuery()->with('employee')
            ->where('status', 'approved')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end]);
            })
            ->get();

        $balances = LeaveBalance::with('employee')
            ->where('year', $start->year)
            ->when(!$user->isAdmin(), fn($q) => $q->whereHas('employee', fn($sq) => $sq->where('site_id', $user->site_id)))
            ->get();

        return view('leaves.calendar', compact('leaves', 'month', 'balances'));
    }
}
