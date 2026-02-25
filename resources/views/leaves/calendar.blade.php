<x-layouts.app :title="'Kalender Cuti'">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kalender Cuti</h1>
                <p class="mt-1 text-sm text-gray-500">{{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}</p>
            </div>
            <form method="GET" class="flex items-center gap-2">
                <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()"
                       class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
            </form>
        </div>

        {{-- Leave Balances --}}
        @if($balances->count())
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Saldo Cuti Tahunan {{ \Carbon\Carbon::parse($month . '-01')->year }}</h3>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
                @foreach($balances as $balance)
                    <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-3">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $balance->employee->full_name }}</p>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-lg font-bold {{ $balance->remaining <= 2 ? 'text-red-600' : 'text-primary-600' }}">{{ $balance->remaining }}</span>
                            <span class="text-xs text-gray-400">/ {{ $balance->total_quota }} hari</span>
                        </div>
                        <div class="mt-1.5 h-1.5 w-full rounded-full bg-gray-200 overflow-hidden">
                            <div class="h-full rounded-full {{ $balance->remaining <= 2 ? 'bg-red-500' : 'bg-primary-500' }}"
                                 style="width: {{ $balance->total_quota > 0 ? ($balance->remaining / $balance->total_quota) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Approved Leaves List --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Cuti Disetujui Bulan Ini</h3>
            @if($leaves->count())
                <div class="space-y-3">
                    @foreach($leaves as $leave)
                        <div class="flex items-center gap-4 rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-sm font-bold text-blue-600">
                                {{ strtoupper(substr($leave->employee->full_name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $leave->employee->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $leave->type_label }} · {{ $leave->start_date->format('d M') }} — {{ $leave->end_date->format('d M Y') }}</p>
                            </div>
                            <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">{{ $leave->days }} hari</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400 text-center py-8">Tidak ada cuti yang disetujui bulan ini.</p>
            @endif
        </div>
    </div>
</x-layouts.app>
