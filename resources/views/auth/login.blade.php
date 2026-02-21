<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HRIS - Login">
    <title>Login — HRIS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased">
    <div class="flex min-h-full">

        {{-- Left panel: branding --}}
        <div class="hidden w-1/2 bg-gradient-to-br from-primary-900 via-primary-800 to-primary-950 lg:flex lg:flex-col lg:justify-center lg:px-16">
            <div class="max-w-md">
                <div class="mb-8 flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white tracking-tight">HRIS</h1>
                <p class="mt-2 text-lg text-primary-200">Employee Directory & HR Management System</p>
                <p class="mt-6 text-sm leading-relaxed text-primary-300/80">
                    Sistem manajemen data karyawan yang cepat, aman, dan efisien. Dirancang khusus untuk HRD dalam mengelola lebih dari 1500 data karyawan.
                </p>

                {{-- Decorative dots --}}
                <div class="mt-12 flex gap-2">
                    <div class="h-2 w-2 rounded-full bg-primary-400/40"></div>
                    <div class="h-2 w-8 rounded-full bg-primary-400/60"></div>
                    <div class="h-2 w-2 rounded-full bg-primary-400/40"></div>
                </div>
            </div>
        </div>

        {{-- Right panel: login form --}}
        <div class="flex w-full flex-col justify-center px-6 py-12 lg:w-1/2 lg:px-16">
            <div class="mx-auto w-full max-w-sm">

                {{-- Mobile logo --}}
                <div class="mb-8 lg:hidden">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900">HRIS</span>
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Selamat datang</h2>
                    <p class="mt-1.5 text-sm text-gray-500">Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                            placeholder="Masukkan username"
                        >
                        @error('username')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                            placeholder="Masukkan password"
                        >
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-600">Ingat saya</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-primary-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-primary-600/30 transition-all duration-200 hover:bg-primary-700 hover:shadow-primary-700/30 active:scale-[0.98]"
                    >
                        Masuk
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} HRIS — Internal Use Only
                </p>
            </div>
        </div>
    </div>
</body>
</html>
