<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeDocumentController extends Controller
{
    /**
     * Allowed document types per DBML schema.
     */
    private const DOCUMENT_TYPES = ['Foto', 'CV', 'Portofolio PDF', 'Portofolio URL'];

    /**
     * File-based document types (these accept file uploads).
     */
    private const FILE_TYPES = ['Foto', 'CV', 'Portofolio PDF'];

    /**
     * Upload a new document (file or URL) for the employee.
     */
    public function store(Request $request, Employee $employee)
    {
        $documentType = $request->input('document_type');

        // Validate base fields
        $rules = [
            'document_type' => ['required', 'string', 'in:' . implode(',', self::DOCUMENT_TYPES)],
        ];

        // Conditional validation: URL type → require url_link, others → require file
        if ($documentType === 'Portofolio URL') {
            $rules['url_link'] = ['required', 'url', 'max:255'];
        } else {
            $allowedMimes = match ($documentType) {
                'Foto' => 'jpg,jpeg,png,webp',
                'CV' => 'pdf,doc,docx',
                'Portofolio PDF' => 'pdf',
                default => 'pdf,doc,docx,jpg,jpeg,png,webp',
            };
            $rules['file'] = ['required', 'file', 'mimes:' . $allowedMimes, 'max:5120']; // max 5MB
        }

        $validated = $request->validate($rules);

        $data = [
            'employee_id'   => $employee->id,
            'document_type' => $validated['document_type'],
            'file_path'     => null,
            'url_link'      => null,
        ];

        if ($documentType === 'Portofolio URL') {
            $data['url_link'] = $validated['url_link'];
        } else {
            // Store file: storage/app/public/documents/{employee_id}/uuid.ext
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $path = $file->storeAs("documents/{$employee->id}", $filename, 'public');
            $data['file_path'] = $path;
        }

        EmployeeDocument::create($data);

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    /**
     * Download a document file.
     */
    public function download(Employee $employee, EmployeeDocument $document)
    {
        // Ensure document belongs to this employee
        if ($document->employee_id !== $employee->id) {
            abort(403);
        }

        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($document->file_path);
    }

    /**
     * Delete a document.
     */
    public function destroy(Employee $employee, EmployeeDocument $document)
    {
        // Ensure document belongs to this employee
        if ($document->employee_id !== $employee->id) {
            abort(403);
        }

        // Delete physical file if exists
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
