<x-layouts.app :title="'Pengaturan Shift'">
    <x-slot:headerActions>
        <a href="{{ route('attendances.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            ← Absensi
        </a>
    </x-slot:headerActions>

    <div class="space-y-6" x-data="{ showForm: false, editShift: null }">

        {{-- Add shift button --}}
        <div class="flex justify-end">
            <button @click="showForm = !showForm; editShift = null;"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition shadow-sm shadow-primary-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Tambah Shift
            </button>
        </div>

        {{-- Add/Edit form --}}
        <div x-show="showForm" x-transition x-cloak>
            <form method="POST" action="{{ route('attendances.store-shift') }}"
                  class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100 space-y-4">
                @csrf
                <h3 class="text-lg font-bold text-gray-900">Tambah Shift Baru</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Shift *</label>
                        <input type="text" name="name" required placeholder="contoh: Shift Pagi"
                               class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode *</label>
                        <input type="text" name="code" required maxlength="10" placeholder="contoh: SP"
                               class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Masuk *</label>
                        <input type="time" name="start_time" required
                               class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Keluar *</label>
                        <input type="time" name="end_time" required
                               class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Istirahat Mulai</label>
                        <input type="time" name="break_start"
                               class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Istirahat Selesai</label>
                        <input type="time" name="break_end"
                               class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Toleransi Telat (menit) *</label>
                        <input type="number" name="late_tolerance" value="15" min="0" max="120" required
                               class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Kerja (menit) *</label>
                        <input type="number" name="minimum_work_minutes" value="480" min="60" max="720" required
                               class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Min. Lembur dihitung (menit) *</label>
                        <input type="number" name="overtime_threshold_minutes" value="30" min="0" max="120" required
                               class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    </div>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition">Simpan</button>
                    <button type="button" @click="showForm = false" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700">Batal</button>
                </div>
            </form>
        </div>

        {{-- Shifts grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($shifts as $shift)
            <div class="relative rounded-2xl bg-white shadow-sm border {{ $shift->is_default ? 'border-primary-300 ring-2 ring-primary-100' : 'border-gray-100' }} overflow-hidden hover:shadow-md transition">
                {{-- Default badge --}}
                @if($shift->is_default)
                <div class="absolute top-3 right-3">
                    <span class="inline-flex items-center rounded-full bg-primary-500 px-2 py-0.5 text-[10px] font-bold text-white shadow-sm">DEFAULT</span>
                </div>
                @endif

                {{-- Header --}}
                <div class="p-5 pb-3">
                    <div class="flex items-start gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white font-bold text-sm shadow-lg shadow-indigo-500/20">
                            {{ $shift->code }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base">{{ $shift->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $shift->employees_count }} karyawan</p>
                        </div>
                    </div>
                </div>

                {{-- Schedule visual --}}
                <div class="px-5 pb-4">
                    <div class="flex items-center gap-3 rounded-xl bg-gray-50 p-3">
                        <div class="text-center">
                            <p class="text-[10px] text-gray-500 font-medium">MASUK</p>
                            <p class="font-mono text-lg font-bold text-emerald-600">{{ substr($shift->start_time, 0, 5) }}</p>
                        </div>
                        <div class="flex-1 flex items-center">
                            <div class="h-0.5 flex-1 bg-gray-200 rounded"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            <div class="h-0.5 flex-1 bg-gray-200 rounded"></div>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] text-gray-500 font-medium">KELUAR</p>
                            <p class="font-mono text-lg font-bold text-blue-600">{{ substr($shift->end_time, 0, 5) }}</p>
                        </div>
                    </div>

                    @if($shift->break_start)
                    <p class="text-xs text-gray-400 mt-2 text-center">
                        Istirahat: {{ substr($shift->break_start, 0, 5) }} - {{ substr($shift->break_end, 0, 5) }}
                    </p>
                    @endif
                </div>

                {{-- Settings --}}
                <div class="border-t border-gray-100 px-5 py-3 grid grid-cols-3 gap-2 text-center">
                    <div>
                        <p class="text-[10px] text-gray-400 font-medium">TOLERANSI</p>
                        <p class="text-sm font-bold text-gray-700">{{ $shift->late_tolerance }}m</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-medium">MIN. KERJA</p>
                        <p class="text-sm font-bold text-gray-700">{{ $shift->minimum_work_hours }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-medium">OT MIN.</p>
                        <p class="text-sm font-bold text-gray-700">{{ $shift->overtime_threshold_minutes }}m</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="border-t border-gray-100 px-5 py-3 flex items-center gap-2">
                    @if(!$shift->is_default)
                    <form method="POST" action="{{ route('attendances.set-default-shift', $shift) }}">
                        @csrf @method('PUT')
                        <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium text-primary-600 hover:bg-primary-50 transition">
                            Set Default
                        </button>
                    </form>
                    @endif
                    <span class="ml-auto inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $shift->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $shift->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
