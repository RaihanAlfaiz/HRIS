<x-layouts.app :title="'Payroll'">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Payroll</h1>
                <p class="mt-1 text-sm text-gray-500">Slip gaji karyawan · Periode: {{ \App\Models\Payroll::make(['period' => $period])->period_label }}</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" class="flex items-center gap-2">
                    <input type="month" name="period" value="{{ $period }}" onchange="this.form.submit()"
                           class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                </form>
                @if(auth()->user()->hasRole('admin', 'hr'))
                <a href="{{ route('payrolls.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primary-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Buat Slip Gaji
                </a>
                @endif
            </div>
        </div>

        {{-- Summary --}}
        <div class="rounded-2xl border border-gray-200 bg-gradient-to-r from-primary-50 to-blue-50 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Gaji Bersih Periode Ini</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">Rp {{ number_format($totalNetSalary, 0, ',', '.') }}</p>
                </div>
                <div class="text-sm text-gray-500">{{ $payrolls->total() }} slip gaji</div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Karyawan</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Gaji Pokok</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-emerald-600">Total Pendapatan</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-red-600">Total Potongan</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-900">Gaji Bersih</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($payrolls as $payroll)
                            <tr class="transition hover:bg-gray-50/50">
                                <td class="whitespace-nowrap px-5 py-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $payroll->employee->full_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $payroll->employee->department?->name }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right text-sm text-gray-600">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-right text-sm font-medium text-emerald-600">Rp {{ number_format($payroll->total_earning, 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-right text-sm font-medium text-red-600">Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-right text-sm font-bold text-gray-900">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('payrolls.show', $payroll) }}" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-primary-600" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                        <a href="{{ route('payrolls.print', $payroll) }}" target="_blank" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-blue-600" title="Cetak">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">Belum ada slip gaji untuk periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $payrolls->appends(request()->query())->links() }}
    </div>
</x-layouts.app>
