<x-layouts.app :title="$employee->full_name">
    <x-slot:headerActions>
        <div class="flex items-center gap-2" x-data="{ confirmDelete: false }">
            <a href="{{ route('employees.edit', $employee) }}"
               class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700 active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Data
            </a>
            <button @click="confirmDelete = true" type="button"
                    class="inline-flex items-center gap-2 rounded-xl border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-600 shadow-sm transition hover:bg-red-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus
            </button>
            <a href="{{ route('employees.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>

            {{-- Delete Confirmation Modal --}}
            <div x-show="confirmDelete" x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                <div class="fixed inset-0 bg-gray-900/50" @click="confirmDelete = false"></div>
                <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-center text-lg font-semibold text-gray-900">Hapus Karyawan?</h3>
                    <p class="mt-2 text-center text-sm text-gray-500">
                        Data <strong>{{ $employee->full_name }}</strong> beserta semua dokumen, profil, dan data terkait akan dihapus permanen. Aksi ini tidak bisa dibatalkan.
                    </p>
                    <div class="mt-6 flex gap-3">
                        <button @click="confirmDelete = false" type="button"
                                class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                            Batal
                        </button>
                        <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-700 active:scale-[0.98]">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:headerActions>

    <div class="space-y-6">
        {{-- ═══ HEADER CARD ═══ --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="h-32 bg-gradient-to-r from-primary-600 via-primary-500 to-primary-400"></div>
            <div class="relative px-6 pb-6">
                <div class="-mt-12 flex flex-col sm:flex-row sm:items-end sm:gap-5">
                    <div class="flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-primary-100 text-3xl font-bold text-primary-600 shadow-lg">
                        {{ strtoupper(substr($employee->full_name, 0, 2)) }}
                    </div>
                    <div class="mt-3 sm:mt-0 sm:pb-1">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $employee->full_name }}</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ $employee->position }} · {{ $employee->department?->name }}</p>
                    </div>
                    <div class="mt-3 sm:ml-auto sm:mt-0 sm:pb-1">
                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium
                            {{ $employee->employment_status === 'Permanent' ? 'bg-emerald-100 text-emerald-700' :
                               ($employee->employment_status === 'Contract' ? 'bg-amber-100 text-amber-700' :
                               ($employee->employment_status === 'Probation' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700')) }}">
                            {{ $employee->employment_status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- ═══ DATA KEPEGAWAIAN ═══ --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                    </svg>
                    Data Kepegawaian
                </h3>
                <dl class="mt-4 space-y-3">
                    @foreach([
                        'NIP' => $employee->nip,
                        'Nama Lengkap' => $employee->full_name,
                        'Departemen' => $employee->department?->name,
                        'Jabatan' => $employee->position,
                        'Status Kepegawaian' => $employee->employment_status,
                        'Tanggal Bergabung' => $employee->join_date->format('d F Y'),
                    ] as $label => $value)
                        <div class="flex items-start gap-4 rounded-lg px-3 py-2 odd:bg-gray-50/50">
                            <dt class="w-40 shrink-0 text-sm text-gray-500">{{ $label }}</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $value ?? '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- ═══ DATA PRIBADI ═══ --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Data Pribadi
                </h3>
                <dl class="mt-4 space-y-3">
                    @foreach([
                        'NIK KTP' => $employee->profile?->nik_ktp,
                        'Tempat Lahir' => $employee->profile?->place_of_birth,
                        'Tanggal Lahir' => $employee->profile?->date_of_birth?->format('d F Y'),
                        'Jenis Kelamin' => $employee->profile?->gender,
                        'Agama' => $employee->profile?->religion,
                        'Status Pernikahan' => $employee->profile?->marital_status,
                        'Golongan Darah' => $employee->profile?->blood_type,
                        'Alamat KTP' => $employee->profile?->address_ktp,
                        'Alamat Domisili' => $employee->profile?->address_domicile,
                    ] as $label => $value)
                        <div class="flex items-start gap-4 rounded-lg px-3 py-2 odd:bg-gray-50/50">
                            <dt class="w-40 shrink-0 text-sm text-gray-500">{{ $label }}</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $value ?? '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- ═══ KONTAK ═══ --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Kontak & Darurat
                </h3>
                <dl class="mt-4 space-y-3">
                    @foreach([
                        'Email Kantor' => $employee->contact?->email_work,
                        'Email Pribadi' => $employee->contact?->email_personal,
                        'No. Telepon' => $employee->contact?->phone_number,
                        'Kontak Darurat' => $employee->contact?->emergency_contact_name,
                        'Telepon Darurat' => $employee->contact?->emergency_contact_phone,
                        'Hubungan' => $employee->contact?->emergency_contact_relation,
                    ] as $label => $value)
                        <div class="flex items-start gap-4 rounded-lg px-3 py-2 odd:bg-gray-50/50">
                            <dt class="w-40 shrink-0 text-sm text-gray-500">{{ $label }}</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $value ?? '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- ═══ FINANSIAL ═══ --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Finansial & Administrasi
                </h3>
                <dl class="mt-4 space-y-3">
                    @foreach([
                        'NPWP' => $employee->financial?->npwp,
                        'BPJS Kesehatan' => $employee->financial?->bpjs_kesehatan,
                        'BPJS Ketenagakerjaan' => $employee->financial?->bpjs_ketenagakerjaan,
                        'Nama Bank' => $employee->financial?->bank_name,
                        'No. Rekening' => $employee->financial?->bank_account_number,
                    ] as $label => $value)
                        <div class="flex items-start gap-4 rounded-lg px-3 py-2 odd:bg-gray-50/50">
                            <dt class="w-40 shrink-0 text-sm text-gray-500">{{ $label }}</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $value ?? '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>

        {{-- ═══ DOKUMEN ═══ --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm" x-data="{ showUpload: false, docType: 'Foto' }">
            <div class="flex items-center justify-between mb-5">
                <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Dokumen & File
                </h3>
                <button @click="showUpload = !showUpload" type="button"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-3.5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700 active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Upload Dokumen
                </button>
            </div>

            {{-- Upload Form (collapsible) --}}
            <div x-show="showUpload" x-collapse x-cloak class="mb-6">
                <form method="POST" action="{{ route('employee-documents.store', $employee) }}" enctype="multipart/form-data"
                      class="rounded-xl border border-gray-200 bg-gray-50/50 p-5">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        {{-- Document Type --}}
                        <div>
                            <label for="document_type" class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Dokumen</label>
                            <select id="document_type" name="document_type" x-model="docType" required
                                    class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                                <option value="Foto">Foto</option>
                                <option value="CV">CV</option>
                                <option value="Portofolio PDF">Portofolio PDF</option>
                                <option value="Portofolio URL">Portofolio URL</option>
                            </select>
                        </div>

                        {{-- File Input (for Foto, CV, Portofolio PDF) --}}
                        <div x-show="docType !== 'Portofolio URL'">
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-1.5">Pilih File</label>
                            <input type="file" id="file" name="file"
                                   :accept="docType === 'Foto' ? '.jpg,.jpeg,.png,.webp' : (docType === 'CV' ? '.pdf,.doc,.docx' : '.pdf')"
                                   class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary-100 file:px-3 file:py-1 file:text-sm file:font-medium file:text-primary-700 transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                            <p class="mt-1 text-xs text-gray-400">
                                <template x-if="docType === 'Foto'">
                                    <span>Format: JPG, PNG, WebP. Maks 5MB</span>
                                </template>
                                <template x-if="docType === 'CV'">
                                    <span>Format: PDF, DOC, DOCX. Maks 5MB</span>
                                </template>
                                <template x-if="docType === 'Portofolio PDF'">
                                    <span>Format: PDF. Maks 5MB</span>
                                </template>
                            </p>
                        </div>

                        {{-- URL Input (for Portofolio URL) --}}
                        <div x-show="docType === 'Portofolio URL'">
                            <label for="url_link" class="block text-sm font-medium text-gray-700 mb-1.5">URL Portofolio</label>
                            <input type="url" id="url_link" name="url_link" placeholder="https://portfolio.example.com"
                                   class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                        </div>

                        {{-- Submit --}}
                        <div class="flex items-end">
                            <button type="submit"
                                    class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-700 active:scale-[0.98]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Upload
                            </button>
                        </div>
                    </div>
                    @error('file') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('url_link') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('document_type') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </form>
            </div>

            {{-- Document List --}}
            @if($employee->documents->count() > 0)
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($employee->documents as $doc)
                        <div class="group flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 transition hover:border-primary-200 hover:shadow-sm">
                            {{-- Icon --}}
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                                {{ $doc->document_type === 'Foto' ? 'bg-blue-100 text-blue-600' :
                                   ($doc->document_type === 'CV' ? 'bg-emerald-100 text-emerald-600' :
                                   ($doc->document_type === 'Portofolio PDF' ? 'bg-red-100 text-red-600' : 'bg-violet-100 text-violet-600')) }}">
                                @if($doc->document_type === 'Foto')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                @elseif($doc->document_type === 'Portofolio URL')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $doc->document_type }}</p>
                                @if($doc->file_path)
                                    <p class="truncate text-xs text-gray-400">{{ basename($doc->file_path) }}</p>
                                @elseif($doc->url_link)
                                    <a href="{{ $doc->url_link }}" target="_blank" class="truncate block text-xs text-primary-500 hover:underline">{{ $doc->url_link }}</a>
                                @endif
                                <p class="text-xs text-gray-400 mt-0.5">{{ $doc->created_at->format('d M Y H:i') }}</p>
                            </div>

                            {{-- Actions --}}
                            <div class="flex shrink-0 items-center gap-1">
                                @if($doc->file_path)
                                    <a href="{{ route('employee-documents.download', [$employee, $doc]) }}"
                                       class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-primary-600" title="Download">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @elseif($doc->url_link)
                                    <a href="{{ $doc->url_link }}" target="_blank"
                                       class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-primary-600" title="Buka Link">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('employee-documents.destroy', [$employee, $doc]) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-red-50 hover:text-red-600" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border-2 border-dashed border-gray-200 py-10 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-3 text-sm font-medium text-gray-500">Belum ada dokumen</p>
                    <p class="mt-1 text-xs text-gray-400">Klik "Upload Dokumen" untuk menambahkan file</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
