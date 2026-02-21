<x-layouts.app :title="'Departemen'">
    <x-slot:headerActions>
        <a href="{{ route('departments.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm shadow-primary-600/25 transition hover:bg-primary-700 active:scale-[0.98]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Departemen
        </a>
    </x-slot:headerActions>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/80">
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">#</th>
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Nama Departemen</th>
                        <th class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Jumlah Karyawan</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($departments as $index => $dept)
                        <tr class="transition-colors hover:bg-primary-50/30">
                            <td class="whitespace-nowrap px-5 py-3.5 text-gray-500">{{ $departments->firstItem() + $index }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5 font-medium text-gray-900">{{ $dept->name }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5">
                                <span class="inline-flex items-center rounded-full bg-primary-100 px-2.5 py-1 text-xs font-medium text-primary-700">
                                    {{ $dept->employees_count }} karyawan
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('departments.edit', $dept) }}" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-amber-600" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @if($dept->employees_count === 0)
                                        <form method="POST" action="{{ route('departments.destroy', $dept) }}" onsubmit="return confirm('Yakin ingin menghapus departemen ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-red-50 hover:text-red-600" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center">
                                <p class="text-sm text-gray-500">Belum ada data departemen.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($departments->hasPages())
            <div class="border-t border-gray-200 bg-gray-50/50 px-5 py-3">
                {{ $departments->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
