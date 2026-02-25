<x-layouts.app :title="'Rekap Absensi'">
    <x-slot:headerActions>
        <a href="{{ route('attendances.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            ← Kembali
        </a>
    </x-slot:headerActions>

    <div class="space-y-6">
        {{-- Filters --}}
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-gray-100">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Periode</label>
                    <input type="month" name="month" value="{{ $month }}"
                           class="rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                           onchange="this.form.submit()">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Departemen</label>
                    <select name="department_id" class="rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-sm min-w-40 transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Karyawan</label>
                    <select name="employee_id" class="select-search min-w-48" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        @foreach($allEmployees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        {{-- Summary cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            @php
                $totalPresent = $recap->sum('present');
                $totalLate    = $recap->sum('late');
                $totalAbsent  = $recap->sum('absent');
                $avgPct       = $recap->count() > 0 ? round($recap->avg('attendance_pct')) : 0;
            @endphp
            <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-4 text-white shadow-lg shadow-emerald-500/20">
                <p class="text-emerald-100 text-sm font-medium">Total Hadir</p>
                <p class="text-3xl font-bold mt-1">{{ $totalPresent }}</p>
                <p class="text-emerald-200 text-xs">hari × karyawan</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 p-4 text-white shadow-lg shadow-amber-500/20">
                <p class="text-amber-100 text-sm font-medium">Total Terlambat</p>
                <p class="text-3xl font-bold mt-1">{{ $totalLate }}</p>
                <p class="text-amber-200 text-xs">kejadian</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-red-500 to-red-600 p-4 text-white shadow-lg shadow-red-500/20">
                <p class="text-red-100 text-sm font-medium">Total Alpa</p>
                <p class="text-3xl font-bold mt-1">{{ $totalAbsent }}</p>
                <p class="text-red-200 text-xs">kejadian</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 p-4 text-white shadow-lg shadow-primary-500/20">
                <p class="text-primary-100 text-sm font-medium">Rata-rata Kehadiran</p>
                <p class="text-3xl font-bold mt-1">{{ $avgPct }}%</p>
                <p class="text-primary-200 text-xs">dari {{ $daysInMonth }} hari</p>
            </div>
        </div>

        {{-- Recap table --}}
        <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 text-left">
                            <th class="px-4 py-3 font-semibold text-gray-600 w-8">#</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 min-w-48">Karyawan</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">
                                <span class="inline-flex items-center gap-1 text-emerald-600">✓ Hadir</span>
                            </th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">
                                <span class="inline-flex items-center gap-1 text-amber-600">⏰ Telat</span>
                            </th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">
                                <span class="inline-flex items-center gap-1 text-red-600">✗ Alpa</span>
                            </th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">
                                <span class="inline-flex items-center gap-1 text-orange-600">🏥 Sakit</span>
                            </th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">
                                <span class="inline-flex items-center gap-1 text-blue-600">🏖 Cuti</span>
                            </th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">Tot. Telat</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">Lembur</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">Jam Kerja</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recap as $i => $r)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700">
                                        {{ strtoupper(substr($r['employee']->full_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $r['employee']->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $r['employee']->department?->name ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-sm font-bold text-emerald-700">{{ $r['present'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $r['late'] > 0 ? 'bg-amber-50 text-amber-700' : 'bg-gray-50 text-gray-400' }} text-sm font-bold">{{ $r['late'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $r['absent'] > 0 ? 'bg-red-50 text-red-700' : 'bg-gray-50 text-gray-400' }} text-sm font-bold">{{ $r['absent'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $r['sick'] > 0 ? 'bg-orange-50 text-orange-700' : 'bg-gray-50 text-gray-400' }} text-sm font-bold">{{ $r['sick'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $r['leave'] > 0 ? 'bg-blue-50 text-blue-700' : 'bg-gray-50 text-gray-400' }} text-sm font-bold">{{ $r['leave'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($r['total_late_mins'] > 0)
                                    @php $h = intdiv($r['total_late_mins'], 60); $m = $r['total_late_mins'] % 60; @endphp
                                    <span class="text-amber-700 font-medium text-xs">{{ $h > 0 ? "{$h}j {$m}m" : "{$m}m" }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($r['total_overtime'] > 0)
                                    @php $oh = intdiv($r['total_overtime'], 60); $om = $r['total_overtime'] % 60; @endphp
                                    <span class="text-indigo-700 font-medium text-xs">{{ $oh > 0 ? "{$oh}j {$om}m" : "{$om}m" }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-mono text-sm font-medium text-gray-700">{{ $r['total_work_hours'] }}h</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $r['attendance_pct'] >= 80 ? 'bg-emerald-500' : ($r['attendance_pct'] >= 50 ? 'bg-amber-500' : 'bg-red-500') }}"
                                             style="width: {{ $r['attendance_pct'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold {{ $r['attendance_pct'] >= 80 ? 'text-emerald-700' : ($r['attendance_pct'] >= 50 ? 'text-amber-700' : 'text-red-700') }}">{{ $r['attendance_pct'] }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="px-4 py-12 text-center text-gray-400">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
