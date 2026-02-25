<x-layouts.app :title="'Pengumuman'">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pengumuman</h1>
                <p class="mt-1 text-sm text-gray-500">Buat dan kelola pengumuman perusahaan</p>
            </div>
            <a href="{{ route('announcements.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primary-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Buat Pengumuman
            </a>
        </div>

        <div class="space-y-4">
            @forelse($announcements as $ann)
                <div class="rounded-2xl border bg-white p-5 shadow-sm transition hover:shadow-md {{ $ann->priority === 'urgent' ? 'border-red-200 bg-red-50/30' : ($ann->priority === 'high' ? 'border-amber-200 bg-amber-50/30' : 'border-gray-200') }}">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold {{ $ann->priority_color }}">{{ $ann->priority_label }}</span>
                                @if(!$ann->is_active)<span class="inline-flex rounded-full bg-gray-200 px-2 py-0.5 text-[10px] font-bold text-gray-500">Nonaktif</span>@endif
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $ann->title }}</h3>
                            <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ $ann->content }}</p>
                            <p class="mt-3 text-xs text-gray-400">
                                Oleh {{ $ann->creator?->name ?? $ann->creator?->username ?? 'System' }} · {{ $ann->created_at->diffForHumans() }}
                                @if($ann->expire_date) · Berakhir {{ $ann->expire_date->format('d M Y') }} @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <a href="{{ route('announcements.edit', $ann) }}" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-primary-600" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <form method="POST" action="{{ route('announcements.destroy', $ann) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                                @csrf @method('DELETE')
                                <button class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center shadow-sm">
                    <p class="text-gray-400">Belum ada pengumuman.</p>
                </div>
            @endforelse
        </div>

        {{ $announcements->links() }}
    </div>
</x-layouts.app>
