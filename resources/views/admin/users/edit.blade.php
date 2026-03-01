<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Edit User: {{ $user->full_name }}
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

                    <form action="{{ route('admin.users.update', $user) }}" method="POST" id="userForm">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- ✅ Nama Lengkap — FIX BUG #3 & #4: tambah id + error/success elements --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="full_name" id="full_name"
                                       value="{{ old('full_name', $user->full_name) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       required>
                                <p id="name-error" class="text-red-600 text-sm mt-1 hidden">⚠️ Nama sudah terdaftar!</p>
                                <p id="name-success" class="text-green-600 text-sm mt-1 hidden">✅ Nama tersedia</p>
                            </div>

                            {{-- ✅ Email --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email"
                                       value="{{ old('email', $user->email) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       required>
                                <p id="email-error" class="text-red-600 text-sm mt-1 hidden">⚠️ Email sudah terdaftar!</p>
                                <p id="email-success" class="text-green-600 text-sm mt-1 hidden">✅ Email tersedia</p>
                            </div>

                            {{-- ✅ Phone --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon (Opsional)
                                </label>
                                <input type="text" name="phone" id="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <p id="phone-error" class="text-red-600 text-sm mt-1 hidden">⚠️ Nomor telepon sudah terdaftar!</p>
                                <p id="phone-success" class="text-green-600 text-sm mt-1 hidden">✅ Nomor telepon tersedia</p>
                            </div>

                            {{-- ✅ Password (opsional saat edit) --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Baru <span class="text-gray-400 text-xs">(Kosongkan jika tidak ingin mengubah)</span>
                                </label>
                                <input type="password" name="password"
                                       placeholder="Minimal 8 karakter"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            {{-- ✅ Konfirmasi Password --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password Baru
                                </label>
                                <input type="password" name="password_confirmation"
                                       placeholder="Ulangi password baru"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            {{-- ✅ FIX BUG #2: Unit — auto-fill jika hanya 1 unit --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Unit <span class="text-red-500">*</span>
                                </label>

                                @if($units->count() === 1)
                                    {{-- Kepala / Admin Akuntansi: unit terkunci ke unit sendiri --}}
                                    <input type="hidden" name="unit_id" value="{{ $units->first()->id }}">
                                    <div class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 cursor-not-allowed">
                                        🏢 {{ $units->first()->name }}
                                        <span class="text-xs text-gray-500 ml-2">(unit Anda)</span>
                                    </div>
                                @else
                                    {{-- Admin GA: bisa pindah unit kepala_departemen --}}
                                    <select name="unit_id"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            required>
                                        <option value="">Pilih Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ old('unit_id', $user->unit_id) == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            {{-- ✅ FIX BUG #1: Role — pakai $availableRoles, bukan hardcode --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Role <span class="text-red-500">*</span>
                                </label>

                                @if(count($availableRoles) === 1)
                                    {{-- Hanya 1 pilihan → tampilkan readonly --}}
                                    <input type="hidden" name="role" value="{{ array_key_first($availableRoles) }}">
                                    <div class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 cursor-not-allowed">
                                        {{ array_values($availableRoles)[0] }}
                                        <span class="text-xs text-gray-500 ml-2">(otomatis)</span>
                                    </div>
                                @else
                                    <select name="role"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            required>
                                        @foreach($availableRoles as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ old('role', $user->role) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            {{-- ✅ Status --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="is_active" value="1"
                                               {{ old('is_active', $user->is_active) == 1 ? 'checked' : '' }}
                                               class="mr-2" required>
                                        <span class="text-sm">✅ Aktif</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="is_active" value="0"
                                               {{ old('is_active', $user->is_active) == 0 ? 'checked' : '' }}
                                               class="mr-2">
                                        <span class="text-sm">❌ Nonaktif</span>
                                    </label>
                                </div>
                            </div>

                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                            <a href="{{ route('admin.users.index') }}"
                               class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                                Batal
                            </a>
                            <button type="submit" id="submitBtn"
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                💾 Update User
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // ✅ FIX BUG #6: Semua deklarasi variabel di atas sebelum dipakai

        // Data asli user (untuk skip validasi jika tidak berubah)
        const originalName  = '{{ addslashes($user->full_name) }}';
        const originalEmail = '{{ addslashes($user->email) }}';
        const originalPhone = '{{ addslashes($user->phone ?? '') }}';

        // ✅ VALIDASI NAMA — FIX BUG #5,#7,#8,#9,#10,#11
        const full_nameInput = document.getElementById('full_name'); // FIX #5
        const nameError      = document.getElementById('name-error');
        const nameSuccess    = document.getElementById('name-success');
        let nameValid        = true; // ✅ FIX BUG #10: true karena nama lama sudah valid

        full_nameInput.addEventListener('input', debounce(function() { // ✅ FIX BUG #7
            const full_name = this.value.trim(); // ✅ FIX BUG #8: definisikan variabel

            if (full_name.length === 0) {
                nameError.classList.add('hidden');
                nameSuccess.classList.add('hidden');
                nameValid = false;
                return;
            }

            // ✅ FIX BUG #11: skip cek jika nama tidak berubah
            if (full_name === originalName) {
                nameError.classList.add('hidden');
                nameSuccess.classList.add('hidden');
                full_nameInput.classList.remove('border-red-500', 'border-green-500');
                nameValid = true;
                return;
            }

            fetch('{{ route("admin.users.check-name") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    full_name: full_name,       // ✅ FIX BUG #8: variabel sudah benar
                    user_id: "{{ $user->id }}"
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.exists) {
                    nameError.classList.remove('hidden');    // ✅ FIX BUG #9: pakai nameError
                    nameSuccess.classList.add('hidden');
                    full_nameInput.classList.add('border-red-500');
                    full_nameInput.classList.remove('border-green-500');
                    nameValid = false;
                } else {
                    nameError.classList.add('hidden');
                    nameSuccess.classList.remove('hidden');  // ✅ FIX BUG #9: pakai nameSuccess
                    full_nameInput.classList.remove('border-red-500');
                    full_nameInput.classList.add('border-green-500');
                    nameValid = true;
                }
            });
        }, 500));

        // ✅ VALIDASI EMAIL
        const emailInput   = document.getElementById('email');
        const emailError   = document.getElementById('email-error');
        const emailSuccess = document.getElementById('email-success');
        let emailValid     = true;

        emailInput.addEventListener('input', debounce(function() {
            const email = this.value.trim();

            if (email.length === 0) {
                emailError.classList.add('hidden');
                emailSuccess.classList.add('hidden');
                emailValid = false;
                return;
            }

            if (email === originalEmail) {
                emailError.classList.add('hidden');
                emailSuccess.classList.add('hidden');
                emailInput.classList.remove('border-red-500', 'border-green-500');
                emailValid = true;
                return;
            }

            fetch('{{ route("admin.users.check-email") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email: email,
                    user_id: "{{ $user->id }}"
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.exists) {
                    emailError.classList.remove('hidden');
                    emailSuccess.classList.add('hidden');
                    emailInput.classList.add('border-red-500');
                    emailInput.classList.remove('border-green-500');
                    emailValid = false;
                } else {
                    emailError.classList.add('hidden');
                    emailSuccess.classList.remove('hidden');
                    emailInput.classList.remove('border-red-500');
                    emailInput.classList.add('border-green-500');
                    emailValid = true;
                }
            });
        }, 500));

        // ✅ VALIDASI PHONE
        const phoneInput   = document.getElementById('phone');
        const phoneError   = document.getElementById('phone-error');
        const phoneSuccess = document.getElementById('phone-success');
        let phoneValid     = true;

        phoneInput.addEventListener('input', debounce(function() {
            const phone = this.value.trim();

            if (phone.length === 0) {
                phoneError.classList.add('hidden');
                phoneSuccess.classList.add('hidden');
                phoneInput.classList.remove('border-red-500', 'border-green-500');
                phoneValid = true;
                return;
            }

            if (phone === originalPhone) {
                phoneError.classList.add('hidden');
                phoneSuccess.classList.add('hidden');
                phoneInput.classList.remove('border-red-500', 'border-green-500');
                phoneValid = true;
                return;
            }

            fetch('{{ route("admin.users.check-phone") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    phone: phone,
                    user_id: "{{ $user->id }}"
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.exists) {
                    phoneError.classList.remove('hidden');
                    phoneSuccess.classList.add('hidden');
                    phoneInput.classList.add('border-red-500');
                    phoneInput.classList.remove('border-green-500');
                    phoneValid = false;
                } else {
                    phoneError.classList.add('hidden');
                    phoneSuccess.classList.remove('hidden');
                    phoneInput.classList.remove('border-red-500');
                    phoneInput.classList.add('border-green-500');
                    phoneValid = true;
                }
            });
        }, 500));

        // ✅ VALIDASI SUBMIT
        document.getElementById('userForm').addEventListener('submit', function(e) {
            if (!nameValid) {
                e.preventDefault();
                alert('⚠️ Nama sudah terdaftar!');
                full_nameInput.focus();
                return false;
            }
            if (!emailValid) {
                e.preventDefault();
                alert('⚠️ Email sudah terdaftar!');
                emailInput.focus();
                return false;
            }
            if (!phoneValid) {
                e.preventDefault();
                alert('⚠️ Nomor telepon sudah terdaftar!');
                phoneInput.focus();
                return false;
            }
        });

        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
    </script>
    @endpush
</x-app-layout>
