<x-layouts.app :title="'Tambah Karyawan'">
    <x-slot:headerActions>
        <a href="{{ route('employees.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </x-slot:headerActions>

    @include('employees._form', ['employee' => null, 'departments' => $departments])
</x-layouts.app>
