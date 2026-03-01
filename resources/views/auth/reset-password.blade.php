<x-guest-layout>
    <div class="text-center mb-6">
        <div class="mx-auto w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-white">Reset Password</h2>
        <p class="text-blue-100 text-sm mt-2">Buat password baru untuk akun Anda</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                    value="{{ old('email', $request->email) }}"
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="nama@company.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-yellow-200" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password Baru')" class="text-white font-semibold" />
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
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-yellow-200" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-white font-semibold" />
            <div class="relative mt-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <input id="password_confirmation" 
                    class="block w-full pl-10 pr-3 py-3 bg-white/90 border-white/50 text-gray-800 placeholder-gray-500 focus:border-white focus:ring-white rounded-lg"
                    type="password"
                    name="password_confirmation"
                    required 
                    autocomplete="new-password"
                    placeholder="Ketik ulang password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-yellow-200" />
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full flex justify-center items-center px-4 py-3 bg-white hover:bg-gray-50 border border-transparent rounded-lg font-semibold text-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>

    <!-- Security Tips -->
    <div class="mt-6 p-4 bg-white/10 backdrop-blur-sm rounded-lg border border-white/20">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-yellow-200 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
            <div>
                <p class="text-xs text-white font-semibold">🔒 Password yang aman:</p>
                <ul class="text-xs text-blue-100 mt-1 space-y-1 list-disc list-inside">
                    <li>Minimal 8 karakter</li>
                    <li>Kombinasi huruf besar & kecil</li>
                    <li>Tambahkan angka & simbol</li>
                </ul>
            </div>
        </div>
    </div>
</x-guest-layout>
