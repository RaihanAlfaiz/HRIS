<x-layouts.app :title="'Buat Slip Gaji'">
    <div class="mx-auto max-w-3xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Buat Slip Gaji</h1>
            <p class="mt-1 text-sm text-gray-500">Isi data gaji karyawan untuk periode tertentu</p>
        </div>

        <form method="POST" action="{{ route('payrolls.store') }}" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                    <select name="employee_id" required data-placeholder="Pilih karyawan..." class="select-search mt-1.5 block w-full">
                        <option value=""></option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                    <input type="month" name="period" value="{{ old('period', $period) }}" required
                           class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                </div>
            </div>

            {{-- Pendapatan --}}
            <div>
                <h3 class="flex items-center gap-2 text-sm font-semibold text-emerald-700 mb-3">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Pendapatan
                </h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach([
                        'basic_salary' => 'Gaji Pokok *',
                        'transport_allowance' => 'Tunjangan Transport',
                        'meal_allowance' => 'Tunjangan Makan',
                        'other_allowance' => 'Tunjangan Lain',
                        'overtime' => 'Lembur',
                    ] as $field => $label)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                            <input type="number" name="{{ $field }}" value="{{ old($field, 0) }}" min="0" step="1000"
                                   {{ $field === 'basic_salary' ? 'required' : '' }}
                                   class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Potongan --}}
            <div>
                <h3 class="flex items-center gap-2 text-sm font-semibold text-red-700 mb-3">
                    <span class="h-2 w-2 rounded-full bg-red-500"></span> Potongan
                </h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    @foreach([
                        'bpjs_deduction' => 'BPJS',
                        'tax_deduction' => 'Pajak',
                        'other_deduction' => 'Potongan Lain',
                    ] as $field => $label)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                            <input type="number" name="{{ $field }}" value="{{ old($field, 0) }}" min="0" step="1000"
                                   class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                <textarea name="notes" rows="2" class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none" placeholder="Opsional...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primary-700 transition">
                    Simpan Slip Gaji
                </button>
                <a href="{{ route('payrolls.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.app>
