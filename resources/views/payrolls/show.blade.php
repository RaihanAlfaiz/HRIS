<x-layouts.app :title="'Detail Slip Gaji'">
    <div class="mx-auto max-w-2xl space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Slip Gaji</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $payroll->employee->full_name }} · {{ $payroll->period_label }}</p>
            </div>
            <a href="{{ route('payrolls.print', $payroll) }}" target="_blank"
               class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primary-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak / PDF
            </a>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-6">
            {{-- Employee Info --}}
            <div class="flex items-center gap-4 pb-4 border-b border-gray-100">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100 text-lg font-bold text-primary-600">
                    {{ strtoupper(substr($payroll->employee->full_name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $payroll->employee->full_name }}</p>
                    <p class="text-sm text-gray-500">{{ $payroll->employee->position }} · {{ $payroll->employee->department?->name }}</p>
                </div>
            </div>

            {{-- Pendapatan --}}
            <div>
                <h3 class="text-sm font-semibold text-emerald-700 mb-3">Pendapatan</h3>
                <dl class="space-y-2">
                    @foreach([
                        'Gaji Pokok' => $payroll->basic_salary,
                        'Tunjangan Transport' => $payroll->transport_allowance,
                        'Tunjangan Makan' => $payroll->meal_allowance,
                        'Tunjangan Lain' => $payroll->other_allowance,
                        'Lembur' => $payroll->overtime,
                    ] as $label => $amount)
                        @if($amount > 0)
                        <div class="flex justify-between px-3 py-1.5 odd:bg-gray-50/50 rounded-lg">
                            <dt class="text-sm text-gray-600">{{ $label }}</dt>
                            <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($amount, 0, ',', '.') }}</dd>
                        </div>
                        @endif
                    @endforeach
                    <div class="flex justify-between px-3 py-2 bg-emerald-50 rounded-lg border border-emerald-100">
                        <dt class="text-sm font-semibold text-emerald-700">Total Pendapatan</dt>
                        <dd class="text-sm font-bold text-emerald-700">Rp {{ number_format($payroll->total_earning, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Potongan --}}
            <div>
                <h3 class="text-sm font-semibold text-red-700 mb-3">Potongan</h3>
                <dl class="space-y-2">
                    @foreach([
                        'BPJS' => $payroll->bpjs_deduction,
                        'Pajak' => $payroll->tax_deduction,
                        'Potongan Lain' => $payroll->other_deduction,
                    ] as $label => $amount)
                        @if($amount > 0)
                        <div class="flex justify-between px-3 py-1.5 odd:bg-gray-50/50 rounded-lg">
                            <dt class="text-sm text-gray-600">{{ $label }}</dt>
                            <dd class="text-sm font-medium text-red-600">- Rp {{ number_format($amount, 0, ',', '.') }}</dd>
                        </div>
                        @endif
                    @endforeach
                    <div class="flex justify-between px-3 py-2 bg-red-50 rounded-lg border border-red-100">
                        <dt class="text-sm font-semibold text-red-700">Total Potongan</dt>
                        <dd class="text-sm font-bold text-red-700">- Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Net --}}
            <div class="flex justify-between items-center px-4 py-4 bg-gradient-to-r from-primary-50 to-blue-50 rounded-xl border border-primary-100">
                <span class="text-base font-bold text-gray-900">Gaji Bersih (Take Home Pay)</span>
                <span class="text-xl font-extrabold text-primary-700">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</x-layouts.app>
