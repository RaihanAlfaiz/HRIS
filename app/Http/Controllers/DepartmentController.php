<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display all departments.
     */
    public function index()
    {
        $departments = Department::withCount('employees')
            ->orderBy('name')
            ->paginate(15);

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:departments,name'],
        ]);

        Department::create($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Departemen berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:departments,name,' . $department->id],
        ]);

        $department->update($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Departemen berhasil diperbarui.');
    }

    /**
     * Remove the specified department.
     */
    public function destroy(Department $department)
    {
        if ($department->employees()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus departemen yang masih memiliki karyawan.');
        }

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Departemen berhasil dihapus.');
    }
}
