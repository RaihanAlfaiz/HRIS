<x-layouts.app :title="'Dashboard'">

    {{-- ═══════════ STAT CARDS ═══════════ --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">

        {{-- Total Employees --}}
        <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-primary-50 transition-transform duration-500 group-hover:scale-125"></div>
            <div class="relative">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-100 text-primary-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <p class="mt-4 text-2xl font-bold text-gray-900">{{ number_format($totalEmployees) }}</p>
                <p class="mt-1 text-sm text-gray-500">Total Karyawan</p>
            </div>
        </div>

        {{-- Total Departments --}}
        <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-violet-50 transition-transform duration-500 group-hover:scale-125"></div>
            <div class="relative">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <p class="mt-4 text-2xl font-bold text-gray-900">{{ number_format($totalDepartments) }}</p>
                <p class="mt-1 text-sm text-gray-500">Departemen</p>
            </div>
        </div>

        {{-- Total Sites --}}
        <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-cyan-50 transition-transform duration-500 group-hover:scale-125"></div>
            <div class="relative">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-100 text-cyan-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <p class="mt-4 text-2xl font-bold text-gray-900">{{ number_format($totalSites) }}</p>
                <p class="mt-1 text-sm text-gray-500">Site / Area</p>
            </div>
        </div>

        {{-- Permanent --}}
        <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-50 transition-transform duration-500 group-hover:scale-125"></div>
            <div class="relative">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <p class="mt-4 text-2xl font-bold text-gray-900">{{ number_format($statusCounts->get('Permanent', 0)) }}</p>
                <p class="mt-1 text-sm text-gray-500">Karyawan Tetap</p>
            </div>
        </div>

        {{-- Contract --}}
        <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-50 transition-transform duration-500 group-hover:scale-125"></div>
            <div class="relative">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="mt-4 text-2xl font-bold text-gray-900">{{ number_format($statusCounts->get('Contract', 0)) }}</p>
                <p class="mt-1 text-sm text-gray-500">Karyawan Kontrak</p>
            </div>
        </div>
    </div>

    {{-- ═══════════ CONTRACT ALERTS ═══════════ --}}
    @if($expiringContracts->count() > 0)
        <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
            <h3 class="flex items-center gap-2 text-base font-semibold text-amber-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Kontrak Akan Habis ({{ $expiringContracts->count() }} dalam 30 hari)
            </h3>
            <div class="mt-3 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-amber-200">
                        <th class="px-4 py-2 text-left text-xs font-semibold text-amber-700">Karyawan</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-amber-700">Site</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-amber-700">Tipe</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-amber-700">Berakhir</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-amber-700">Sisa</th>
                    </tr></thead>
                    <tbody class="divide-y divide-amber-100">
                        @foreach($expiringContracts as $contract)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2.5">
                                    <a href="{{ route('employees.show', $contract->employee) }}" class="font-medium text-amber-900 hover:underline">{{ $contract->employee->full_name }}</a>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2.5 text-amber-700">{{ $contract->employee->site?->name ?? '—' }}</td>
                                <td class="whitespace-nowrap px-4 py-2.5 text-amber-700">{{ $contract->contract_type }}</td>
                                <td class="whitespace-nowrap px-4 py-2.5 text-amber-700">{{ $contract->end_date->format('d M Y') }}</td>
                                <td class="whitespace-nowrap px-4 py-2.5">
                                    <span class="font-bold {{ $contract->remaining_days <= 7 ? 'text-red-600' : 'text-amber-700' }}">
                                        {{ $contract->remaining_days }} hari
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ═══════════ BOTTOM GRID ═══════════ --}}
    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Department distribution --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Distribusi per Departemen</h3>
            <p class="mt-1 text-sm text-gray-500">Top 10 departemen</p>
            <div class="mt-5 space-y-3">
                @foreach($departmentCounts as $dept)
                    @php $pct = $totalEmployees > 0 ? round(($dept->employees_count / $totalEmployees) * 100, 1) : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium text-gray-700">{{ $dept->name }}</span>
                            <span class="text-gray-500">{{ $dept->employees_count }} <span class="text-gray-400">({{ $pct }}%)</span></span>
                        </div>
                        <div class="mt-1.5 h-2 w-full overflow-hidden rounded-full bg-gray-100">
                            <div class="h-full rounded-full bg-gradient-to-r from-primary-500 to-primary-400 transition-all duration-700" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Site distribution --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Distribusi per Site</h3>
            <p class="mt-1 text-sm text-gray-500">Karyawan aktif per area</p>
            <div class="mt-5 space-y-3">
                @forelse($siteCounts as $site)
                    @php $pct = $totalEmployees > 0 ? round(($site->employees_count / $totalEmployees) * 100, 1) : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <a href="{{ route('sites.show', $site) }}" class="font-medium text-gray-700 hover:text-primary-600 transition">{{ $site->name }}</a>
                            <span class="text-gray-500">{{ $site->employees_count }} <span class="text-gray-400">({{ $pct }}%)</span></span>
                        </div>
                        <div class="mt-1.5 h-2 w-full overflow-hidden rounded-full bg-gray-100">
                            <div class="h-full rounded-full bg-gradient-to-r from-cyan-500 to-cyan-400 transition-all duration-700" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada data site.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent employees --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Karyawan Terbaru</h3>
            <p class="mt-1 text-sm text-gray-500">5 terakhir ditambahkan</p>
            <div class="mt-5 space-y-4">
                @forelse($recentEmployees as $emp)
                    <a href="{{ route('employees.show', $emp) }}" class="group flex items-center gap-3 rounded-xl p-2 -mx-2 transition hover:bg-gray-50">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-600">
                            {{ strtoupper(substr($emp->full_name, 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-gray-900 group-hover:text-primary-600 transition">{{ $emp->full_name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ $emp->position }} · {{ $emp->department?->name }}</p>
                        </div>
                        <span class="shrink-0 rounded-full px-2 py-0.5 text-[11px] font-medium
                            {{ $emp->employment_status === 'Permanent' ? 'bg-emerald-100 text-emerald-700' :
                               ($emp->employment_status === 'Contract' ? 'bg-amber-100 text-amber-700' :
                               ($emp->employment_status === 'Probation' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700')) }}">
                            {{ $emp->employment_status }}
                        </span>
                    </a>
                @empty
                    <p class="text-sm text-gray-400">Belum ada data karyawan.</p>
                @endforelse
            </div>
        </div>
    </div>

</x-layouts.app>
