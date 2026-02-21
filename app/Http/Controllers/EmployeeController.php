<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeContact;
use App\Models\EmployeeFinancial;
use App\Models\EmployeeProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display the employee directory with search & filters.
     */
    public function index(Request $request)
    {
        $query = Employee::with('department');

        // ── Instant search (full_name, nip, position) ──
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('nip', 'LIKE', "%{$search}%")
                  ->orWhere('position', 'LIKE', "%{$search}%");
            });
        }

        // ── Filter by department ──
        if ($departmentId = $request->input('department_id')) {
            $query->where('department_id', $departmentId);
        }

        // ── Filter by employment status ──
        if ($status = $request->input('employment_status')) {
            $query->where('employment_status', $status);
        }

        // ── Sorting ──
        $sortField = $request->input('sort', 'full_name');
        $sortDirection = $request->input('direction', 'asc');
        $allowedSorts = ['full_name', 'nip', 'position', 'employment_status', 'join_date'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        }

        $employees = $query->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();
        $statuses = collect(['Permanent', 'Contract', 'Probation', 'Internship']);

        return view('employees.index', compact('employees', 'departments', 'statuses'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('employees.create', compact('departments'));
    }

    /**
     * Store a newly created employee with all related data.
     */
    public function store(Request $request)
    {
        $validated = $this->validateEmployee($request);

        DB::transaction(function () use ($validated) {
            $employee = Employee::create($validated['employee']);

            if (!empty($validated['profile'])) {
                $employee->profile()->create($validated['profile']);
            }

            if (!empty($validated['contact'])) {
                $employee->contact()->create($validated['contact']);
            }

            if (!empty($validated['financial'])) {
                $employee->financial()->create($validated['financial']);
            }
        });

        return redirect()
            ->route('employees.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    /**
     * Display the specified employee with all details.
     */
    public function show(Employee $employee)
    {
        $employee->load(['department', 'profile', 'contact', 'documents', 'financial']);

        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        $employee->load(['profile', 'contact', 'financial']);
        $departments = Department::orderBy('name')->get();

        return view('employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update the specified employee and all related data.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $this->validateEmployee($request, $employee->id);

        DB::transaction(function () use ($validated, $employee) {
            $employee->update($validated['employee']);

            if (!empty($validated['profile'])) {
                $employee->profile()->updateOrCreate(
                    ['employee_id' => $employee->id],
                    $validated['profile']
                );
            }

            if (!empty($validated['contact'])) {
                $employee->contact()->updateOrCreate(
                    ['employee_id' => $employee->id],
                    $validated['contact']
                );
            }

            if (!empty($validated['financial'])) {
                $employee->financial()->updateOrCreate(
                    ['employee_id' => $employee->id],
                    $validated['financial']
                );
            }
        });

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified employee.
     */
    public function destroy(Employee $employee)
    {
        DB::transaction(function () use ($employee) {
            // Delete document files from storage
            foreach ($employee->documents as $doc) {
                if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                    Storage::disk('public')->delete($doc->file_path);
                }
            }

            // Delete all related records
            $employee->documents()->delete();
            $employee->profile()?->delete();
            $employee->contact()?->delete();
            $employee->financial()?->delete();

            // Delete the employee
            $employee->delete();
        });

        return redirect()
            ->route('employees.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }

    /**
     * Validate employee request data (reusable for create & update).
     */
    private function validateEmployee(Request $request, ?int $employeeId = null): array
    {
        $data = $request->validate([
            // Employee (core)
            'nip'                => ['required', 'string', 'max:50', Rule::unique('employees', 'nip')->ignore($employeeId)],
            'full_name'          => ['required', 'string', 'max:150'],
            'department_id'      => ['required', 'exists:departments,id'],
            'position'           => ['required', 'string', 'max:100'],
            'employment_status'  => ['required', 'string', 'max:50'],
            'join_date'          => ['required', 'date'],

            // Profile
            'nik_ktp'            => ['nullable', 'string', 'max:20', Rule::unique('employee_profiles', 'nik_ktp')->ignore($employeeId, 'employee_id')],
            'place_of_birth'     => ['nullable', 'string', 'max:100'],
            'date_of_birth'      => ['nullable', 'date'],
            'gender'             => ['nullable', 'string', 'max:10'],
            'religion'           => ['nullable', 'string', 'max:50'],
            'marital_status'     => ['nullable', 'string', 'max:50'],
            'blood_type'         => ['nullable', 'string', 'max:5'],
            'address_ktp'        => ['nullable', 'string'],
            'address_domicile'   => ['nullable', 'string'],

            // Contact
            'email_work'                  => ['nullable', 'email', 'max:150', Rule::unique('employee_contacts', 'email_work')->ignore($employeeId, 'employee_id')],
            'email_personal'              => ['nullable', 'email', 'max:150'],
            'phone_number'                => ['nullable', 'string', 'max:20'],
            'emergency_contact_name'      => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone'     => ['nullable', 'string', 'max:20'],
            'emergency_contact_relation'  => ['nullable', 'string', 'max:50'],

            // Financial
            'npwp'                   => ['nullable', 'string', 'max:50'],
            'bpjs_kesehatan'         => ['nullable', 'string', 'max:50'],
            'bpjs_ketenagakerjaan'   => ['nullable', 'string', 'max:50'],
            'bank_name'              => ['nullable', 'string', 'max:50'],
            'bank_account_number'    => ['nullable', 'string', 'max:50'],
        ]);

        return [
            'employee' => array_intersect_key($data, array_flip([
                'department_id', 'nip', 'full_name', 'position', 'employment_status', 'join_date',
            ])),
            'profile' => array_filter(array_intersect_key($data, array_flip([
                'nik_ktp', 'place_of_birth', 'date_of_birth', 'gender', 'religion',
                'marital_status', 'blood_type', 'address_ktp', 'address_domicile',
            ])), fn($v) => $v !== null),
            'contact' => array_filter(array_intersect_key($data, array_flip([
                'email_work', 'email_personal', 'phone_number',
                'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
            ])), fn($v) => $v !== null),
            'financial' => array_filter(array_intersect_key($data, array_flip([
                'npwp', 'bpjs_kesehatan', 'bpjs_ketenagakerjaan', 'bank_name', 'bank_account_number',
            ])), fn($v) => $v !== null),
        ];
    }
}
