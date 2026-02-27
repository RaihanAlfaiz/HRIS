<x-layouts.app :title="'Manajemen Akun'">
    <x-slot:headerActions>
        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 transition shadow-sm shadow-primary-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Tambah Akun
        </a>
    </x-slot:headerActions>

    <div class="space-y-6">
        {{-- Info roles --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            @php
                $roleCounts = [
                    'admin'  => $users->where('role', 'admin')->count(),
                    'hr'     => $users->where('role', 'hr')->count(),
                    'viewer' => $users->where('role', 'viewer')->count(),
                ];
                $roleInfo = [
                    'admin'  => ['label' => 'Admin', 'desc' => 'Akses penuh', 'color' => 'from-red-500 to-rose-600', 'icon' => '🛡️'],
                    'hr'     => ['label' => 'HR', 'desc' => 'Kelola karyawan & absensi', 'color' => 'from-blue-500 to-indigo-600', 'icon' => '👥'],
                    'viewer' => ['label' => 'Viewer', 'desc' => 'Lihat data & absen sendiri', 'color' => 'from-emerald-500 to-teal-600', 'icon' => '👁️'],
                ];
            @endphp
            @foreach($roleInfo as $role => $info)
            <div class="rounded-xl bg-white shadow-sm border border-gray-100 p-4 flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br {{ $info['color'] }} text-white text-lg shadow-sm">
                    {{ $info['icon'] }}
                </div>
                <div>
                    <p class="font-bold text-gray-900">{{ $roleCounts[$role] }} {{ $info['label'] }}</p>
                    <p class="text-xs text-gray-500">{{ $info['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Users table --}}
        <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 text-left">
                            <th class="px-5 py-3.5 font-semibold text-gray-600 w-8">#</th>
                            <th class="px-5 py-3.5 font-semibold text-gray-600">Username</th>
                            <th class="px-5 py-3.5 font-semibold text-gray-600">Nama</th>
                            <th class="px-5 py-3.5 font-semibold text-gray-600 font-medium">Role</th>
                            <th class="px-5 py-3.5 font-semibold text-gray-600 font-medium text-xs uppercase tracking-wider">Unit / Site</th>
                            <th class="px-5 py-3.5 font-semibold text-gray-600 font-medium">Karyawan Terhubung</th>
                            <th class="px-5 py-3.5 font-semibold text-gray-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $i => $user)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $i + 1 }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : ($user->role === 'hr' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700') }}
                                                text-xs font-bold">
                                        {{ strtoupper(substr($user->username, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $user->username }}</p>
                                        @if($user->id === auth()->id())
                                            <span class="text-[10px] font-semibold text-primary-600">(Anda)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-700">{{ $user->name }}</td>
                            <td class="px-5 py-3.5">
                                @php
                                    $roleColors = [
                                        'admin'  => 'bg-red-50 text-red-700 ring-red-600/10',
                                        'hr'     => 'bg-blue-50 text-blue-700 ring-blue-600/10',
                                        'viewer' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $roleColors[$user->role] ?? '' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($user->site)
                                    <span class="inline-flex items-center gap-1.5 text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $user->site->name }}
                                    </span>
                                @else
                                    <span class="text-xs font-medium text-gray-400 bg-gray-50 px-2 py-1 rounded">Semua / Global</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @if($user->employee)
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                        <span class="text-gray-700">{{ $user->employee->full_name }}</span>
                                        <span class="text-xs text-gray-400">({{ $user->employee->nip }})</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">— Belum terhubung</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5" x-data="{ showReset: false }">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('users.edit', $user) }}"
                                       class="rounded-lg p-2 text-gray-400 hover:bg-blue-50 hover:text-blue-600 transition" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button @click="showReset = !showReset"
                                            class="rounded-lg p-2 text-gray-400 hover:bg-amber-50 hover:text-amber-600 transition" title="Reset Password">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus akun {{ $user->username }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-600 transition" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>

                                {{-- Inline reset password --}}
                                <div x-show="showReset" x-transition x-cloak class="mt-2">
                                    <form method="POST" action="{{ route('users.reset-password', $user) }}" class="flex items-center gap-2">
                                        @csrf @method('PUT')
                                        <input type="password" name="password" placeholder="Password baru" required minlength="6"
                                               class="flex-1 rounded-lg border border-gray-300 bg-gray-50/50 px-3 py-1.5 text-xs transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                                        <input type="password" name="password_confirmation" placeholder="Konfirmasi" required
                                               class="flex-1 rounded-lg border border-gray-300 bg-gray-50/50 px-3 py-1.5 text-xs transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                                        <button type="submit" class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-amber-600 transition">Reset</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
