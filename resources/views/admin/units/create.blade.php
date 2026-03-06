<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Tambah Unit & Kepala Departemen
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-red-800 mb-2">Terdapat kesalahan:</p>
                                <ul class="list-disc list-inside text-sm text-red-700">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('admin.units.store') }}" method="POST" id="unitForm">
                        @csrf

                        {{-- SECTION 1: INFO UNIT --}}
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">
                                🏢 Informasi Unit
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Unit <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="unit_name"
                                           value="{{ old('name') }}"
                                           placeholder="Contoh: IT, Keuangan, Operasional..."
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                           required>
                                    <p id="unit-name-error"   class="text-red-600 text-sm mt-1 hidden">⚠️ Nama unit sudah digunakan!</p>
                                    <p id="unit-name-success" class="text-green-600 text-sm mt-1 hidden">✅ Nama unit tersedia</p>
                                    @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>

                                <!-- <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Unit</label>
                                    <select name="is_active"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>✅ Aktif</option>
                                        <option value="0" {{ old('is_active') == '0'       ? 'selected' : '' }}>❌ Tidak Aktif</option>
                                    </select>
                                </div> -->

                            </div>
                        </div>

                        {{-- SECTION 2: KEPALA DEPARTEMEN — WAJIB --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b">
                                <h3 class="text-lg font-semibold text-gray-800">👔 Akun Kepala Departemen</h3>
                                <span class="text-xs bg-red-100 text-red-700 font-semibold px-2 py-0.5 rounded-full">Wajib</span>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-5">
                                <p class="text-sm text-blue-700">
                                    ℹ️ Setiap unit harus memiliki kepala departemen. Akun kepala akan dibuat otomatis bersama unit.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="kepala_full_name"
                                           value="{{ old('kepala_full_name') }}"
                                           placeholder="Contoh: Budi Santoso"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('kepala_full_name') border-red-500 @enderror"
                                           required>
                                    @error('kepala_full_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="kepala_email" id="kepala_email"
                                           value="{{ old('kepala_email') }}"
                                           placeholder="Contoh: budi@company.com"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('kepala_email') border-red-500 @enderror"
                                           required>
                                    <p id="kepala-email-error"   class="text-red-600 text-sm mt-1 hidden">⚠️ Email sudah digunakan!</p>
                                    <p id="kepala-email-success" class="text-green-600 text-sm mt-1 hidden">✅ Email tersedia</p>
                                    @error('kepala_email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        No. HP <span class="text-gray-400 text-xs">(Opsional)</span>
                                    </label>
                                    <input type="text" name="kepala_phone"
                                           value="{{ old('kepala_phone') }}"
                                           placeholder="Contoh: 081234567890"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('kepala_phone') border-red-500 @enderror">
                                    @error('kepala_phone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="kepala_password"
                                           placeholder="Min. 8 karakter"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('kepala_password') border-red-500 @enderror"
                                           required>
                                    @error('kepala_password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Konfirmasi Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="kepala_password_confirmation"
                                           placeholder="Ulangi password"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                           required>
                                </div>

                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                            <a href="{{ route('admin.units.index') }}"
                               class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                💾 Buat Unit & Kepala
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Cek nama unit
        const unitNameInput   = document.getElementById('unit_name');
        const unitNameError   = document.getElementById('unit-name-error');
        const unitNameSuccess = document.getElementById('unit-name-success');
        let unitNameValid = null;

        unitNameInput.addEventListener('input', debounce(function () {
            const val = this.value.trim();
            if (!val) {
                unitNameError.classList.add('hidden');
                unitNameSuccess.classList.add('hidden');
                unitNameInput.classList.remove('border-red-500', 'border-green-500');
                unitNameValid = null;
                return;
            }
            fetch("{{ route('admin.units.check-name') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ name: val })
            })
            .then(r => r.json())
            .then(data => {
                unitNameValid = !data.exists;
                unitNameError.classList.toggle('hidden', !data.exists);
                unitNameSuccess.classList.toggle('hidden', data.exists);
                unitNameInput.classList.toggle('border-red-500', data.exists);
                unitNameInput.classList.toggle('border-green-500', !data.exists);
            });
        }, 500));

        // Cek email kepala
        const kepalaEmailInput   = document.getElementById('kepala_email');
        const kepalaEmailError   = document.getElementById('kepala-email-error');
        const kepalaEmailSuccess = document.getElementById('kepala-email-success');
        let kepalaEmailValid = null;

        kepalaEmailInput.addEventListener('input', debounce(function () {
            const val = this.value.trim();
            if (!val) {
                kepalaEmailError.classList.add('hidden');
                kepalaEmailSuccess.classList.add('hidden');
                kepalaEmailInput.classList.remove('border-red-500', 'border-green-500');
                kepalaEmailValid = null;
                return;
            }
            fetch("{{ route('admin.users.check-email') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ email: val })
            })
            .then(r => r.json())
            .then(data => {
                kepalaEmailValid = !data.exists;
                kepalaEmailError.classList.toggle('hidden', !data.exists);
                kepalaEmailSuccess.classList.toggle('hidden', data.exists);
                kepalaEmailInput.classList.toggle('border-red-500', data.exists);
                kepalaEmailInput.classList.toggle('border-green-500', !data.exists);
            });
        }, 500));

        // Block submit jika ada yang duplikat
        document.getElementById('unitForm').addEventListener('submit', function (e) {
            if (unitNameValid === false) {
                e.preventDefault();
                alert('⚠️ Nama unit sudah digunakan!');
                unitNameInput.focus();
                return;
            }
            if (kepalaEmailValid === false) {
                e.preventDefault();
                alert('⚠️ Email kepala sudah digunakan!');
                kepalaEmailInput.focus();
            }
        });

        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
    </script>
    @endpush
</x-app-layout>
