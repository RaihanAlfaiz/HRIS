<x-layouts.app :title="'Koreksi Absensi'">
    <x-slot:headerActions>
        <a href="{{ route('attendances.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            ← Absensi
        </a>
    </x-slot:headerActions>

    <div class="space-y-6">
        {{-- Filter tabs --}}
        <div class="flex items-center gap-2">
            @foreach(['' => 'Semua', 'pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $val => $lbl)
            <a href="{{ route('attendances.corrections', ['status' => $val]) }}"
               class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-sm font-medium transition
                      {{ request('status', '') === $val ? 'bg-primary-600 text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                {{ $lbl }}
                @if($val === 'pending' && $pendingCount > 0)
                    <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full {{ request('status', '') === $val ? 'bg-white/20' : 'bg-amber-500 text-white' }} px-1.5 text-[10px] font-bold">{{ $pendingCount }}</span>
                @endif
            </a>
            @endforeach
        </div>

        {{-- Corrections list --}}
        <div class="space-y-4">
            @forelse($corrections as $correction)
            <div class="rounded-2xl bg-white shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                    <div class="flex-1 space-y-3">
                        {{-- Header --}}
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700 font-bold text-sm">
                                {{ strtoupper(substr($correction->attendance->employee->full_name ?? '?', 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $correction->attendance->employee->full_name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $correction->attendance->employee->department?->name ?? '' }} ·
                                    Tanggal: {{ $correction->attendance->date->format('d M Y') }} ·
                                    Diajukan oleh: {{ $correction->requester->name ?? $correction->requester->username ?? '?' }}
                                </p>
                            </div>
                            <span class="ml-auto inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $correction->status_color }}">
                                {{ $correction->status_label }}
                            </span>
                        </div>

                        {{-- Correction details --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-gray-50 rounded-xl p-3">
                            <div>
                                <p class="text-[11px] text-gray-500 font-medium">Check-In Asli</p>
                                <p class="font-mono font-semibold text-gray-700">{{ $correction->original_check_in ? substr($correction->original_check_in, 0, 5) : '—' }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] text-gray-500 font-medium">Check-In Koreksi</p>
                                <p class="font-mono font-semibold {{ $correction->corrected_check_in ? 'text-emerald-600' : 'text-gray-400' }}">{{ $correction->corrected_check_in ? substr($correction->corrected_check_in, 0, 5) : '—' }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] text-gray-500 font-medium">Check-Out Asli</p>
                                <p class="font-mono font-semibold text-gray-700">{{ $correction->original_check_out ? substr($correction->original_check_out, 0, 5) : '—' }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] text-gray-500 font-medium">Check-Out Koreksi</p>
                                <p class="font-mono font-semibold {{ $correction->corrected_check_out ? 'text-emerald-600' : 'text-gray-400' }}">{{ $correction->corrected_check_out ? substr($correction->corrected_check_out, 0, 5) : '—' }}</p>
                            </div>
                        </div>

                        {{-- Reason --}}
                        <div class="bg-amber-50/50 rounded-xl px-4 py-3">
                            <p class="text-xs font-medium text-amber-700 mb-0.5">Alasan Koreksi:</p>
                            <p class="text-sm text-gray-700">{{ $correction->reason }}</p>
                        </div>

                        @if($correction->rejection_reason)
                        <div class="bg-red-50/50 rounded-xl px-4 py-3">
                            <p class="text-xs font-medium text-red-700 mb-0.5">Alasan Penolakan:</p>
                            <p class="text-sm text-gray-700">{{ $correction->rejection_reason }}</p>
                        </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    @if($correction->status === 'pending')
                    <div class="flex lg:flex-col gap-2 shrink-0" x-data="{ showReject: false }">
                        <form method="POST" action="{{ route('attendances.approve-correction', $correction) }}">
                            @csrf @method('PUT')
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600 transition shadow-sm shadow-emerald-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Setuju
                            </button>
                        </form>
                        <button @click="showReject = !showReject" class="inline-flex items-center gap-1.5 rounded-xl bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600 transition shadow-sm shadow-red-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Tolak
                        </button>
                        <form x-show="showReject" x-transition method="POST" action="{{ route('attendances.reject-correction', $correction) }}" class="lg:mt-2" x-cloak>
                            @csrf @method('PUT')
                            <textarea name="rejection_reason" rows="2" required placeholder="Alasan penolakan..."
                                      class="block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none mb-2"></textarea>
                            <button type="submit" class="w-full rounded-xl bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200 transition">Kirim Penolakan</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="rounded-2xl bg-white p-12 text-center text-gray-400 shadow-sm border border-gray-100">
                Tidak ada data koreksi.
            </div>
            @endforelse
        </div>

        @if($corrections->hasPages())
        <div>{{ $corrections->links() }}</div>
        @endif
    </div>
</x-layouts.app>
