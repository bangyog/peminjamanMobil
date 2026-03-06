<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajukan Peminjaman Kendaraan
        </h2>
    </x-slot>

    {{-- ✅ FIX BUG 5 & 6: Hapus py-12, max-w, dan flash messages --}}

    @if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <strong class="flex items-center font-bold mb-2">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            Terdapat kesalahan input:
        </strong>
        <ul class="list-disc list-inside ml-7 space-y-1">
            @foreach ($errors->all() as $error)
            <li class="text-sm">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="max-w-5xl mx-auto">
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
            <div class="p-8">

                <!-- Header Formulir -->
                <div class="text-center mb-8 border-b-2 border-blue-600 pb-6">
                    <div class="flex items-center justify-center mb-3">
                        <svg class="w-12 h-12 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="text-3xl font-bold text-gray-800">PT. SWABINA GATRA</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-1">Sistem Kendaraan Dinas</p>
                    <h4 class="text-xl font-semibold text-gray-700 mt-3">FORMULIR PERMINTAAN PEMINJAMAN KENDARAAN DINAS</h4>
                    <p class="text-sm text-gray-600 mt-3 bg-blue-50 inline-block px-4 py-2 rounded-full">
                        📅 Surabaya, {{ now()->translatedFormat('d F Y') }}
                    </p>
                </div>

                <form action="{{ route('loan-requests.store') }}" method="POST" enctype="multipart/form-data" id="loanForm">
                    @csrf

                    <!-- Informasi Pemohon -->
                    <div class="border-2 border-gray-200 rounded-xl p-6 mb-6 hover:border-blue-300 transition">
                        <h5 class="font-bold text-lg mb-4 flex items-center text-blue-700">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Pemohon
                        </h5>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Unit -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Unit <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    {{-- Display only — disabled tidak ikut submit --}}
                                    <input type="text"
                                        value="{{ auth()->user()->unit->name ?? '-' }}"
                                        disabled
                                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg text-gray-700 font-medium">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>

                                {{-- ✅ INI yang penting! Yang disabled tidak ikut submit --}}
                                <input type="hidden" name="unit_id" value="{{ auth()->user()->unit_id }}">

                                <p class="mt-1 text-xs text-gray-500">Otomatis dari profil Anda</p>
                            </div>


                            <!-- Nama Pemakai -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pemakai</label>
                                <div class="relative">
                                    {{-- ✅ FIX BUG 7: hapus fallback ->name --}}
                                    <input type="text" value="{{ auth()->user()->full_name }}" disabled
                                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg text-gray-700 font-medium">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Otomatis dari akun Anda</p>
                            </div>
                        </div>

                        <!-- Keperluan -->
                        <div class="mt-6">
                            <label for="purpose" class="block text-sm font-semibold text-gray-700 mb-2">
                                Keperluan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="purpose" id="purpose" rows="3" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('purpose') border-red-400 @enderror"
                                placeholder="Contoh: Menghadiri rapat koordinasi dengan klien di Jakarta">{{ old('purpose') }}</textarea>
                            @error('purpose')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- projek -->
                        <div class="mt-6">
                            <label for="projek" class="block text-sm font-semibold text-gray-700 mb-2">
                                Projek <span class="text-gray-500 font-normal text-xs"></span>
                            </label>
                            <input type="text" name="projek" id="projek"
                                value="{{ old('projek') }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Contoh: Proyek Pengembangan Aplikasi XYZ">
                        </div>

                        <!-- Tujuan & Kota -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="destination" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tujuan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="destination" id="destination" required
                                    value="{{ old('destination') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('destination') border-red-400 @enderror"
                                    placeholder="Contoh: Surabaya - Jakarta">
                                @error('destination')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="request_city" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kota Pengajuan
                                </label>
                                <input type="text" name="request_city" id="request_city"
                                    value="{{ old('request_city', 'Surabaya') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="Contoh: Surabaya">
                            </div>
                        </div>

                        <!-- anggaran awal
                        <div class="mt-6">
                            <label for="anggaran_awal" class="block text-sm font-semibold text-gray-700 mb-2">
                                Anggaran Awal <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="anggaran_awal" id="anggaran_awal" required
                                value="{{ old('anggaran_awal') }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('anggaran_awal') border-red-400 @enderror"
                                placeholder="Contoh: 1500000">
                            @error('anggaran_awal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div> -->

                        <!-- driver -->
                        <div class="mt-6">
                            <label for="driver" class="inline-flex items-center gap-4 cursor-pointer group">
                                <!-- Toggle Switch -->
                                <div class="relative">
                                    <input type="checkbox" name="driver" id="driver" value="1"
                                        {{ old('driver') ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="w-12 h-6 bg-gray-200 rounded-full peer 
                        peer-checked:bg-blue-600 
                        peer-focus:ring-2 peer-focus:ring-blue-400 peer-focus:ring-offset-2
                        transition-colors duration-300 ease-in-out
                        group-hover:bg-gray-300 peer-checked:group-hover:bg-blue-700">
                                    </div>
                                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md
                        peer-checked:translate-x-6
                        transition-transform duration-300 ease-in-out">
                                    </div>
                                </div>

                                <!-- Label & Description -->
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-700 group-hover:text-blue-600 transition-colors duration-200">
                                        Butuh Driver?
                                    </span>
                                    <span class="text-xs text-gray-400">Aktifkan jika membutuhkan driver</span>
                                </div>
                            </label>
                        </div>

                        <!-- Kendaraan -->
                        <div class="mt-6">
                            <label for="preferred_vehicle_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Kendaraan yang Diminta
                                <span class="text-gray-500 font-normal text-xs">(Opsional)</span>
                            </label>
                            <select name="preferred_vehicle_id" id="preferred_vehicle_id"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">-- Tidak ada preferensi --</option>
                                {{-- ✅ FIX BUG 8: $vehicles hanya yang available (dari controller) --}}
                                @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('preferred_vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    🚗 {{ $vehicle->brand }} {{ $vehicle->model }}
                                    @if($vehicle->plate_no) - {{ $vehicle->plate_no }} @endif
                                    @if($vehicle->capacity) ({{ $vehicle->capacity }} seats) @endif
                                </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                Hanya menampilkan kendaraan yang tersedia. Admin GA akan konfirmasi kendaraan final.
                            </p>
                        </div>
                    </div>

                    <!-- Jadwal Perjalanan -->
                    <div class="border-2 border-gray-200 rounded-xl p-6 mb-6 hover:border-green-300 transition">
                        <h5 class="font-bold text-lg mb-4 flex items-center text-green-700">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Jadwal Perjalanan
                        </h5>

                        <!-- BERANGKAT -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-5 rounded-xl mb-4 border border-blue-200">
                            <h6 class="font-bold text-blue-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                                Keberangkatan
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="siap_di" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Siap di <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="siap_di" id="siap_di" required
                                        value="{{ old('siap_di', 'Kantor Pusat') }}"
                                        class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white transition"
                                        placeholder="Contoh: Kantor Pusat">
                                </div>
                                <div>
                                    <label for="depart_at" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Tanggal & Jam <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" name="depart_at" id="depart_at" required
                                        value="{{ old('depart_at') }}"
                                        min="{{ now()->addDay()->format('Y-m-d') }}T00:00"
                                        class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white transition @error('depart_at') border-red-400 @enderror">
                                    @error('depart_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- KEMBALI -->
                        <div class="bg-gradient-to-r from-green-50 to-green-100 p-5 rounded-xl border border-green-200">
                            <h6 class="font-bold text-green-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kepulangan
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="kembali_di" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Kembali di <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="kembali_di" id="kembali_di" required
                                        value="{{ old('kembali_di', 'Kantor Pusat') }}"
                                        class="w-full px-4 py-3 border-2 border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white transition"
                                        placeholder="Contoh: Kantor Pusat">
                                </div>
                                <div>
                                    <label for="expected_return_at" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Tanggal & Jam <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" name="expected_return_at" id="expected_return_at" required
                                        value="{{ old('expected_return_at') }}"
                                        min="{{ now()->addDay()->format('Y-m-d') }}T00:00"
                                        class="w-full px-4 py-3 border-2 border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white transition @error('expected_return_at') border-red-400 @enderror">
                                    @error('expected_return_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Catatan -->
                    <div class="border-2 border-gray-200 rounded-xl p-6 mb-6 hover:border-yellow-300 transition">
                        <h5 class="font-bold text-lg mb-4 flex items-center text-yellow-700">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Catatan Tambahan
                        </h5>
                        <textarea name="notes" id="notes" rows="3"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                            placeholder="Catatan tambahan atau kebutuhan khusus... (opsional)">{{ old('notes') }}</textarea>
                    </div>

                    <!-- lampiran file  -->

                    {{-- ===================== TAMBAH LAMPIRAN BARU ===================== --}}
                    <div class="border-2 border-gray-200 rounded-xl p-6 mb-6 hover:border-gray-400 transition">
                        <h5 class="font-bold text-lg mb-4 flex items-center text-gray-700">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Lampiran Baru
                            <span class="text-gray-500 font-normal text-sm ml-2">(Opsional)</span>
                        </h5>
                        <input type="file" name="attachments[]" id="attachments"
                            multiple
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                            class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 transition cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <p class="mt-2 text-xs text-gray-500">
                            Format: PDF, JPG, PNG, DOC, DOCX · Maks. 5MB per file
                        </p>
                        @error('attachments.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- TANDA TANGAN -->
                    <div class="border-2 border-gray-200 rounded-xl p-6 mb-6 hover:border-purple-300 transition">
                        <h5 class="font-bold text-lg mb-4 flex items-center text-purple-700">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Tanda Tangan Pemohon <span class="text-red-500">*</span>
                        </h5>

                        <div class="bg-gray-50 rounded-xl p-4 border-2 border-dashed border-gray-300">
                            <p class="text-sm text-gray-600 mb-3">
                                Silakan bubuhkan tanda tangan Anda di area di bawah ini
                            </p>
                            <div class="bg-white border-4 border-gray-400 rounded-lg overflow-hidden mb-3" id="signatureWrapper">
                                <canvas id="signatureCanvas" width="600" height="200"
                                    style="display: block; width: 100%; height: 200px; touch-action: none; cursor: crosshair;"></canvas>
                            </div>

                            {{-- ✅ FIX BUG 3: requester_signature bukan signature --}}
                            <input type="hidden" name="requester_signature" id="signatureInput">

                            <div class="flex justify-between items-center">
                                <button type="button" id="clearSignature"
                                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Tanda Tangan
                                </button>
                                <span id="signatureStatus" class="text-sm text-gray-500 italic">Belum ada tanda tangan</span>
                            </div>
                        </div>

                        <p id="signatureError" class="mt-2 text-sm text-red-600 hidden">
                            ⚠️ Tanda tangan wajib diisi sebelum submit
                        </p>
                    </div>

                    <!-- Info Footer -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-5 mb-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-semibold text-blue-800 mb-1">Catatan Penting:</p>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• Pengajuan minimal <strong>1 hari sebelum</strong> tanggal keberangkatan</li>
                                    <li>• Approval oleh Kepala Departemen kemudian Admin GA</li>
                                    <li>• Pastikan semua data sudah benar sebelum submit</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t-2 border-gray-200">
                        <a href="{{ route('loan-requests.index') }}"
                            class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>
                        <button type="submit" id="submitBtn"
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Submit Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==================== SIGNATURE PAD ====================
            const canvas = document.getElementById('signatureCanvas');
            const signatureInput = document.getElementById('signatureInput');
            const clearBtn = document.getElementById('clearSignature');
            const signatureStatus = document.getElementById('signatureStatus');
            const signatureError = document.getElementById('signatureError');

            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)',
                minWidth: 1,
                maxWidth: 3,
                velocityFilterWeight: 0.7
            });

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const rect = canvas.getBoundingClientRect();
                const data = signaturePad.toData();

                canvas.width = rect.width * ratio;
                canvas.height = rect.height * ratio;
                canvas.style.width = rect.width + 'px';
                canvas.style.height = rect.height + 'px';
                canvas.getContext('2d').scale(ratio, ratio);

                signaturePad.clear();
                if (data && data.length > 0) signaturePad.fromData(data);
            }

            setTimeout(resizeCanvas, 100);
            window.addEventListener('resize', resizeCanvas);

            function updateSignatureStatus() {
                if (!signaturePad.isEmpty()) {
                    signatureStatus.textContent = '✅ Tanda tangan sudah dibuat';
                    signatureStatus.className = 'text-sm text-green-600 font-semibold';
                    signatureInput.value = signaturePad.toDataURL('image/png');
                    signatureError.classList.add('hidden');
                } else {
                    signatureStatus.textContent = 'Belum ada tanda tangan';
                    signatureStatus.className = 'text-sm text-gray-500 italic';
                    signatureInput.value = '';
                }
            }

            signaturePad.addEventListener('endStroke', updateSignatureStatus);

            clearBtn.addEventListener('click', function() {
                signaturePad.clear();
                updateSignatureStatus();
            });

            // ==================== AUTO-FILL TANGGAL KEMBALI ====================

            const departureDate = document.getElementById('departure_date');
            const returnDate = document.getElementById('return_date');

            if (departureDate) {
                departureDate.addEventListener('change', function() {
                    const d = new Date(this.value);
                    if (!isNaN(d)) {
                        const next = new Date(d.getTime() + (24 * 60 * 60 * 1000));
                        const offset = next.getTimezoneOffset() * 60000;
                        returnDate.value = new Date(next - offset).toISOString().slice(0, 16);
                    }
                });
            }

            // ==================== FORM VALIDATION & SUBMIT ====================
            const form = document.getElementById('loanForm');
            const submitBtn = document.getElementById('submitBtn');
            let isSubmitting = false;

            form.addEventListener('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }

                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    signatureError.classList.remove('hidden');
                    canvas.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    const wrapper = document.getElementById('signatureWrapper');
                    wrapper.style.animation = 'shake 0.5s';
                    setTimeout(() => wrapper.style.animation = '', 500);
                    return false;
                }

                // Final save
                signatureInput.value = signaturePad.toDataURL('image/png');

                isSubmitting = true;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin w-5 h-5 mr-2 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Mengirim...
                `;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            });
        });

        // Validasi tambahan: expected_return_at tidak boleh sebelum depart_at
        document.getElementById('depart_at').addEventListener('change', function() {
            const departVal = this.value;
            const returnInput = document.getElementById('expected_return_at');
            if (departVal) {
                returnInput.min = departVal;
                // Reset nilai return jika lebih awal dari keberangkatan
                if (returnInput.value && returnInput.value < departVal) {
                    returnInput.value = '';
                }
            }
        });
    </script>

    <style>
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-5px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(5px);
            }
        }

        #signatureCanvas {
            display: block !important;
            touch-action: none !important;
            user-select: none !important;
        }
    </style>
</x-app-layout>