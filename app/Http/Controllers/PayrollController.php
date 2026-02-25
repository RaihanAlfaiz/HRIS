<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', now()->format('Y-m'));

        $payrolls = Payroll::with('employee.department')
            ->where('period', $period)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalNetSalary = Payroll::where('period', $period)->sum('net_salary');

        return view('payrolls.index', compact('payrolls', 'period', 'totalNetSalary'));
    }

    public function create()
    {
        $employees = Employee::orderBy('full_name')->get();
        $period    = now()->format('Y-m');
        return view('payrolls.create', compact('employees', 'period'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'          => 'required|exists:employees,id',
            'period'               => 'required|string',
            'basic_salary'         => 'required|numeric|min:0',
            'transport_allowance'  => 'nullable|numeric|min:0',
            'meal_allowance'       => 'nullable|numeric|min:0',
            'other_allowance'      => 'nullable|numeric|min:0',
            'overtime'             => 'nullable|numeric|min:0',
            'bpjs_deduction'       => 'nullable|numeric|min:0',
            'tax_deduction'        => 'nullable|numeric|min:0',
            'other_deduction'      => 'nullable|numeric|min:0',
            'notes'                => 'nullable|string|max:500',
        ]);

        // Fill defaults
        foreach (['transport_allowance','meal_allowance','other_allowance','overtime','bpjs_deduction','tax_deduction','other_deduction'] as $field) {
            $validated[$field] = $validated[$field] ?? 0;
        }

        // Auto-calculate totals
        $validated['total_earning']   = $validated['basic_salary'] + $validated['transport_allowance'] + $validated['meal_allowance'] + $validated['other_allowance'] + $validated['overtime'];
        $validated['total_deduction'] = $validated['bpjs_deduction'] + $validated['tax_deduction'] + $validated['other_deduction'];
        $validated['net_salary']      = $validated['total_earning'] - $validated['total_deduction'];

        // Check duplicate
        $exists = Payroll::where('employee_id', $validated['employee_id'])
            ->where('period', $validated['period'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Slip gaji untuk periode ini sudah ada.');
        }

        Payroll::create($validated);

        return redirect()->route('payrolls.index', ['period' => $validated['period']])
            ->with('success', 'Slip gaji berhasil dibuat.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee.department');
        return view('payrolls.show', compact('payroll'));
    }

    public function destroy(Payroll $payroll)
    {
        $period = $payroll->period;
        $payroll->delete();
        return redirect()->route('payrolls.index', ['period' => $period])
            ->with('success', 'Slip gaji berhasil dihapus.');
    }

    /**
     * Export slip gaji as PDF (simple HTML-to-print)
     */
    public function print(Payroll $payroll)
    {
        $payroll->load('employee.department');
        return view('payrolls.print', compact('payroll'));
    }
}
