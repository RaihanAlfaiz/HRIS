<x-layouts.app :title="'Karyawan'">
    <x-slot:headerActions>
        <a href="{{ route('export.employees', request()->query()) }}"
           class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 active:scale-[0.98]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export Excel
        </a>
        <a href="{{ route('employees.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm shadow-primary-600/25 transition hover:bg-primary-700 active:scale-[0.98]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Karyawan
        </a>
    </x-slot:headerActions>

    {{-- ═══ SEARCH & FILTERS ═══ --}}
    <form method="GET" action="{{ route('employees.index') }}" id="filter-form"
          class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
            {{-- Search --}}
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1.5">Cari Karyawan</label>
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, atau jabatan..."
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/50 py-2.5 pl-10 pr-4 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                </div>
            </div>

            {{-- Department filter --}}
            <div class="sm:w-56">
                <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1.5">Departemen</label>
                <select id="department_id" name="department_id" data-placeholder="Semua Departemen"
                        class="select-search select-filter w-full">
                    <option value=""></option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status filter --}}
            <div class="sm:w-44">
                <label for="employment_status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <select id="employment_status" name="employment_status" data-placeholder="Semua Status"
                        class="select-search select-filter w-full">
                    <option value=""></option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('employment_status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Reset only --}}
            <div class="flex gap-2">
                <a href="{{ route('employees.index') }}"
                   class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-600 shadow-sm transition hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </div>
    </form>

    {{-- ═══ TABLE ═══ --}}
    <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="employees-table">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/80">
                        @php
                            $currentSort = request('sort', 'full_name');
                            $currentDir = request('direction', 'asc');
                        @endphp
                        @foreach([
                            ['field' => 'nip', 'label' => 'NIP'],
                            ['field' => 'full_name', 'label' => 'Nama Lengkap'],
                            ['field' => 'position', 'label' => 'Jabatan'],
                            ['field' => 'employment_status', 'label' => 'Status'],
                            ['field' => 'join_date', 'label' => 'Tgl Bergabung'],
                        ] as $col)
                            <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <a href="{{ route('employees.index', array_merge(request()->query(), [
                                    'sort' => $col['field'],
                                    'direction' => ($currentSort === $col['field'] && $currentDir === 'asc') ? 'desc' : 'asc',
                                ])) }}" class="inline-flex items-center gap-1 hover:text-gray-900 transition">
                                    {{ $col['label'] }}
                                    @if($currentSort === $col['field'])
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            @if($currentDir === 'asc')
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            @endif
                                        </svg>
                                    @endif
                                </a>
                            </th>
                        @endforeach
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Departemen</th>
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Masa Kerja</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($employees as $employee)
                        <tr class="transition-colors hover:bg-primary-50/30">
                            <td class="whitespace-nowrap px-5 py-3.5 font-mono text-xs text-gray-600">{{ $employee->nip }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5">
                                <a href="{{ route('employees.show', $employee) }}" class="font-medium text-gray-900 hover:text-primary-600 transition">
                                    {{ $employee->full_name }}
                                </a>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-gray-600">{{ $employee->position }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium
                                    {{ $employee->employment_status === 'Permanent' ? 'bg-emerald-100 text-emerald-700' :
                                       ($employee->employment_status === 'Contract' ? 'bg-amber-100 text-amber-700' :
                                       ($employee->employment_status === 'Probation' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700')) }}">
                                    {{ $employee->employment_status }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-gray-600">{{ $employee->join_date->format('d M Y') }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-gray-500">{{ $employee->department?->name }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5">
                                @php
                                    $tenureMonths = $employee->tenure_months;
                                    $tenureBadge = match(true) {
                                        $tenureMonths >= 60 => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                        $tenureMonths >= 24 => 'bg-blue-50 text-blue-700 ring-blue-200',
                                        $tenureMonths >= 6  => 'bg-violet-50 text-violet-700 ring-violet-200',
                                        default             => 'bg-amber-50 text-amber-700 ring-amber-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $tenureBadge }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $employee->tenure }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('employees.show', $employee) }}" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-primary-600" title="Lihat">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('employees.edit', $employee) }}" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-amber-600" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('employees.destroy', $employee) }}" onsubmit="return confirm('Yakin ingin menghapus data karyawan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-red-50 hover:text-red-600" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="mt-3 text-sm font-medium text-gray-500">Tidak ada data karyawan</p>
                                <p class="mt-1 text-xs text-gray-400">Coba ubah filter atau tambah karyawan baru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($employees->hasPages())
            <div class="border-t border-gray-200 bg-gray-50/50 px-5 py-3">
                {{ $employees->links() }}
            </div>
        @endif
    </div>

</x-layouts.app>
