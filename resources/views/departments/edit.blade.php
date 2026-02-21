<x-layouts.app :title="'Edit Departemen: ' . $department->name">
    <x-slot:headerActions>
        <a href="{{ route('departments.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </x-slot:headerActions>

    <div class="mx-auto max-w-lg">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('departments.update', $department) }}" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Departemen <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $department->name) }}" required autofocus
                           class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('departments.index') }}" class="rounded-xl border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">Batal</a>
                    <button type="submit" class="rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm shadow-primary-600/25 transition hover:bg-primary-700 active:scale-[0.98]">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
