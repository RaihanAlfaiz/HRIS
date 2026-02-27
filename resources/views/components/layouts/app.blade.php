<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HRIS - Employee Directory & HR Management System">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — HRIS</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tom Select (searchable Select2 alternative, no jQuery) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

    {{-- Alpine.js (collapse plugin + core) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full overflow-hidden bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex h-full overflow-hidden">

        {{-- ═══════════ SIDEBAR ═══════════ --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 transform bg-sidebar transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            {{-- Logo — fixed top --}}
            <div class="flex h-16 shrink-0 items-center gap-3 border-b border-white/10 px-6">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-white tracking-tight">HRIS</h1>
                    <p class="text-[11px] text-gray-400 -mt-0.5">Employee Directory</p>
                </div>
            </div>

            {{-- Navigation — scrollable middle area --}}
            <div class="relative flex-1 min-h-0">
                {{-- Top fade mask --}}
                <div class="pointer-events-none absolute top-0 left-0 right-0 z-10 h-6 bg-gradient-to-b from-sidebar to-transparent"></div>

                <nav class="sidebar-scroll flex h-full flex-col gap-0.5 overflow-y-auto px-3 py-4">
                    <p class="px-3 pt-1 pb-2 text-[10px] font-semibold uppercase tracking-widest text-gray-500">Main</p>
                    <a href="{{ route('dashboard') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('dashboard') ? 'bg-primary-600/20 text-primary-300' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        Dashboard
                    </a>

                    @if(auth()->user()->hasRole('admin', 'hr'))
                    <p class="px-3 pt-4 pb-2 text-[10px] font-semibold uppercase tracking-widest text-gray-500">HR Management</p>
                    <a href="{{ route('employees.index') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('employees.*') ? 'bg-primary-600/20 text-primary-300' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        Karyawan
                    </a>
                    <a href="{{ route('departments.index') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('departments.*') ? 'bg-primary-600/20 text-primary-300' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        Departemen
                    </a>
                    <a href="{{ route('sites.index') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('sites.*') ? 'bg-primary-600/20 text-primary-300' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Sites
                    </a>
                    <a href="{{ route('histories.index') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('histories.*') ? 'bg-primary-600/20 text-primary-300' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Riwayat
                    </a>
                    @endif

                    <p class="px-3 pt-4 pb-2 text-[10px] font-semibold uppercase tracking-widest text-gray-500">Operasional</p>
                    <div x-data="{ open: {{ request()->routeIs('attendances.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                class="group flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                       {{ request()->routeIs('attendances.*') ? 'bg-primary-600/20 text-primary-300' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                            Absensi
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-auto h-4 w-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-collapse x-cloak class="ml-8 mt-0.5 flex flex-col gap-0.5 border-l border-white/10 pl-3">
                            <a href="{{ route('attendances.index') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ request()->routeIs('attendances.index') ? 'text-primary-300' : 'text-gray-400 hover:text-white' }}">Harian</a>
                            @if(auth()->user()->hasRole('admin', 'hr'))
                                <a href="{{ route('attendances.recap') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ request()->routeIs('attendances.recap') ? 'text-primary-300' : 'text-gray-400 hover:text-white' }}">Rekap Bulanan</a>
                                <a href="{{ route('attendances.overtime') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ request()->routeIs('attendances.overtime') ? 'text-primary-300' : 'text-gray-400 hover:text-white' }}">Lembur</a>
                                <a href="{{ route('attendances.corrections') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ request()->routeIs('attendances.corrections') ? 'text-primary-300' : 'text-gray-400 hover:text-white' }}">Koreksi</a>
                                <a href="{{ route('attendances.shifts') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ request()->routeIs('attendances.shifts') ? 'text-primary-300' : 'text-gray-400 hover:text-white' }}">Shift Kerja</a>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('leaves.index') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('leaves.*') ? 'bg-primary-600/20 text-primary-300' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Cuti
                        @php $pendingLeaves = cache()->remember('pending_leaves_count', 60, fn() => \App\Models\Leave::where('status', 'pending')->count()); @endphp
                        @if($pendingLeaves > 0)
                            <span class="ml-auto inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-500 px-1.5 text-[10px] font-bold text-white">{{ $pendingLeaves }}</span>
                        @endif
                    </a>

                    <p class="px-3 pt-4 pb-2 text-[10px] font-semibold uppercase tracking-widest text-gray-500">Keuangan</p>
                    <a href="{{ route('payrolls.index') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('payrolls.*') ? 'bg-primary-600/20 text-primary-300' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Payroll
                    </a>

                    @if(auth()->user()->hasRole('admin', 'hr'))

                    <p class="px-3 pt-4 pb-2 text-[10px] font-semibold uppercase tracking-widest text-gray-500">Komunikasi</p>
                    <a href="{{ route('announcements.index') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('announcements.*') ? 'bg-primary-600/20 text-primary-300' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                        Pengumuman
                    </a>

                    <p class="px-3 pt-4 pb-2 text-[10px] font-semibold uppercase tracking-widest text-gray-500">Pengaturan</p>
                    <a href="{{ route('users.index') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('users.*') ? 'bg-primary-600/20 text-primary-300' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Manajemen Akun
                    </a>
                    @endif

                    {{-- Extra bottom padding so last item doesn't hide behind fade --}}
                    <div class="h-4 shrink-0"></div>
                </nav>

                {{-- Bottom fade mask --}}
                <div class="pointer-events-none absolute bottom-0 left-0 right-0 z-10 h-6 bg-gradient-to-t from-sidebar to-transparent"></div>
            </div>

            {{-- Bottom user section — fixed bottom --}}
            <div class="shrink-0 border-t border-white/10 p-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-600 text-sm font-semibold text-white">
                        {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="truncate text-sm font-medium text-white">{{ Auth::user()->name ?? Auth::user()->username }}</p>
                        <p class="text-xs text-gray-400">{{ ucfirst(Auth::user()->role ?? 'admin') }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-sidebar-hover hover:text-white" title="Logout">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Sidebar overlay for mobile --}}
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black/50 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        {{-- ═══════════ MAIN CONTENT ═══════════ --}}
        <div class="flex flex-1 flex-col overflow-hidden">
            {{-- Top bar --}}
            <header class="flex h-16 items-center gap-4 border-b border-gray-200 bg-white px-4 sm:px-6 lg:px-8">
                <button @click="sidebarOpen = !sidebarOpen" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <h2 class="text-lg font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h2>

                <div class="ml-auto flex items-center gap-2">
                    @isset($headerActions)
                        {{ $headerActions }}
                    @endisset
                </div>
            </header>

            {{-- Flash messages --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mx-4 mt-4 flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 sm:mx-6 lg:mx-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mx-4 mt-4 flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 sm:mx-6 lg:mx-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
