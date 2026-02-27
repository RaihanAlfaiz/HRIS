<?php

namespace App\Http\Controllers;

use App\Exports\EmployeesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Export employees to Excel with current filters applied.
     */
    public function employees(Request $request)
    {
        $filename = 'employees_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new EmployeesExport(
                search: $request->input('search'),
                departmentId: $request->input('department_id'),
                status: $request->input('employment_status'),
                siteId: auth()->user()->isAdmin() ? null : auth()->user()->site_id,
            ),
            $filename
        );
    }
}
