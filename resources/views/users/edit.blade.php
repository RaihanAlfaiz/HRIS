<x-layouts.app :title="'Edit Akun: ' . $user->username">
    <x-slot:headerActions>
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            ← Kembali
        </a>
    </x-slot:headerActions>

    <div class="max-w-2xl mx-auto">
        <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
                <h2 class="text-xl font-bold text-white">Edit Akun</h2>
                <p class="text-blue-200 text-sm mt-0.5">{{ $user->username }} · {{ ucfirst($user->role) }}</p>
            </div>

            <form method="POST" action="{{ route('users.update', $user) }}" class="p-6 space-y-5">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                               class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none @error('username') border-red-500 @enderror">
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password" minlength="6"
                               class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none @error('password') border-red-500 @enderror"
                               placeholder="Kosongkan jika tidak diubah">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                               class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                               placeholder="Ulangi password baru">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-3 gap-3">
                        @php
                            $roles = [
                                'admin'  => ['label' => 'Admin', 'desc' => 'Akses penuh ke semua fitur', 'icon' => '🛡️', 'color' => 'peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:ring-red-500'],
                                'hr'     => ['label' => 'HR', 'desc' => 'Kelola karyawan, absensi, cuti', 'icon' => '👥', 'color' => 'peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-blue-500'],
                                'viewer' => ['label' => 'Karyawan', 'desc' => 'Lihat data & absen sendiri', 'icon' => '👁️', 'color' => 'peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:ring-emerald-500'],
                            ];
                        @endphp
                        @foreach($roles as $val => $info)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="{{ $val }}" class="peer sr-only" {{ old('role', $user->role) === $val ? 'checked' : '' }}>
                            <div class="rounded-xl border-2 border-gray-200 p-3 text-center transition hover:border-gray-300 peer-checked:ring-1 {{ $info['color'] }}">
                                <span class="text-2xl">{{ $info['icon'] }}</span>
                                <p class="font-semibold text-gray-900 text-sm mt-1">{{ $info['label'] }}</p>
                                <p class="text-[10px] text-gray-500 mt-0.5">{{ $info['desc'] }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                @if(auth()->user()->isAdmin())
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja / Site <span class="text-red-500">*</span></label>
                    <select name="site_id" class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none @error('site_id') border-red-500 @enderror">
                        <option value="">— Pilih Site —</option>
                        @foreach($sites as $site)
                        <option value="{{ $site->id }}" {{ old('site_id', $user->site_id) == $site->id ? 'selected' : '' }}>
                            {{ $site->name }} ({{ $site->code }})
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1 italic">Kosongkan jika Admin ini mengelola semua site.</p>
                    @error('site_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hubungkan ke Karyawan</label>
                    <select name="employee_id" class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                        <option value="">— Tidak dihubungkan —</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id', $linkedEmployeeId) == $employee->id ? 'selected' : '' }}>
                            {{ $employee->full_name }} ({{ $employee->nip }})
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih karyawan yang akan dihubungkan dengan akun ini.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('users.index') }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition shadow-sm shadow-primary-500/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
