<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji — {{ $payroll->employee->full_name }} · {{ $payroll->period_label }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; font-size: 13px; color: #1f2937; padding: 40px; }
        .header { text-align: center; border-bottom: 2px solid #3b82f6; padding-bottom: 16px; margin-bottom: 24px; }
        .header h1 { font-size: 20px; font-weight: 700; color: #1e40af; }
        .header p { font-size: 12px; color: #6b7280; margin-top: 4px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 24px; }
        .info-grid .label { font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; }
        .info-grid .value { font-size: 13px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f9fafb; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600; }
        td.amount { text-align: right; font-family: 'Consolas', monospace; }
        .total-row { background: #f0fdf4; font-weight: 700; }
        .total-row.deduction { background: #fef2f2; }
        .net-row { background: #eff6ff; font-size: 16px; font-weight: 800; }
        .net-row td { padding: 12px; border-top: 2px solid #3b82f6; }
        .footer { margin-top: 40px; text-align: center; font-size: 11px; color: #9ca3af; }
        @media print {
            body { padding: 20px; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>SLIP GAJI KARYAWAN</h1>
        <p>Periode: {{ $payroll->period_label }}</p>
    </div>

    <div class="info-grid">
        <div><span class="label">Nama</span><br><span class="value">{{ $payroll->employee->full_name }}</span></div>
        <div><span class="label">NIP</span><br><span class="value">{{ $payroll->employee->nip }}</span></div>
        <div><span class="label">Jabatan</span><br><span class="value">{{ $payroll->employee->position }}</span></div>
        <div><span class="label">Departemen</span><br><span class="value">{{ $payroll->employee->department?->name ?? '—' }}</span></div>
    </div>

    <table>
        <thead><tr><th colspan="2">PENDAPATAN</th></tr></thead>
        <tbody>
            <tr><td>Gaji Pokok</td><td class="amount">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td></tr>
            @if($payroll->transport_allowance > 0)<tr><td>Tunjangan Transport</td><td class="amount">Rp {{ number_format($payroll->transport_allowance, 0, ',', '.') }}</td></tr>@endif
            @if($payroll->meal_allowance > 0)<tr><td>Tunjangan Makan</td><td class="amount">Rp {{ number_format($payroll->meal_allowance, 0, ',', '.') }}</td></tr>@endif
            @if($payroll->other_allowance > 0)<tr><td>Tunjangan Lain</td><td class="amount">Rp {{ number_format($payroll->other_allowance, 0, ',', '.') }}</td></tr>@endif
            @if($payroll->overtime > 0)<tr><td>Lembur</td><td class="amount">Rp {{ number_format($payroll->overtime, 0, ',', '.') }}</td></tr>@endif
            <tr class="total-row"><td><strong>Total Pendapatan</strong></td><td class="amount"><strong>Rp {{ number_format($payroll->total_earning, 0, ',', '.') }}</strong></td></tr>
        </tbody>
    </table>

    <table>
        <thead><tr><th colspan="2">POTONGAN</th></tr></thead>
        <tbody>
            @if($payroll->bpjs_deduction > 0)<tr><td>BPJS</td><td class="amount">Rp {{ number_format($payroll->bpjs_deduction, 0, ',', '.') }}</td></tr>@endif
            @if($payroll->tax_deduction > 0)<tr><td>Pajak</td><td class="amount">Rp {{ number_format($payroll->tax_deduction, 0, ',', '.') }}</td></tr>@endif
            @if($payroll->other_deduction > 0)<tr><td>Potongan Lain</td><td class="amount">Rp {{ number_format($payroll->other_deduction, 0, ',', '.') }}</td></tr>@endif
            <tr class="total-row deduction"><td><strong>Total Potongan</strong></td><td class="amount"><strong>Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</strong></td></tr>
        </tbody>
    </table>

    <table>
        <tbody>
            <tr class="net-row"><td>GAJI BERSIH (TAKE HOME PAY)</td><td class="amount">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td></tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem HRIS pada {{ now()->translatedFormat('d F Y, H:i') }}</p>
    </div>
</body>
</html>
