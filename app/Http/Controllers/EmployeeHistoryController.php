<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeHistory;
use Illuminate\Http\Request;

class EmployeeHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeHistory::with(['employee', 'changedByUser'])->latest();

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $histories = $query->paginate(30);
        $employees = Employee::orderBy('full_name')->get();

        return view('histories.index', compact('histories', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type'        => 'required|in:promotion,department_change,status_change,position_change,site_change,salary_change,other',
            'old_value'   => 'nullable|string|max:500',
            'new_value'   => 'nullable|string|max:500',
            'description' => 'required|string|max:1000',
        ]);

        $validated['changed_by'] = auth()->id();

        EmployeeHistory::create($validated);

        return back()->with('success', 'Riwayat perubahan berhasil ditambahkan.');
    }
}
