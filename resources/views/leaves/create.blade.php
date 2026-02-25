<x-layouts.app :title="'Ajukan Cuti'">
    <div class="mx-auto max-w-2xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ajukan Cuti / Izin</h1>
            <p class="mt-1 text-sm text-gray-500">Isi form di bawah untuk mengajukan cuti atau izin</p>
        </div>

        <form method="POST" action="{{ route('leaves.store') }}" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                <select name="employee_id" required data-placeholder="Pilih karyawan..." class="select-search mt-1.5 block w-full">
                    <option value=""></option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }} — {{ $emp->nip }}</option>
                    @endforeach
                </select>
                @error('employee_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Cuti</label>
                <select name="type" required class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    <option value="cuti_tahunan" {{ old('type') == 'cuti_tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                    <option value="cuti_sakit" {{ old('type') == 'cuti_sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                    <option value="cuti_melahirkan" {{ old('type') == 'cuti_melahirkan' ? 'selected' : '' }}>Cuti Melahirkan</option>
                    <option value="cuti_menikah" {{ old('type') == 'cuti_menikah' ? 'selected' : '' }}>Cuti Menikah</option>
                    <option value="cuti_khusus" {{ old('type') == 'cuti_khusus' ? 'selected' : '' }}>Cuti Khusus</option>
                    <option value="izin" {{ old('type') == 'izin' ? 'selected' : '' }}>Izin</option>
                </select>
                @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                           class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required
                           class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                    @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alasan</label>
                <textarea name="reason" rows="3" required placeholder="Jelaskan alasan cuti..."
                          class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">{{ old('reason') }}</textarea>
                @error('reason') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primary-700 transition">
                    Ajukan Cuti
                </button>
                <a href="{{ route('leaves.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.app>
