<x-guest-layout>
    <div class="text-center mb-6">
        <div class="mx-auto w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-white">Lupa Password?</h2>
        <p class="text-blue-100 text-sm mt-2 leading-relaxed">
            Tidak masalah! Masukkan email Anda dan kami akan mengirimkan link untuk reset password.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-4 bg-green-500/20 border border-green-300 rounded-lg">
            <p class="text-sm text-white font-medium">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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
                    placeholder="nama@company.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-yellow-200" />
        </div>

        <!-- Buttons -->
        <div class="space-y-3">
            <button type="submit" class="w-full flex justify-center items-center px-4 py-3 bg-white hover:bg-gray-50 border border-transparent rounded-lg font-semibold text-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                {{ __('Kirim Link Reset Password') }}
            </button>

            <a href="{{ route('login') }}" class="w-full flex justify-center items-center px-4 py-3 bg-white/10 hover:bg-white/20 border border-white/30 rounded-lg font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Kembali ke Login') }}
            </a>
        </div>
    </form>

    <!-- Info Box -->
    <div class="mt-6 p-4 bg-white/10 backdrop-blur-sm rounded-lg border border-white/20">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-200 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            <div>
                <p class="text-xs text-white font-semibold">💡 Tips:</p>
                <p class="text-xs text-blue-100 mt-1 leading-relaxed">
                    Periksa folder spam jika email tidak masuk dalam 5 menit. Link reset berlaku selama 60 menit.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
