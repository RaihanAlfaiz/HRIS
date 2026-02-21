<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    use Exportable;

    public function __construct(
        protected ?string $search = null,
        protected ?string $departmentId = null,
        protected ?string $status = null,
    ) {}

    /**
     * Build the query with applied filters.
     */
    public function query()
    {
        $query = Employee::with(['department', 'profile', 'contact', 'financial']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('full_name', 'LIKE', "%{$this->search}%")
                  ->orWhere('nip', 'LIKE', "%{$this->search}%")
                  ->orWhere('position', 'LIKE', "%{$this->search}%");
            });
        }

        if ($this->departmentId) {
            $query->where('department_id', $this->departmentId);
        }

        if ($this->status) {
            $query->where('employment_status', $this->status);
        }

        return $query->orderBy('full_name');
    }

    /**
     * Excel column headings.
     */
    public function headings(): array
    {
        return [
            'NIP',
            'Nama Lengkap',
            'Departemen',
            'Jabatan',
            'Status',
            'Tanggal Bergabung',
            'NIK KTP',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Agama',
            'Status Pernikahan',
            'Golongan Darah',
            'Alamat KTP',
            'Alamat Domisili',
            'Email Kantor',
            'Email Pribadi',
            'No. Telepon',
            'Kontak Darurat',
            'Telepon Darurat',
            'Hubungan Darurat',
            'NPWP',
            'BPJS Kesehatan',
            'BPJS Ketenagakerjaan',
            'Nama Bank',
            'No. Rekening',
        ];
    }

    /**
     * Map each employee row to column values.
     */
    public function map($employee): array
    {
        return [
            $employee->nip,
            $employee->full_name,
            $employee->department?->name,
            $employee->position,
            $employee->employment_status,
            $employee->join_date?->format('d/m/Y'),
            $employee->profile?->nik_ktp,
            $employee->profile?->place_of_birth,
            $employee->profile?->date_of_birth?->format('d/m/Y'),
            $employee->profile?->gender,
            $employee->profile?->religion,
            $employee->profile?->marital_status,
            $employee->profile?->blood_type,
            $employee->profile?->address_ktp,
            $employee->profile?->address_domicile,
            $employee->contact?->email_work,
            $employee->contact?->email_personal,
            $employee->contact?->phone_number,
            $employee->contact?->emergency_contact_name,
            $employee->contact?->emergency_contact_phone,
            $employee->contact?->emergency_contact_relation,
            $employee->financial?->npwp,
            $employee->financial?->bpjs_kesehatan,
            $employee->financial?->bpjs_ketenagakerjaan,
            $employee->financial?->bank_name,
            $employee->financial?->bank_account_number,
        ];
    }

    /**
     * Style the header row.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E40AF'],
                ],
            ],
        ];
    }
}
