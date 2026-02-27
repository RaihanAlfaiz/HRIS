<x-layouts.app :title="'Absensi Harian'">
    <x-slot:headerActions>
        <div class="flex items-center gap-2">
            <a href="{{ route('attendances.shifts') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Shift
            </a>
            <a href="{{ route('attendances.overtime') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Lembur
            </a>
            <a href="{{ route('attendances.corrections') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Koreksi
            </a>
            <a href="{{ route('attendances.recap') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-3 py-2 text-sm font-medium text-white hover:bg-primary-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Rekap
            </a>
        </div>
    </x-slot:headerActions>

    <div class="space-y-6">

        {{-- ═══════════════════════════════════════════════════
             SECTION 1: SELF-SERVICE CHECK-IN/OUT (KARYAWAN)
             ═══════════════════════════════════════════════════ --}}
        @if($myEmployee)
        <div x-data="selfAttendance()" x-init="startClock(); getLocation();">
            {{-- My attendance card --}}
            <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
                {{-- Header with clock --}}
                <div class="bg-gradient-to-r from-primary-600 via-primary-700 to-indigo-700 px-6 py-5">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15 backdrop-blur-sm">
                                <span class="text-2xl font-bold text-white tabular-nums tracking-tight" x-text="clock" x-cloak>--:--</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Selamat {{ now()->hour < 12 ? 'Pagi' : (now()->hour < 15 ? 'Siang' : (now()->hour < 18 ? 'Sore' : 'Malam')) }}, {{ $myEmployee->full_name }}!</h2>
                                <p class="text-primary-200 text-sm" x-text="clockDate" x-cloak>Loading...</p>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($myEmployee->defaultShift)
                            <p class="text-primary-200 text-xs">Shift: {{ $myEmployee->defaultShift->name }}</p>
                            <p class="text-white font-mono font-semibold">{{ substr($myEmployee->defaultShift->start_time, 0, 5) }} — {{ substr($myEmployee->defaultShift->end_time, 0, 5) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Today status --}}
                <div class="px-6 py-5">
                    @if($myAttendance)
                        {{-- Already checked in --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
                            <div class="rounded-xl bg-emerald-50 p-3 text-center">
                                <p class="text-[10px] font-semibold text-emerald-500 uppercase tracking-wider">Check-In</p>
                                <p class="text-xl font-bold text-emerald-700 font-mono mt-0.5">{{ $myAttendance->check_in ? substr($myAttendance->check_in, 0, 5) : '—' }}</p>
                            </div>
                            <div class="rounded-xl bg-blue-50 p-3 text-center">
                                <p class="text-[10px] font-semibold text-blue-500 uppercase tracking-wider">Check-Out</p>
                                <p class="text-xl font-bold text-blue-700 font-mono mt-0.5">{{ $myAttendance->check_out ? substr($myAttendance->check_out, 0, 5) : '—' }}</p>
                            </div>
                            <div class="rounded-xl bg-gray-50 p-3 text-center">
                                <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Jam Kerja</p>
                                <p class="text-xl font-bold text-gray-700 font-mono mt-0.5">{{ $myAttendance->work_hours ?? '—' }}</p>
                            </div>
                            <div class="rounded-xl p-3 text-center {{ $myAttendance->status === 'late' ? 'bg-amber-50' : 'bg-emerald-50' }}">
                                <p class="text-[10px] font-semibold uppercase tracking-wider {{ $myAttendance->status === 'late' ? 'text-amber-500' : 'text-emerald-500' }}">Status</p>
                                <p class="text-lg font-bold mt-0.5 {{ $myAttendance->status === 'late' ? 'text-amber-700' : 'text-emerald-700' }}">{{ $myAttendance->status_icon }} {{ $myAttendance->status_label }}</p>
                            </div>
                        </div>

                        @if($myAttendance->late_minutes > 0)
                        <div class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 mb-5 flex items-center gap-2">
                            <span class="text-amber-600">⏰</span>
                            <p class="text-sm text-amber-800">Terlambat <strong>{{ $myAttendance->late_formatted }}</strong></p>
                        </div>
                        @endif

                        @if(!$myAttendance->check_out)
                            {{-- SHOW CHECK-OUT BUTTON --}}
                            <div class="space-y-4">
                                {{-- Camera --}}
                                <div class="relative rounded-xl overflow-hidden bg-gray-900 aspect-video max-w-lg mx-auto">
                                    <video x-ref="video" autoplay playsinline muted
                                           class="w-full h-full object-cover"
                                           x-show="!capturedPhoto"></video>
                                    <img x-show="capturedPhoto" :src="capturedPhoto" class="w-full h-full object-cover" x-cloak>

                                    <div x-show="!capturedPhoto && !cameraError" class="absolute inset-0 pointer-events-none">
                                        <div class="absolute top-4 left-4 w-12 h-12 border-t-2 border-l-2 border-white/60 rounded-tl-lg"></div>
                                        <div class="absolute top-4 right-4 w-12 h-12 border-t-2 border-r-2 border-white/60 rounded-tr-lg"></div>
                                        <div class="absolute bottom-4 left-4 w-12 h-12 border-b-2 border-l-2 border-white/60 rounded-bl-lg"></div>
                                        <div class="absolute bottom-4 right-4 w-12 h-12 border-b-2 border-r-2 border-white/60 rounded-br-lg"></div>
                                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 rounded-full bg-black/50 px-3 py-1">
                                            <span class="text-white text-sm font-mono" x-text="clock + ':' + clockSeconds"></span>
                                        </div>
                                    </div>

                                    <div x-show="cameraError" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-800" x-cloak>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        <p class="text-gray-400 text-sm">Kamera tidak tersedia</p>
                                        <p class="text-gray-500 text-xs mt-1">Harap izinkan akses kamera di browser Anda</p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-center gap-3">
                                    <button type="button" @click="startCamera(); capturePhoto()"
                                            x-show="!cameraStarted && !capturedPhoto"
                                            class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition">
                                        📸 Buka Kamera
                                    </button>
                                    <button type="button" @click="capturePhoto()"
                                            x-show="cameraStarted && !capturedPhoto && !cameraError" x-cloak
                                            class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 transition shadow">
                                        📸 Ambil Foto
                                    </button>
                                    <button type="button" @click="retakePhoto()" x-show="capturedPhoto" x-cloak
                                            class="inline-flex items-center gap-2 rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                        🔄 Ulangi
                                    </button>
                                </div>

                                {{-- Location info --}}
                                <div class="flex items-center justify-center gap-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="text-gray-500" x-text="latitude ? 'Lokasi: ' + latitude + ', ' + longitude : 'Mendeteksi lokasi...'"></span>
                                </div>

                                {{-- Check-out form --}}
                                <form method="POST" action="{{ route('attendances.check-out') }}" class="flex justify-center">
                                    @csrf
                                    <input type="hidden" name="employee_id" value="{{ $myEmployee->id }}">
                                    <input type="hidden" name="photo" x-bind:value="capturedPhoto">
                                    <input type="hidden" name="latitude" x-bind:value="latitude">
                                    <input type="hidden" name="longitude" x-bind:value="longitude">
                                    <button type="submit"
                                            x-bind:disabled="!capturedPhoto"
                                            x-bind:class="!capturedPhoto ? 'opacity-50 cursor-not-allowed saturate-50' : 'hover:scale-[1.02] active:scale-95 hover:from-blue-600 hover:to-blue-700'"
                                            class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-blue-500 to-blue-600 px-10 py-4 text-lg font-bold text-white transition shadow-lg shadow-blue-500/30 transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        <span x-text="!capturedPhoto ? 'Ambil Foto Dulu' : 'CHECK-OUT'"></span>
                                    </button>
                                </form>
                            </div>
                        @else
                            {{-- Already checked out --}}
                            <div class="text-center py-4">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <p class="text-lg font-bold text-gray-900">Absensi Hari Ini Selesai! 🎉</p>
                                <p class="text-sm text-gray-500 mt-1">Terima kasih atas kerja kerasnya.</p>
                                @if($myAttendance->overtime_minutes > 0)
                                <div class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700">
                                    ⚡ Lembur {{ $myAttendance->overtime_formatted }} ({{ $myAttendance->overtime_status_label }})
                                </div>
                                @endif
                            </div>
                        @endif
                    @else
                        {{-- NOT CHECKED IN YET — show big check-in --}}
                        <div class="space-y-4">
                            {{-- Camera --}}
                            <div class="relative rounded-xl overflow-hidden bg-gray-900 aspect-video max-w-lg mx-auto">
                                <video x-ref="video" autoplay playsinline muted
                                       class="w-full h-full object-cover"
                                       x-show="cameraStarted && !capturedPhoto"></video>
                                <img x-show="capturedPhoto" :src="capturedPhoto" class="w-full h-full object-cover" x-cloak>

                                {{-- Placeholder before camera starts --}}
                                <div x-show="!cameraStarted && !capturedPhoto" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <p class="text-gray-400 text-sm">Klik tombol di bawah untuk buka kamera</p>
                                </div>

                                {{-- Camera overlay --}}
                                <div x-show="cameraStarted && !capturedPhoto && !cameraError" class="absolute inset-0 pointer-events-none" x-cloak>
                                    <div class="absolute top-4 left-4 w-12 h-12 border-t-2 border-l-2 border-white/60 rounded-tl-lg"></div>
                                    <div class="absolute top-4 right-4 w-12 h-12 border-t-2 border-r-2 border-white/60 rounded-tr-lg"></div>
                                    <div class="absolute bottom-4 left-4 w-12 h-12 border-b-2 border-l-2 border-white/60 rounded-bl-lg"></div>
                                    <div class="absolute bottom-4 right-4 w-12 h-12 border-b-2 border-r-2 border-white/60 rounded-br-lg"></div>
                                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 rounded-full bg-black/50 px-3 py-1">
                                        <span class="text-white text-sm font-mono" x-text="clock + ':' + clockSeconds"></span>
                                    </div>
                                </div>

                                <div x-show="cameraError" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-800" x-cloak>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <p class="text-gray-400 text-sm">Kamera tidak tersedia</p>
                                    <p class="text-gray-500 text-xs mt-1">Harap izinkan akses kamera di browser Anda</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-center gap-3">
                                <button type="button" @click="startCamera()"
                                        x-show="!cameraStarted && !capturedPhoto"
                                        class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition">
                                    📸 Buka Kamera
                                </button>
                                <button type="button" @click="capturePhoto()"
                                        x-show="cameraStarted && !capturedPhoto && !cameraError" x-cloak
                                        class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 transition shadow">
                                    📸 Ambil Foto
                                </button>
                                <button type="button" @click="retakePhoto()" x-show="capturedPhoto" x-cloak
                                        class="inline-flex items-center gap-2 rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                    🔄 Ulangi
                                </button>
                            </div>

                            {{-- Shift selector --}}
                            <div class="max-w-lg mx-auto">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Shift Kerja</label>
                                <select x-model="shiftId" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                                    @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ ($myEmployee->default_shift_id == $shift->id || $shift->is_default) ? 'selected' : '' }}>
                                        {{ $shift->name }} ({{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Tipe Lokasi Kerja --}}
                            <div class="max-w-lg mx-auto mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Lokasi (Work From)</label>
                                <select x-model="workFrom" name="work_from" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                                    <option value="WFO">WFO (Work From Office)</option>
                                    <option value="WFH">WFH (Work From Home)</option>
                                    <option value="Lainnya">Lainnya (Dinas / Luar Kota)</option>
                                </select>
                            </div>

                            {{-- Location info --}}
                            <div class="flex items-center justify-center gap-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="text-gray-500" x-text="latitude ? 'Lokasi: ' + latitude + ', ' + longitude : 'Mendeteksi lokasi...'"></span>
                            </div>

                            {{-- Check-in form --}}
                            <form method="POST" action="{{ route('attendances.check-in') }}" class="flex justify-center">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $myEmployee->id }}">
                                <input type="hidden" name="shift_id" x-bind:value="shiftId">
                                <input type="hidden" name="photo" x-bind:value="capturedPhoto">
                                <input type="hidden" name="latitude" x-bind:value="latitude">
                                <input type="hidden" name="longitude" x-bind:value="longitude">
                                <input type="hidden" name="work_from" x-bind:value="workFrom">
                                <button type="submit"
                                        x-bind:disabled="!capturedPhoto"
                                        x-bind:class="!capturedPhoto ? 'opacity-50 cursor-not-allowed saturate-50' : 'hover:scale-[1.02] active:scale-95 hover:from-emerald-600 hover:to-emerald-700'"
                                        class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 px-10 py-4 text-lg font-bold text-white transition shadow-lg shadow-emerald-500/30 transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                    <span x-text="!capturedPhoto ? 'Ambil Foto Dulu' : 'CHECK-IN SEKARANG'"></span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            <canvas x-ref="canvas" class="hidden"></canvas>
        </div>
        @elseif(!auth()->user()->hasRole('admin', 'hr'))
            {{-- Employee without linked account --}}
            <div class="rounded-2xl bg-amber-50 border border-amber-200 p-8 text-center">
                <span class="text-4xl mb-3 block">⚠️</span>
                <h3 class="text-lg font-bold text-amber-800">Akun belum terhubung</h3>
                <p class="text-amber-700 text-sm mt-1">Akun login kamu belum dihubungkan dengan data karyawan. Hubungi admin/HR untuk mengaktifkan fitur absensi.</p>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════
             SECTION 2: ADMIN/HR MONITORING DASHBOARD
             ═══════════════════════════════════════════════════ --}}
        @if(auth()->user()->hasRole('admin', 'hr'))
        <div x-data="{ search: '', showBulkModal: false }"
             {{ !$myEmployee ? "x-init=\"
                const tick = () => { const n=new Date(); document.getElementById('adminClock').textContent = n.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',hour12:false}); };
                tick(); setInterval(tick,1000);
             \"" : '' }}>

            {{-- Date selector + clock for admin --}}
            @if(!$myEmployee)
            <div class="flex items-center gap-4 mb-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 shadow-lg shadow-primary-500/25">
                    <span id="adminClock" class="text-xl font-bold text-white tabular-nums">--:--</span>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <p class="text-sm text-gray-500">Monitoring Kehadiran</p>
                </div>
            </div>
            @endif

            {{-- Stats header --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">📊 Monitor Kehadiran</h3>
                <form method="GET" class="flex items-center gap-2">
                    <input type="date" name="date" value="{{ $date }}"
                           class="rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                           onchange="this.form.submit()">
                </form>
            </div>

            {{-- Stats cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 mb-6">
                @php
                $statCards = [
                    ['label' => 'Total',     'value' => $stats['total'],      'color' => 'bg-gray-600',    'icon' => '👥'],
                    ['label' => 'Hadir',     'value' => $stats['present'],    'color' => 'bg-emerald-500', 'icon' => '✓'],
                    ['label' => 'Terlambat', 'value' => $stats['late'],       'color' => 'bg-amber-500',   'icon' => '⏰'],
                    ['label' => 'Alpa',      'value' => $stats['absent'],     'color' => 'bg-red-500',     'icon' => '✗'],
                    ['label' => 'Sakit',     'value' => $stats['sick'],       'color' => 'bg-orange-500',  'icon' => '🏥'],
                    ['label' => 'Cuti',      'value' => $stats['leave'],      'color' => 'bg-blue-500',    'icon' => '🏖'],
                    ['label' => 'Libur',     'value' => $stats['holiday'],    'color' => 'bg-purple-500',  'icon' => '🎉'],
                    ['label' => 'Belum',     'value' => $stats['not_yet'],    'color' => 'bg-gray-400',    'icon' => '⏳'],
                ];
                @endphp
                @foreach($statCards as $card)
                <div class="relative overflow-hidden rounded-xl bg-white p-3 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">{{ $card['icon'] }}</span>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
                            <p class="text-[11px] text-gray-500 font-medium">{{ $card['label'] }}</p>
                        </div>
                    </div>
                    <div class="absolute -right-2 -bottom-2 h-10 w-10 rounded-full {{ $card['color'] }} opacity-10"></div>
                </div>
                @endforeach
            </div>

            {{-- Employee table --}}
            <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-4 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <h3 class="text-base font-semibold text-gray-900">Daftar Kehadiran</h3>
                        <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">{{ $date }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" placeholder="Cari karyawan..." x-model="search"
                                   class="w-56 rounded-xl border border-gray-200 bg-gray-50/50 pl-9 pr-4 py-2 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                        </div>
                        <button @click="showBulkModal = true" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Massal
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/80 text-left">
                                <th class="px-4 py-3 font-semibold text-gray-600 w-8">#</th>
                                <th class="px-4 py-3 font-semibold text-gray-600">Karyawan</th>
                                <th class="px-4 py-3 font-semibold text-gray-600">Shift</th>
                                <th class="px-4 py-3 font-semibold text-gray-600 text-center">Check-In</th>
                                <th class="px-4 py-3 font-semibold text-gray-600 text-center">Check-Out</th>
                                <th class="px-4 py-3 font-semibold text-gray-600 text-center">Telat</th>
                                <th class="px-4 py-3 font-semibold text-gray-600 text-center">Jam Kerja</th>
                                <th class="px-4 py-3 font-semibold text-gray-600 text-center">TipeLokasi</th>
                                <th class="px-4 py-3 font-semibold text-gray-600 text-center">Status</th>
                                <th class="px-4 py-3 font-semibold text-gray-600 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($employees as $i => $employee)
                            @php $att = $employee->attendances->first(); @endphp
                            <tr class="group hover:bg-gray-50/50 transition"
                                x-show="!search || '{{ strtolower($employee->full_name) }}'.includes(search.toLowerCase())"
                                x-cloak>
                                <td class="px-4 py-3 text-gray-400 text-xs">{{ $i + 1 }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700">
                                            {{ strtoupper(substr($employee->full_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $employee->full_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $employee->department?->name ?? '—' }} · {{ $employee->nip }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($att && $att->shift)
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700">
                                            {{ $att->shift->code }}
                                        </span>
                                    @elseif($employee->defaultShift)
                                        <span class="text-xs text-gray-400">{{ $employee->defaultShift->code }}</span>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($att && $att->check_in)
                                        <div class="inline-flex items-center gap-1">
                                            @if($att->check_in_photo)
                                                <span class="text-emerald-500 text-xs" title="Dengan foto">📸</span>
                                            @endif
                                            <span class="font-mono text-sm font-semibold {{ $att->status === 'late' ? 'text-amber-600' : 'text-emerald-600' }}">
                                                {{ substr($att->check_in, 0, 5) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($att && $att->check_out)
                                        <div class="inline-flex items-center gap-1">
                                            @if($att->check_out_photo)
                                                <span class="text-blue-500 text-xs">📸</span>
                                            @endif
                                            <span class="font-mono text-sm font-semibold text-blue-600">
                                                {{ substr($att->check_out, 0, 5) }}
                                            </span>
                                        </div>
                                    @elseif($att && $att->check_in)
                                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse" title="Masih di kantor"></span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($att && $att->late_minutes > 0)
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700">
                                            ⏰ {{ $att->late_formatted }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($att && $att->work_hours)
                                        <span class="font-mono text-sm font-medium text-gray-700">{{ $att->work_hours }}</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($att && $att->work_from)
                                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                            {{ $att->work_from }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($att)
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold {{ $att->status_color }}">
                                            {{ $att->status_icon }} {{ $att->status_label }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-400">Belum</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="flex items-center gap-1.5 opacity-0 transition group-hover:opacity-100">

                                        </div>
                                        @if($att)
                                        <div x-data="{ open: false }" class="relative inline-block">
                                            <button @click="open = !open" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false" x-transition
                                                 class="absolute right-0 z-20 mt-1 w-40 rounded-xl bg-white shadow-lg border border-gray-100 py-1">
                                                @foreach(['present' => 'Hadir', 'late' => 'Terlambat', 'absent' => 'Alpa', 'sick' => 'Sakit', 'leave' => 'Cuti', 'holiday' => 'Libur'] as $val => $lbl)
                                                <form method="POST" action="{{ route('attendances.update-status', $att) }}">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="{{ $val }}">
                                                    <button type="submit" class="w-full px-3 py-1.5 text-left text-sm hover:bg-gray-50 transition {{ $att->status === $val ? 'font-semibold text-primary-600' : 'text-gray-700' }}">
                                                        {{ $lbl }}
                                                    </button>
                                                </form>
                                                @endforeach
                                            </div>
                                        </div>
                                        @else
                                            <span class="text-xs text-gray-300">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center text-gray-400">Belum ada data karyawan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Bulk mark modal --}}
            <div x-show="showBulkModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-cloak>
                <div @click.away="showBulkModal = false" x-show="showBulkModal" x-transition
                     class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Absensi Massal</h3>
                        <p class="text-sm text-gray-500">Tandai semua karyawan sekaligus</p>
                    </div>
                    <form method="POST" action="{{ route('attendances.bulk-mark') }}">
                        @csrf
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" name="date" value="{{ $date }}" class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                                    <option value="holiday">🎉 Libur</option>
                                    <option value="present">✓ Hadir</option>
                                    <option value="absent">✗ Alpa</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
                                <select name="shift_id" class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                                    @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ $shift->is_default ? 'selected' : '' }}>{{ $shift->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="border-t border-gray-100 px-6 py-4 flex justify-end gap-3">
                            <button type="button" @click="showBulkModal = false" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700">Batal</button>
                            <button type="submit" class="rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition">Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script>
    function selfAttendance() {
        return {
            clock: '--:--',
            clockSeconds: '--',
            clockDate: 'Memuat tanggal...',
            cameraStarted: false,
            capturedPhoto: null,
            stream: null,
            cameraError: false,
            shiftId: '{{ $myEmployee->default_shift_id ?? ($shifts->first()?->id ?? '') }}',
            workFrom: 'WFO',
            latitude: null,
            longitude: null,

            startClock() {
                const tick = () => {
                    const now = new Date();
                    this.clock = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
                    this.clockSeconds = String(now.getSeconds()).padStart(2, '0');
                    this.clockDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                };
                tick();
                setInterval(tick, 1000);
            },

            getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            this.latitude = pos.coords.latitude.toFixed(7);
                            this.longitude = pos.coords.longitude.toFixed(7);
                        },
                        () => {},
                        { enableHighAccuracy: true }
                    );
                }
            },

            async startCamera() {
                try {
                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: 'user', width: 640, height: 480 }
                    });
                    this.$nextTick(() => {
                        if (this.$refs.video) {
                            this.$refs.video.srcObject = this.stream;
                        }
                    });
                    this.cameraStarted = true;
                    this.cameraError = false;
                } catch (e) {
                    this.cameraStarted = true;
                    this.cameraError = true;
                }
            },

            capturePhoto() {
                if (!this.cameraStarted) {
                    this.startCamera();
                    return;
                }
                const video = this.$refs.video;
                const canvas = this.$refs.canvas;
                if (!video || !canvas || !video.videoWidth) return;

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0);

                // Add timestamp watermark
                const now = new Date();
                const ts = now.toLocaleDateString('id-ID') + ' ' + now.toLocaleTimeString('id-ID');
                ctx.fillStyle = 'rgba(0,0,0,0.5)';
                ctx.fillRect(0, canvas.height - 30, canvas.width, 30);
                ctx.fillStyle = '#fff';
                ctx.font = '14px monospace';
                ctx.fillText(ts, 10, canvas.height - 10);

                this.capturedPhoto = canvas.toDataURL('image/jpeg', 0.8);
            },

            retakePhoto() {
                this.capturedPhoto = '';
                if (!this.stream || !this.stream.active) {
                    this.startCamera();
                }
            }
        };
    }
    </script>
</x-layouts.app>
