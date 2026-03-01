<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Tambah Kendaraan Baru
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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

                    <form action="{{ route('admin.vehicles.store') }}" method="POST" id="vehicleForm">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- ✅ unit_code ADA di DB --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kode Unit
                                </label>
                                <input type="text" name="unit_code" id="unit_code" value="{{ old('unit_code') }}"
                                       placeholder="Contoh: GA-001"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('unit_code') border-red-500 @enderror">
                                <p id="unit-code-error"   class="text-red-600 text-sm mt-1 hidden">⚠️ Kode unit sudah digunakan!</p>
                                <p id="unit-code-success" class="text-green-600 text-sm mt-1 hidden">✅ Kode unit tersedia</p>
                                @error('unit_code')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Merek -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Merek <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="brand" value="{{ old('brand') }}"
                                       placeholder="Contoh: Toyota"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('brand') border-red-500 @enderror"
                                       required>
                                @error('brand')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Model -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Model <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="model" value="{{ old('model') }}"
                                       placeholder="Contoh: Avanza"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('model') border-red-500 @enderror"
                                       required>
                                @error('model')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Nomor Polisi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Polisi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="plate_no" id="plate_no" value="{{ old('plate_no') }}"
                                       placeholder="Contoh: L 1234 AB"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('plate_no') border-red-500 @enderror"
                                       required>
                                <p id="plate-no-error"   class="text-red-600 text-sm mt-1 hidden">⚠️ Nomor polisi sudah terdaftar!</p>
                                <p id="plate-no-success" class="text-green-600 text-sm mt-1 hidden">✅ Nomor polisi tersedia</p>
                                @error('plate_no')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Kapasitas -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kapasitas Penumpang <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="seat_capacity" value="{{ old('seat_capacity') }}"
                                       placeholder="Contoh: 7" min="1" max="50"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('seat_capacity') border-red-500 @enderror"
                                       required>
                                @error('seat_capacity')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Odometer -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Odometer (KM) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="odometer_km" value="{{ old('odometer_km', 0) }}"
                                       placeholder="Contoh: 15000" min="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('odometer_km') border-red-500 @enderror"
                                       required>
                                @error('odometer_km')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Status -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                {{-- ✅ ENUM DB: available, in_use, maintenance, retired --}}
                                <select name="status"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        required>
                                    <option value="available"   {{ old('status', 'available') === 'available'   ? 'selected' : '' }}>✅ Tersedia</option>
                                    <option value="in_use"      {{ old('status') === 'in_use'      ? 'selected' : '' }}>🚗 Sedang Digunakan</option>
                                    <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>🔧 Maintenance</option>
                                    <option value="retired"     {{ old('status') === 'retired'     ? 'selected' : '' }}>⛔ Tidak Aktif</option>
                                </select>
                            </div>

                            <!-- Catatan -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                <textarea name="notes" rows="3"
                                          placeholder="Catatan tambahan tentang kendaraan..."
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                            </div>

                        </div>

                        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                            <a href="{{ route('admin.vehicles.index') }}"
                               class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                                Batal
                            </a>
                            <button type="submit" id="submitBtn"
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                💾 Simpan Kendaraan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // ✅ Real-time cek unit_code
        const unitCodeInput   = document.getElementById('unit_code');
        const unitCodeError   = document.getElementById('unit-code-error');
        const unitCodeSuccess = document.getElementById('unit-code-success');
        let unitCodeValid = null;

        unitCodeInput.addEventListener('input', debounce(function () {
            const val = this.value.trim();
            if (!val) {
                unitCodeError.classList.add('hidden');
                unitCodeSuccess.classList.add('hidden');
                unitCodeInput.classList.remove('border-red-500', 'border-green-500');
                unitCodeValid = null;
                return;
            }
            fetch("{{ route('admin.vehicles.check-plate-no') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ unit_code: val })
            })
            .then(r => r.json())
            .then(data => {
                unitCodeValid = !data.exists;
                unitCodeError.classList.toggle('hidden', !data.exists);
                unitCodeSuccess.classList.toggle('hidden', data.exists);
                unitCodeInput.classList.toggle('border-red-500', data.exists);
                unitCodeInput.classList.toggle('border-green-500', !data.exists);
            });
        }, 500));

        // ✅ Real-time cek plate_no
        const plateNoInput   = document.getElementById('plate_no');
        const plateNoError   = document.getElementById('plate-no-error');
        const plateNoSuccess = document.getElementById('plate-no-success');
        let plateNoValid = null;

        plateNoInput.addEventListener('input', debounce(function () {
            const val = this.value.trim();
            if (!val) {
                plateNoError.classList.add('hidden');
                plateNoSuccess.classList.add('hidden');
                plateNoInput.classList.remove('border-red-500', 'border-green-500');
                plateNoValid = null;
                return;
            }
            fetch("{{ route('admin.vehicles.check-plate-no') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ plate_no: val })
            })
            .then(r => r.json())
            .then(data => {
                plateNoValid = !data.exists;
                plateNoError.classList.toggle('hidden', !data.exists);
                plateNoSuccess.classList.toggle('hidden', data.exists);
                plateNoInput.classList.toggle('border-red-500', data.exists);
                plateNoInput.classList.toggle('border-green-500', !data.exists);
            });
        }, 500));

        // ✅ Block submit jika duplikat
        document.getElementById('vehicleForm').addEventListener('submit', function (e) {
            if (unitCodeValid === false) {
                e.preventDefault();
                alert('⚠️ Kode unit sudah digunakan!');
                unitCodeInput.focus();
                return;
            }
            if (plateNoValid === false) {
                e.preventDefault();
                alert('⚠️ Nomor polisi sudah terdaftar!');
                plateNoInput.focus();
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
