<x-layouts.app :title="'Cuti & Izin'">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Cuti & Izin</h1>
                <p class="mt-1 text-sm text-gray-500">Kelola pengajuan cuti karyawan</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('leaves.calendar') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    Kalender
                </a>
                <a href="{{ route('leaves.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primary-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Ajukan Cuti
                </a>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex gap-2">
            <a href="{{ route('leaves.index') }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ !$status ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua
            </a>
            <a href="{{ route('leaves.index', ['status' => 'pending']) }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $status === 'pending' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Menunggu <span class="ml-1 text-xs">({{ $pendingCount }})</span>
            </a>
            <a href="{{ route('leaves.index', ['status' => 'approved']) }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $status === 'approved' ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Disetujui <span class="ml-1 text-xs">({{ $approvedCount }})</span>
            </a>
            <a href="{{ route('leaves.index', ['status' => 'rejected']) }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $status === 'rejected' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Ditolak
            </a>
        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Karyawan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tipe</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tanggal</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Hari</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Alasan</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($leaves as $leave)
                            <tr class="transition hover:bg-gray-50/50">
                                <td class="whitespace-nowrap px-5 py-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $leave->employee->full_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $leave->employee->department?->name }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-600">{{ $leave->type_label }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-600">
                                    {{ $leave->start_date->format('d M') }} — {{ $leave->end_date->format('d M Y') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-center text-sm font-medium text-gray-900">{{ $leave->days }}</td>
                                <td class="px-5 py-3 text-sm text-gray-500 max-w-[200px] truncate">{{ $leave->reason }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-center">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $leave->status_color }}">{{ $leave->status_label }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-center" x-data="{ showReject: false }">
                                    @if($leave->status === 'pending')
                                        <div class="flex items-center justify-center gap-1">
                                            <form method="POST" action="{{ route('leaves.approve', $leave) }}">
                                                @csrf @method('PUT')
                                                <button type="submit" class="rounded-lg bg-emerald-500 px-2.5 py-1 text-xs font-medium text-white hover:bg-emerald-600 transition">Setuju</button>
                                            </form>
                                            <button @click="showReject = !showReject" class="rounded-lg bg-red-500 px-2.5 py-1 text-xs font-medium text-white hover:bg-red-600 transition">Tolak</button>
                                        </div>
                                        <div x-show="showReject" x-cloak class="mt-2">
                                            <form method="POST" action="{{ route('leaves.reject', $leave) }}" class="flex gap-1">
                                                @csrf @method('PUT')
                                                <input type="text" name="rejection_reason" placeholder="Alasan penolakan..." required
                                                       class="w-full rounded-lg border-gray-300 text-xs px-2 py-1">
                                                <button type="submit" class="rounded-lg bg-red-600 px-2 py-1 text-xs text-white">OK</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">
                                            {{ $leave->approver?->name ?? '—' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-5 py-12 text-center text-sm text-gray-400">Belum ada pengajuan cuti.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $leaves->appends(request()->query())->links() }}
    </div>
</x-layouts.app>
