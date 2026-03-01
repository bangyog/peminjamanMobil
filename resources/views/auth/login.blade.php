<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-white">Selamat Datang</h2>
        <p class="text-blue-100 text-sm mt-1">Silakan login untuk melanjutkan</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
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
                    placeholder="nama@company.com" />
            </div>
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
                    class="block w-full pl-10 pr-3 py-3 bg-white/90 border-white/50 text-gray-800 placeholder-gray-500 focus:border-white focus:ring-white rounded-lg"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password"
                    placeholder="••••••••" />
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

    <!-- Demo Credentials (Remove in production)
    <div class="mt-6 p-4 bg-white/10 backdrop-blur-sm rounded-lg border border-white/20">
        <p class="text-xs text-white font-semibold mb-2">🔑 Demo Login:</p>
        <p class="text-xs text-blue-100">Admin: <span class="font-mono text-white">admin.ga@company.com</span></p>
        <p class="text-xs text-blue-100">User: <span class="font-mono text-white">siti@company.com</span></p>
        <p class="text-xs text-blue-100 mt-1">Password: <span class="font-mono text-white">password</span></p>
    </div> -->
</x-guest-layout>
