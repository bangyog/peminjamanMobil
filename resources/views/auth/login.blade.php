<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />


    {{-- ⚠️ Peringatan akses logout manual --}}
    @if (session('logout_warning'))
    <div id="logout-warning"
        class="flex items-start gap-3 mb-5 px-4 py-3 rounded-lg bg-yellow-400/20 border border-yellow-300/50 backdrop-blur-sm animate-pulse-once">
        <svg class="w-5 h-5 text-yellow-300 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
        </svg>
        <div>
            <p class="text-yellow-200 text-sm font-semibold">Akses Tidak Diizinkan</p>
            <p class="text-yellow-100 text-xs mt-0.5">
                Halaman <code class="bg-yellow-300/20 px-1 rounded">/logout</code>
                tidak dapat diakses langsung melalui URL.<br>
                login dulu <strong></strong> di dalam aplikasi.
            </p>
        </div>
        <button onclick="document.getElementById('logout-warning').remove()"
            class="ml-auto text-yellow-300 hover:text-white transition text-lg leading-none"
            title="Tutup">&times;</button>
    </div>
    @endif


    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-white">Selamat Datang</h2>
        <p class="text-blue-100 text-sm mt-1">Silakan login untuk melanjutkan</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5" id="loginForm">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-white font-semibold" />
            <div class="relative mt-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input id="email"
                    class="block w-full pl-10 pr-3 py-3 bg-white/90 border-white/50 text-gray-800 placeholder-gray-500 focus:border-white focus:ring-white rounded-lg"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="nama@company.com"
                    oninput="validateEmail(this)" />
            </div>
            <!-- Peringatan Email (Client-side) -->
            <p id="email-warning" class="mt-1 text-sm text-yellow-300 hidden flex items-center gap-1">
                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
                <span id="email-warning-text"></span>
            </p>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-yellow-200" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-white font-semibold" />
            <div class="relative mt-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="password"
                    class="block w-full pl-10 pr-12 py-3 bg-white/90 border-white/50 text-gray-800 placeholder-gray-500 focus:border-white focus:ring-white rounded-lg"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••" />

                <!-- Tombol Show/Hide Password -->
                <button type="button"
                    id="togglePassword"
                    onclick="togglePasswordVisibility()"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 transition">
                    <!-- Icon Eye (password tersembunyi) -->
                    <svg id="icon-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Icon Eye-Off (password terlihat) -->
                    <svg id="icon-eye-off" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.563-4.308m3.078-2.614A9.957 9.957 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.024 10.024 0 01-4.132 5.411M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-yellow-200" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me"
                    type="checkbox"
                    class="rounded border-white/50 bg-white/20 text-white shadow-sm focus:ring-white"
                    name="remember">
                <span class="ml-2 text-sm text-white">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
            <a class="text-sm text-blue-100 hover:text-white transition" href="{{ route('password.request') }}">
                {{ __('Lupa password?') }}
            </a>
            @endif
        </div>

        <!-- Login Button -->
        <div>
            <button type="submit" class="w-full flex justify-center items-center px-4 py-3 bg-white hover:bg-gray-50 border border-transparent rounded-lg font-semibold text-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                {{ __('Masuk') }}
            </button>
        </div>
    </form>

    <script>
        // ─── Validasi Email Real-time ───────────────────────────────────────────
        function validateEmail(input) {
            const warning = document.getElementById('email-warning');
            const warningText = document.getElementById('email-warning-text');
            const value = input.value;

            if (value.includes(' ')) {
                warningText.textContent = 'Email tidak boleh mengandung spasi.';
                warning.classList.remove('hidden');
                input.classList.add('border-yellow-400', 'ring-1', 'ring-yellow-400');
                return;
            }

            // Regex validasi format email dasar
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (value.length > 0 && !emailRegex.test(value)) {
                warningText.textContent = 'Format email tidak valid. Contoh: nama@company.com';
                warning.classList.remove('hidden');
                input.classList.add('border-yellow-400', 'ring-1', 'ring-yellow-400');
            } else {
                warning.classList.add('hidden');
                input.classList.remove('border-yellow-400', 'ring-1', 'ring-yellow-400');
            }
        }

        // ─── Toggle Show/Hide Password ──────────────────────────────────────────
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const iconEye = document.getElementById('icon-eye');
            const iconEyeOff = document.getElementById('icon-eye-off');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                iconEye.classList.add('hidden');
                iconEyeOff.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                iconEye.classList.remove('hidden');
                iconEyeOff.classList.add('hidden');
            }
        }

        // ─── Blokir submit jika email masih ada warning ─────────────────────────
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const emailInput = document.getElementById('email');
            if (emailInput.value.includes(' ')) {
                e.preventDefault();
                document.getElementById('email-warning-text').textContent = 'Hapus spasi pada email sebelum login.';
                document.getElementById('email-warning').classList.remove('hidden');
            }
        });
    </script>
</x-guest-layout>