<x-layouts.app :title="$site->name">
    <x-slot:headerActions>
        <a href="{{ route('sites.edit', $site) }}"
           class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700 active:scale-[0.98]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit
        </a>
        <a href="{{ route('sites.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </x-slot:headerActions>

    <div class="space-y-6">
        {{-- Site Info + Stats --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Kode Site</p>
                <p class="mt-1 text-2xl font-bold text-primary-600">{{ $site->code }}</p>
                @if($site->city || $site->province)
                    <p class="mt-1 text-xs text-gray-400">{{ $site->city }}{{ $site->city && $site->province ? ', ' : '' }}{{ $site->province }}</p>
                @endif
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Karyawan Aktif</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $site->employees_count }}</p>
                <p class="mt-1 text-xs text-gray-400">di site ini</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Kontrak Segera Habis</p>
                <p class="mt-1 text-2xl font-bold {{ $expiringContracts->count() > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $expiringContracts->count() }}</p>
                <p class="mt-1 text-xs text-gray-400">dalam 30 hari ke depan</p>
            </div>
        </div>

        {{-- Expiring Contracts Alert --}}
        @if($expiringContracts->count() > 0)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <h3 class="flex items-center gap-2 text-base font-semibold text-amber-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Kontrak Akan Habis (30 Hari)
                </h3>
                <div class="mt-3 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead><tr class="border-b border-amber-200">
                            <th class="px-4 py-2 text-left text-xs font-semibold text-amber-700">Karyawan</th>
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
                                    <td class="whitespace-nowrap px-4 py-2.5 text-amber-700">{{ $contract->contract_type }}</td>
                                    <td class="whitespace-nowrap px-4 py-2.5 text-amber-700">{{ $contract->end_date->format('d M Y') }}</td>
                                    <td class="whitespace-nowrap px-4 py-2.5">
                                        <span class="font-semibold {{ $contract->remaining_days <= 7 ? 'text-red-600' : 'text-amber-700' }}">
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

        {{-- Employee List --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-gray-50/80 px-5 py-3">
                <h3 class="text-sm font-semibold text-gray-700">Daftar Karyawan di Site Ini</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50/50">
                            <th class="whitespace-nowrap px-5 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">NIP</th>
                            <th class="whitespace-nowrap px-5 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Nama</th>
                            <th class="whitespace-nowrap px-5 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Jabatan</th>
                            <th class="whitespace-nowrap px-5 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Departemen</th>
                            <th class="whitespace-nowrap px-5 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                            <th class="whitespace-nowrap px-5 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Masa Kerja</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($employees as $emp)
                            <tr class="hover:bg-primary-50/30">
                                <td class="whitespace-nowrap px-5 py-3 font-mono text-xs text-gray-600">{{ $emp->nip }}</td>
                                <td class="whitespace-nowrap px-5 py-3">
                                    <a href="{{ route('employees.show', $emp) }}" class="font-medium text-gray-900 hover:text-primary-600 transition">{{ $emp->full_name }}</a>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-gray-600">{{ $emp->position }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-gray-500">{{ $emp->department?->name }}</td>
                                <td class="whitespace-nowrap px-5 py-3">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                        {{ $emp->employment_status === 'Permanent' ? 'bg-emerald-100 text-emerald-700' :
                                           ($emp->employment_status === 'Contract' ? 'bg-amber-100 text-amber-700' :
                                           ($emp->employment_status === 'Probation' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700')) }}">
                                        {{ $emp->employment_status }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-gray-600 text-sm">{{ $emp->tenure }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-500">Belum ada karyawan di site ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($employees->hasPages())
                <div class="border-t border-gray-200 bg-gray-50/50 px-5 py-3">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
