<x-layouts.app :title="'Manajemen Lembur'">
    <x-slot:headerActions>
        <a href="{{ route('attendances.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            ← Absensi
        </a>
    </x-slot:headerActions>

    <div class="space-y-6">
        {{-- Filter tabs --}}
        <div class="flex items-center gap-2">
            @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $val => $lbl)
            <a href="{{ route('attendances.overtime', ['status' => $val]) }}"
               class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-sm font-medium transition
                      {{ $status === $val ? 'bg-primary-600 text-white shadow-sm shadow-primary-500/20' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                {{ $lbl }}
                @if($val === 'pending' && $pendingCount > 0)
                    <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full {{ $status === $val ? 'bg-white/20 text-white' : 'bg-amber-500 text-white' }} px-1.5 text-[10px] font-bold">{{ $pendingCount }}</span>
                @endif
            </a>
            @endforeach
        </div>

        {{-- Table --}}
        <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 text-left">
                            <th class="px-4 py-3 font-semibold text-gray-600">Karyawan</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Tanggal</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Shift</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">Jam Keluar</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">Jadwal Keluar</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">Durasi Lembur</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">Status</th>
                            @if($status === 'pending')
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($overtimes as $att)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-900">{{ $att->employee->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $att->employee->department?->name ?? '—' }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $att->date->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                @if($att->shift)
                                <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700">
                                    {{ $att->shift->name }}
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center font-mono font-semibold text-gray-700">{{ $att->check_out ? substr($att->check_out, 0, 5) : '—' }}</td>
                            <td class="px-4 py-3 text-center font-mono text-gray-500">{{ $att->schedule_out ? substr($att->schedule_out, 0, 5) : '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700">
                                    ⚡ {{ $att->overtime_formatted }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $att->overtime_status_color }}">
                                    {{ $att->overtime_status_label }}
                                </span>
                            </td>
                            @if($status === 'pending')
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <form method="POST" action="{{ route('attendances.approve-overtime', $att) }}">
                                        @csrf @method('PUT')
                                        <button type="submit" class="rounded-lg bg-emerald-500 p-1.5 text-white hover:bg-emerald-600 transition" title="Setujui">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('attendances.reject-overtime', $att) }}">
                                        @csrf @method('PUT')
                                        <button type="submit" class="rounded-lg bg-red-500 p-1.5 text-white hover:bg-red-600 transition" title="Tolak">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-400">Tidak ada data lembur.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($overtimes->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $overtimes->links() }}</div>
            @endif
        </div>
    </div>
</x-layouts.app>
