<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeContract;
use Illuminate\Http\Request;

class EmployeeContractController extends Controller
{
    /**
     * Store a new contract for the employee.
     */
    public function store(Request $request, Employee $employee)
    {
        auth()->user()->authorizeSiteAccess($employee);
        
        $validated = $request->validate([
            'contract_number' => ['nullable', 'string', 'max:100'],
            'contract_type'   => ['required', 'string', 'in:PKWT,PKWTT,Addendum'],
            'start_date'      => ['required', 'date'],
            'end_date'        => ['required', 'date', 'after:start_date'],
            'notes'           => ['nullable', 'string'],
        ]);

        $employee->contracts()->create($validated);

        return back()->with('success', 'Kontrak berhasil ditambahkan.');
    }

    /**
     * Update an existing contract.
     */
    public function update(Request $request, Employee $employee, EmployeeContract $contract)
    {
        auth()->user()->authorizeSiteAccess($employee);
        
        if ($contract->employee_id !== $employee->id) {
            abort(403);
        }

        $validated = $request->validate([
            'contract_number' => ['nullable', 'string', 'max:100'],
            'contract_type'   => ['required', 'string', 'in:PKWT,PKWTT,Addendum'],
            'start_date'      => ['required', 'date'],
            'end_date'        => ['required', 'date', 'after:start_date'],
            'notes'           => ['nullable', 'string'],
        ]);

        $contract->update($validated);

        return back()->with('success', 'Kontrak berhasil diperbarui.');
    }

    /**
     * Delete a contract.
     */
    public function destroy(Employee $employee, EmployeeContract $contract)
    {
        auth()->user()->authorizeSiteAccess($employee);
        
        if ($contract->employee_id !== $employee->id) {
            abort(403);
        }

        $contract->delete();

        return back()->with('success', 'Kontrak berhasil dihapus.');
    }
}
