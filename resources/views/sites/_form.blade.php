@props(['site' => null])

@php $isEdit = $site !== null; @endphp

<form method="POST"
      action="{{ $isEdit ? route('sites.update', $site) : route('sites.store') }}"
      class="space-y-6">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900 mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Informasi Site
        </h3>
        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700">Kode Site <span class="text-red-500">*</span></label>
                <input type="text" id="code" name="code" value="{{ old('code', $site?->code) }}" required
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                       placeholder="JKT-01">
                @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Site <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $site?->name) }}" required
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                       placeholder="Kantor Pusat Jakarta">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700">Kota</label>
                <input type="text" id="city" name="city" value="{{ old('city', $site?->city) }}"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                       placeholder="Jakarta Selatan">
                @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="province" class="block text-sm font-medium text-gray-700">Provinsi</label>
                <input type="text" id="province" name="province" value="{{ old('province', $site?->province) }}"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                       placeholder="DKI Jakarta">
                @error('province') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                <textarea id="address" name="address" rows="3"
                          class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                          placeholder="Jl. Sudirman No. 1, RT 01/RW 02...">{{ old('address', $site?->address) }}</textarea>
                @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            
            <div class="sm:col-span-2 pt-4 mt-2 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude (Garis Lintang)</label>
                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $site?->latitude) }}"
                           class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                           placeholder="-6.200000">
                    @error('latitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude (Garis Bujur)</label>
                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $site?->longitude) }}"
                           class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                           placeholder="106.816666">
                    @error('longitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="radius" class="block text-sm font-medium text-gray-700">Radius Ceklok (Meter) <span class="text-red-500">*</span></label>
                    <input type="number" id="radius" name="radius" value="{{ old('radius', $site?->radius ?? 1000) }}" required min="10"
                           class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                           placeholder="1000">
                    <p class="mt-1 text-xs text-gray-500">Jarak maksimal karyawan bisa absen WFO dari titik lokasi.</p>
                    @error('radius') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('sites.index') }}"
           class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">Batal</a>
        <button type="submit"
                class="rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700 active:scale-[0.98]">
            {{ $isEdit ? 'Perbarui' : 'Simpan' }}
        </button>
    </div>
</form>
