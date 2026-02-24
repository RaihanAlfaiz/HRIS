<x-layouts.app :title="'Sites / Area Penempatan'">
    <x-slot:headerActions>
        <a href="{{ route('sites.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm shadow-primary-600/25 transition hover:bg-primary-700 active:scale-[0.98]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Site
        </a>
    </x-slot:headerActions>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/80">
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Kode</th>
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Nama Site</th>
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Kota</th>
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Provinsi</th>
                        <th class="whitespace-nowrap px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Karyawan</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sites as $site)
                        <tr class="transition-colors hover:bg-primary-50/30">
                            <td class="whitespace-nowrap px-5 py-3.5 font-mono text-xs font-semibold text-primary-600">{{ $site->code }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5">
                                <a href="{{ route('sites.show', $site) }}" class="font-medium text-gray-900 hover:text-primary-600 transition">{{ $site->name }}</a>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-gray-600">{{ $site->city ?? '—' }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-gray-600">{{ $site->province ?? '—' }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-center">
                                <span class="inline-flex rounded-full bg-primary-100 px-2.5 py-1 text-xs font-semibold text-primary-700">{{ $site->employees_count }}</span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('sites.show', $site) }}" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-primary-600" title="Lihat">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('sites.edit', $site) }}" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-amber-600" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="mt-3 text-sm font-medium text-gray-500">Belum ada site</p>
                                <p class="mt-1 text-xs text-gray-400">Tambahkan site untuk mengelompokkan karyawan berdasarkan area penempatan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sites->hasPages())
            <div class="border-t border-gray-200 bg-gray-50/50 px-5 py-3">
                {{ $sites->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
