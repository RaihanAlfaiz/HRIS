<x-layouts.app :title="'Riwayat Karyawan'">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Perubahan</h1>
                <p class="mt-1 text-sm text-gray-500">Log perubahan data karyawan (promosi, mutasi, dll)</p>
            </div>
        </div>

        {{-- Quick Add --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm" x-data="{ open: false }">
            <button @click="open = !open" class="flex w-full items-center justify-between text-sm font-medium text-gray-700">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Tambah Riwayat Manual
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="open" x-cloak x-collapse class="mt-4">
                <form method="POST" action="{{ route('histories.store') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                        <select name="employee_id" required data-placeholder="Pilih..." class="select-search mt-1.5 block w-full">
                            <option value=""></option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                        <select name="type" required class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                            <option value="promotion">Promosi</option>
                            <option value="department_change">Pindah Departemen</option>
                            <option value="status_change">Perubahan Status</option>
                            <option value="position_change">Perubahan Jabatan</option>
                            <option value="site_change">Pindah Site</option>
                            <option value="salary_change">Perubahan Gaji</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dari / Sebelumnya</label>
                        <input type="text" name="old_value" class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none" placeholder="Nilai lama">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ke / Sesudahnya</label>
                        <input type="text" name="new_value" class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none" placeholder="Nilai baru">
                    </div>
                    <div class="sm:col-span-2 lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <input type="text" name="description" required class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none" placeholder="Contoh: Dipromosikan menjadi Head of Engineering">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full rounded-xl bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Timeline --}}
        <div class="space-y-4">
            @forelse($histories as $history)
                <div class="flex gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $history->type_color }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            @switch($history->type)
                                @case('promotion') <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /> @break
                                @case('department_change') <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /> @break
                                @default <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            @endswitch
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-semibold text-gray-900">{{ $history->employee->full_name }}</span>
                            <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold {{ $history->type_color }}">{{ $history->type_label }}</span>
                        </div>
                        <p class="text-sm text-gray-700">{{ $history->description }}</p>
                        @if($history->old_value || $history->new_value)
                            <div class="mt-2 flex items-center gap-2 text-xs">
                                @if($history->old_value)
                                    <span class="rounded bg-red-50 px-2 py-0.5 text-red-600 line-through">{{ $history->old_value }}</span>
                                @endif
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                @if($history->new_value)
                                    <span class="rounded bg-emerald-50 px-2 py-0.5 text-emerald-600 font-medium">{{ $history->new_value }}</span>
                                @endif
                            </div>
                        @endif
                        <p class="mt-2 text-xs text-gray-400">
                            {{ $history->created_at->diffForHumans() }}
                            @if($history->changedByUser) · oleh {{ $history->changedByUser->name ?? $history->changedByUser->username }} @endif
                        </p>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center shadow-sm">
                    <p class="text-gray-400">Belum ada riwayat perubahan.</p>
                </div>
            @endforelse
        </div>

        {{ $histories->appends(request()->query())->links() }}
    </div>
</x-layouts.app>
