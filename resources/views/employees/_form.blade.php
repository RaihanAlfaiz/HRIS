{{-- Reusable employee form partial (used by both create and edit) --}}

@php
    $employee  = $employee ?? null;
    $departments = $departments ?? collect();
    $sites     = $sites ?? collect();
    $isEdit    = $employee !== null;
    $profile   = $isEdit ? $employee->profile : null;
    $contact   = $isEdit ? $employee->contact : null;
    $financial = $isEdit ? $employee->financial : null;
@endphp

<form method="POST"
      action="{{ $isEdit ? route('employees.update', $employee) : route('employees.store') }}"
      class="space-y-6">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- ═══ DATA KEPEGAWAIAN ═══ --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900 mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
            </svg>
            Data Kepegawaian
        </h3>
        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
            <div>
                <label for="nip" class="block text-sm font-medium text-gray-700">NIP <span class="text-red-500">*</span></label>
                <input type="text" id="nip" name="nip" value="{{ old('nip', $employee?->nip) }}" required
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                @error('nip') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="full_name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $employee?->full_name) }}" required
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                @error('full_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="department_id" class="block text-sm font-medium text-gray-700">Departemen <span class="text-red-500">*</span></label>
                <select id="department_id" name="department_id" required data-placeholder="Pilih Departemen"
                        class="select-search mt-1.5 block w-full">
                    <option value=""></option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $employee?->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
                @error('department_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="position" class="block text-sm font-medium text-gray-700">Jabatan <span class="text-red-500">*</span></label>
                <input type="text" id="position" name="position" value="{{ old('position', $employee?->position) }}" required
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                @error('position') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="employment_status" class="block text-sm font-medium text-gray-700">Status Kepegawaian <span class="text-red-500">*</span></label>
                <select id="employment_status" name="employment_status" required data-placeholder="Pilih Status"
                        class="select-search mt-1.5 block w-full">
                    <option value=""></option>
                    @foreach(['Permanent', 'Contract', 'Probation', 'Internship'] as $status)
                        <option value="{{ $status }}" {{ old('employment_status', $employee?->employment_status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                @error('employment_status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="site_id" class="block text-sm font-medium text-gray-700">Site / Area Penempatan <span class="text-red-500">*</span></label>
                @if(auth()->user()->isAdmin())
                    <select id="site_id" name="site_id" required data-placeholder="Pilih Site"
                            class="select-search mt-1.5 block w-full">
                        <option value=""></option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ old('site_id', $employee?->site_id) == $site->id ? 'selected' : '' }}>{{ $site->code }} — {{ $site->name }}</option>
                        @endforeach
                    </select>
                @else
                    <div class="mt-1.5 p-2.5 bg-gray-100 rounded-xl border border-gray-200 text-sm text-gray-600 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        {{ auth()->user()->site->name ?? 'Site Terkunci' }}
                        <input type="hidden" name="site_id" value="{{ auth()->user()->site_id }}">
                    </div>
                @endif
                @error('site_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="join_date" class="block text-sm font-medium text-gray-700">Tanggal Bergabung <span class="text-red-500">*</span></label>
                <input type="date" id="join_date" name="join_date" value="{{ old('join_date', $employee?->join_date?->format('Y-m-d')) }}" required
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                @error('join_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- ═══ DATA PRIBADI ═══ --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900 mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Data Pribadi
        </h3>
        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <label for="nik_ktp" class="block text-sm font-medium text-gray-700">NIK KTP</label>
                <input type="text" id="nik_ktp" name="nik_ktp" value="{{ old('nik_ktp', $profile?->nik_ktp) }}" maxlength="20"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                @error('nik_ktp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="place_of_birth" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                <input type="text" id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth', $profile?->place_of_birth) }}"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
            </div>
            <div>
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $profile?->date_of_birth?->format('Y-m-d')) }}"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
            </div>
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                <select id="gender" name="gender" data-placeholder="Pilih"
                        class="select-search mt-1.5 block w-full">
                    <option value=""></option>
                    @foreach(['Laki-laki', 'Perempuan'] as $g)
                        <option value="{{ $g }}" {{ old('gender', $profile?->gender) === $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="religion" class="block text-sm font-medium text-gray-700">Agama</label>
                <select id="religion" name="religion" data-placeholder="Pilih"
                        class="select-search mt-1.5 block w-full">
                    <option value=""></option>
                    @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $r)
                        <option value="{{ $r }}" {{ old('religion', $profile?->religion) === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="marital_status" class="block text-sm font-medium text-gray-700">Status Pernikahan</label>
                <select id="marital_status" name="marital_status" data-placeholder="Pilih"
                        class="select-search mt-1.5 block w-full">
                    <option value=""></option>
                    @foreach(['Belum Menikah', 'Menikah', 'Cerai'] as $m)
                        <option value="{{ $m }}" {{ old('marital_status', $profile?->marital_status) === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="blood_type" class="block text-sm font-medium text-gray-700">Golongan Darah</label>
                <select id="blood_type" name="blood_type" data-placeholder="Pilih"
                        class="select-search mt-1.5 block w-full">
                    <option value=""></option>
                    @foreach(['A', 'B', 'AB', 'O'] as $bt)
                        <option value="{{ $bt }}" {{ old('blood_type', $profile?->blood_type) === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2 lg:col-span-3">
                <label for="address_ktp" class="block text-sm font-medium text-gray-700">Alamat KTP</label>
                <textarea id="address_ktp" name="address_ktp" rows="2"
                          class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">{{ old('address_ktp', $profile?->address_ktp) }}</textarea>
            </div>
            <div class="sm:col-span-2 lg:col-span-3">
                <label for="address_domicile" class="block text-sm font-medium text-gray-700">Alamat Domisili</label>
                <textarea id="address_domicile" name="address_domicile" rows="2"
                          class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">{{ old('address_domicile', $profile?->address_domicile) }}</textarea>
            </div>
        </div>
    </div>

    {{-- ═══ KONTAK ═══ --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900 mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Kontak & Darurat
        </h3>
        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <label for="email_work" class="block text-sm font-medium text-gray-700">Email Kantor</label>
                <input type="email" id="email_work" name="email_work" value="{{ old('email_work', $contact?->email_work) }}"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                @error('email_work') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="email_personal" class="block text-sm font-medium text-gray-700">Email Pribadi</label>
                <input type="email" id="email_personal" name="email_personal" value="{{ old('email_personal', $contact?->email_personal) }}"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
            </div>
            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $contact?->phone_number) }}"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
            </div>
            <div>
                <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700">Nama Kontak Darurat</label>
                <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $contact?->emergency_contact_name) }}"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
            </div>
            <div>
                <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700">Telepon Darurat</label>
                <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $contact?->emergency_contact_phone) }}"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
            </div>
            <div>
                <label for="emergency_contact_relation" class="block text-sm font-medium text-gray-700">Hubungan Darurat</label>
                <select id="emergency_contact_relation" name="emergency_contact_relation" data-placeholder="Pilih"
                        class="select-search mt-1.5 block w-full">
                    <option value=""></option>
                    @foreach(['Suami', 'Istri', 'Orang Tua', 'Saudara', 'Anak', 'Lainnya'] as $rel)
                        <option value="{{ $rel }}" {{ old('emergency_contact_relation', $contact?->emergency_contact_relation) === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- ═══ FINANSIAL ═══ --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900 mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            Finansial & Administrasi
        </h3>
        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <label for="npwp" class="block text-sm font-medium text-gray-700">NPWP</label>
                <input type="text" id="npwp" name="npwp" value="{{ old('npwp', $financial?->npwp) }}"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
            </div>
            <div>
                <label for="bpjs_kesehatan" class="block text-sm font-medium text-gray-700">BPJS Kesehatan</label>
                <input type="text" id="bpjs_kesehatan" name="bpjs_kesehatan" value="{{ old('bpjs_kesehatan', $financial?->bpjs_kesehatan) }}"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
            </div>
            <div>
                <label for="bpjs_ketenagakerjaan" class="block text-sm font-medium text-gray-700">BPJS Ketenagakerjaan</label>
                <input type="text" id="bpjs_ketenagakerjaan" name="bpjs_ketenagakerjaan" value="{{ old('bpjs_ketenagakerjaan', $financial?->bpjs_ketenagakerjaan) }}"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
            </div>
            <div>
                <label for="bank_name" class="block text-sm font-medium text-gray-700">Nama Bank</label>
                <select id="bank_name" name="bank_name" data-placeholder="Pilih Bank"
                        class="select-search mt-1.5 block w-full">
                    <option value=""></option>
                    @foreach(['BCA', 'BNI', 'BRI', 'Mandiri', 'CIMB Niaga', 'Bank Danamon', 'BTN', 'Permata Bank', 'Bank Muamalat', 'Bank Syariah Indonesia'] as $bank)
                        <option value="{{ $bank }}" {{ old('bank_name', $financial?->bank_name) === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="bank_account_number" class="block text-sm font-medium text-gray-700">No. Rekening</label>
                <input type="text" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $financial?->bank_account_number) }}"
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
            </div>
        </div>
    </div>

    {{-- ═══ ACTIONS ═══ --}}
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('employees.index') }}"
           class="rounded-xl border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
            Batal
        </a>
        <button type="submit"
                class="rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm shadow-primary-600/25 transition hover:bg-primary-700 active:scale-[0.98]">
            {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Karyawan' }}
        </button>
    </div>
</form>
