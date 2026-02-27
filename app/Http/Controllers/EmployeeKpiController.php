<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeKpi;
use Illuminate\Http\Request;

class EmployeeKpiController extends Controller
{
    /**
     * Store a new KPI rating for the employee.
     */
    public function store(Request $request, Employee $employee)
    {
        auth()->user()->authorizeSiteAccess($employee);
        
        $validated = $request->validate([
            'period'      => ['required', 'string', 'max:20'],
            'score'       => ['required', 'numeric', 'min:0', 'max:100'],
            'rating'      => ['required', 'string', 'in:Excellent,Good,Average,Below Average,Poor'],
            'notes'       => ['nullable', 'string'],
            'reviewed_by' => ['nullable', 'string', 'max:150'],
        ]);

        $employee->kpis()->create($validated);

        return back()->with('success', 'KPI berhasil ditambahkan.');
    }

    /**
     * Delete a KPI rating.
     */
    public function destroy(Employee $employee, EmployeeKpi $kpi)
    {
        auth()->user()->authorizeSiteAccess($employee);
        
        if ($kpi->employee_id !== $employee->id) {
            abort(403);
        }

        $kpi->delete();

        return back()->with('success', 'KPI berhasil dihapus.');
    }
}
