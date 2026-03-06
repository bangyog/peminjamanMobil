<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Edit Unit: {{ $unit->name }}
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

                    <form action="{{ route('admin.units.update', $unit) }}" method="POST" id="unitForm">
                        @csrf
                        @method('PUT')

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
                                           value="{{ old('name', $unit->name) }}"
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
                                        <option value="1" {{ old('is_active', $unit->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>✅ Aktif</option>
                                        <option value="0" {{ old('is_active', $unit->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>❌ Tidak Aktif</option>
                                    </select>
                                </div> -->

                            </div>
                        </div>

                        {{-- SECTION 2: KEPALA DEPARTEMEN --}}
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">
                                👔 Kepala Departemen
                            </h3>

                            {{-- Info kepala saat ini --}}
                            @if($unit->kepalaDepartemen)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-5">
                                <p class="text-sm font-medium text-blue-800 mb-1">Kepala saat ini:</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-200 flex items-center justify-center text-blue-800 font-bold text-sm">
                                        {{ strtoupper(substr($unit->kepalaDepartemen->full_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-blue-900">{{ $unit->kepalaDepartemen->full_name }}</p>
                                        <p class="text-xs text-blue-700">{{ $unit->kepalaDepartemen->email }}</p>
                                    </div>
                                </div>
                            </div>
                            @else
                            {{-- ✅ Cek apakah ini unit GA --}}
                            @php
                                $isGAUnit = $unit->users()->where('role', 'admin_ga')->exists();
                            @endphp
                            @if($isGAUnit)
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-5">
                                <p class="text-sm text-purple-800">
                                    🛡️ Unit ini adalah <strong>Unit Admin GA</strong>. Tidak memerlukan kepala departemen.
                                </p>
                            </div>
                            @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-5">
                                <p class="text-sm text-yellow-800">
                                    ⚠️ Unit ini belum memiliki kepala departemen. Pilih dari daftar di bawah.
                                </p>
                            </div>
                            @endif
                            @endif

                            {{-- Dropdown ganti kepala (hidden jika unit GA) --}}
                            @if(!isset($isGAUnit) || !$isGAUnit)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ganti / Pilih Kepala Departemen
                                </label>
                                <select name="kepala_departemen_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Tidak Ada / Kosongkan Kepala --</option>
                                    @forelse($availableKepala as $kepala)
                                    <option value="{{ $kepala->id }}"
                                        {{ old('kepala_departemen_id', $unit->kepala_departemen_id) == $kepala->id ? 'selected' : '' }}>
                                        {{ $kepala->full_name }} — {{ $kepala->email }}
                                    </option>
                                    @empty
                                    <option disabled>Tidak ada kepala departemen tersedia</option>
                                    @endforelse
                                </select>
                                <p class="text-xs text-gray-500 mt-2">
                                    ℹ️ Hanya menampilkan user role <strong>Kepala Departemen</strong> yang belum menjadi kepala di unit lain.
                                    Untuk buat kepala baru, gunakan menu
                                    <a href="{{ route('admin.users.create') }}" class="text-blue-600 underline">Tambah User</a>.
                                </p>
                            </div>
                            @endif

                        </div>

                        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                            <a href="{{ route('admin.units.index') }}"
                               class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                💾 Update Unit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const unitNameInput   = document.getElementById('unit_name');
        const unitNameError   = document.getElementById('unit-name-error');
        const unitNameSuccess = document.getElementById('unit-name-success');
        let unitNameValid = null;

        unitNameInput.addEventListener('input', debounce(function () {
            const val = this.value.trim();
            // Skip cek jika nilai sama dengan nama unit saat ini
            if (val === "{{ $unit->name }}") {
                unitNameError.classList.add('hidden');
                unitNameSuccess.classList.add('hidden');
                unitNameInput.classList.remove('border-red-500', 'border-green-500');
                unitNameValid = null;
                return;
            }
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
                body: JSON.stringify({ name: val, unit_id: {{ $unit->id }} })
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

        document.getElementById('unitForm').addEventListener('submit', function (e) {
            if (unitNameValid === false) {
                e.preventDefault();
                alert('⚠️ Nama unit sudah digunakan!');
                unitNameInput.focus();
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
