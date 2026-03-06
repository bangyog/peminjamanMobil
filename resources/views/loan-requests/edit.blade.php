<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Pengajuan #{{ $loanRequest->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Success/Error Messages --}}
            @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <strong class="flex items-center font-bold">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Terdapat kesalahan:
                </strong>
                <ul class="list-disc list-inside mt-2 ml-7">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- ✅ FIX BUG 6: Teks status sesuai ENUM = 'submitted' --}}
            <div class="bg-amber-50 border-2 border-amber-300 rounded-xl p-5 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-amber-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-bold text-amber-800">Catatan Penting</h3>
                        {{-- ✅ 'submitted' bukan 'Pending' --}}
                        <p class="text-sm text-amber-700 mt-1">
                            Hanya pengajuan dengan status <strong>Menunggu Persetujuan (submitted)</strong>
                            yang bisa diedit. Setelah disetujui, pengajuan tidak dapat diubah.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">

                    {{-- Header Formulir --}}
                    <div class="text-center mb-8 border-b-2 border-amber-600 pb-6">
                        <div class="flex items-center justify-center mb-3">
                            <svg class="w-10 h-10 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <h3 class="text-3xl font-bold text-gray-800">PT. SWABINA GATRA</h3>
                        </div>
                        <p class="text-sm text-gray-600 mb-1">Sistem Kendaraan Dinas</p>
                        <h4 class="text-xl font-semibold text-amber-700 mt-3">EDIT PERMINTAAN PEMINJAMAN KENDARAAN DINAS</h4>
                        <div class="mt-4 flex items-center justify-center space-x-6 text-sm flex-wrap gap-y-2">
                            <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-medium">
                                📋 ID: #{{ $loanRequest->id }}
                            </span>
                            <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full">
                                📅 Diajukan: {{ $loanRequest->created_at->translatedFormat('d F Y, H:i') }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('loan-requests.update', $loanRequest) }}" method="POST"
                        enctype="multipart/form-data" id="editLoanForm">
                        @csrf
                        @method('PUT')

                        {{-- ===================== INFORMASI PEMOHON ===================== --}}
                        <div class="border-2 border-gray-200 rounded-xl p-6 mb-6 hover:border-blue-300 transition">
                            <h5 class="font-bold text-lg mb-4 flex items-center text-blue-700">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Informasi Pemohon
                            </h5>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Unit --}}
                                <div>
                                    <label for="unit_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Unit <span class="text-red-500">*</span>
                                    </label>
                                    <select name="unit_id" id="unit_id" required
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        <option value="">-- Pilih Unit --</option>
                                        @foreach($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unit_id', $loanRequest->unit_id) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Nama Pemakai (read-only) --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Pemakai
                                    </label>
                                    {{-- ✅ FIX BUG 1: users tidak punya kolom 'name', hanya 'full_name' --}}
                                    <input type="text"
                                        value="{{ $loanRequest->requester->full_name ?? '-' }}"
                                        disabled
                                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg text-gray-700 font-medium">
                                    <p class="mt-1 text-xs text-gray-500">Pemohon tidak dapat diubah</p>
                                </div>
                            </div>

                            {{-- Keperluan --}}
                            <div class="mt-6">
                                <label for="purpose" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Keperluan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="purpose" id="purpose" rows="3" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="Contoh: Menghadiri rapat koordinasi dengan klien">{{ old('purpose', $loanRequest->purpose) }}</textarea>
                                @error('purpose')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tujuan --}}
                            <div class="mt-6">
                                <label for="destination" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tujuan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="destination" id="destination" required
                                    value="{{ old('destination', $loanRequest->destination) }}"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="Contoh: Surabaya - Jakarta">
                                @error('destination')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- projek -->
                            <div class="mt-6">
                                <label for="projek" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Projek
                                    <span class="text-gray-500 font-normal text-xs">(Opsional)</span>
                                </label>
                                <input type="text" name="projek" id="projek"
                                    value="{{ old('projek', $loanRequest->projek) }}"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="Contoh: Proyek Pengembangan Aplikasi Mobile">
                                @error('projek')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ✅ FIX BUG 2: Tambah field request_city — ADA di DB & controller --}}
                            <div class="mt-6">
                                <label for="request_city" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kota Pengajuan
                                    <span class="text-gray-500 font-normal text-xs">(Opsional)</span>
                                </label>
                                <input type="text" name="request_city" id="request_city"
                                    value="{{ old('request_city', $loanRequest->request_city) }}"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="Contoh: Surabaya, Gresik, Tuban"
                                    maxlength="100">
                                @error('request_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- anggaran awal
                            <div class="mt-6">
                                <label for="anggaran_awal" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Anggaran Awal <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="anggaran_awal" id="anggaran_awal" required
                                    value="{{ old('anggaran_awal', $loanRequest->anggaran_awal) }}"
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

                            <!-- {{-- Kendaraan yang Diminta --}} -->
                            <div class="mt-6">
                                <label for="preferred_vehicle_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Preferensi Kendaraan
                                    <span class="text-gray-500 font-normal text-xs">(Opsional)</span>
                                </label>
                                <select name="preferred_vehicle_id" id="preferred_vehicle_id"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="">-- Tidak ada preferensi --</option>
                                    @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}"
                                        {{ old('preferred_vehicle_id', $loanRequest->preferred_vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                        🚗 {{ $vehicle->brand }} {{ $vehicle->model }}
                                        @if($vehicle->plate_no) - {{ $vehicle->plate_no }} @endif
                                        {{-- ✅ vehicles.seat_capacity — BUKAN capacity --}}
                                        @if($vehicle->seat_capacity) ({{ $vehicle->seat_capacity }} kursi) @endif
                                    </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Admin GA akan menentukan kendaraan jika tidak ada preferensi</p>
                            </div>
                        </div>

                        {{-- ===================== JADWAL PERJALANAN ===================== --}}
                        <div class="border-2 border-gray-200 rounded-xl p-6 mb-6 hover:border-green-300 transition">
                            <h5 class="font-bold text-lg mb-4 flex items-center text-green-700">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Jadwal Perjalanan
                            </h5>

                            {{-- BERANGKAT --}}
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
                                        {{-- ✅ loan_requests.siap_di (varchar) --}}
                                        <input type="text" name="siap_di" id="siap_di" required
                                            value="{{ old('siap_di', $loanRequest->siap_di) }}"
                                            class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white transition"
                                            placeholder="Contoh: Kantor Pusat">
                                        @error('siap_di')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="depart_at" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Tanggal & Jam <span class="text-red-500">*</span>
                                        </label>
                                        {{-- ✅ FIX BUG 5: Hapus min="{{ now() }}" pada edit --}}
                                        {{-- ✅ loan_requests.depart_at (timestamp) --}}
                                        <input type="datetime-local" name="depart_at" id="depart_at" required
                                            value="{{ old('depart_at', $loanRequest->depart_at?->format('Y-m-d\TH:i')) }}"
                                            class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white transition">
                                        @error('depart_at')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- KEMBALI --}}
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
                                        {{-- ✅ loan_requests.kembali_di (varchar) --}}
                                        <input type="text" name="kembali_di" id="kembali_di" required
                                            value="{{ old('kembali_di', $loanRequest->kembali_di) }}"
                                            class="w-full px-4 py-3 border-2 border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white transition"
                                            placeholder="Contoh: Kantor Pusat">
                                        @error('kembali_di')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="expected_return_at" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Tanggal & Jam <span class="text-red-500">*</span>
                                        </label>
                                        {{-- ✅ loan_requests.expected_return_at (timestamp) --}}
                                        <input type="datetime-local" name="expected_return_at" id="expected_return_at" required
                                            value="{{ old('expected_return_at', $loanRequest->expected_return_at?->format('Y-m-d\TH:i')) }}"
                                            class="w-full px-4 py-3 border-2 border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white transition">
                                        @error('expected_return_at')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===================== CATATAN ===================== --}}
                        <div class="border-2 border-gray-200 rounded-xl p-6 mb-6 hover:border-yellow-300 transition">
                            <h5 class="font-bold text-lg mb-4 flex items-center text-yellow-700">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Catatan Tambahan
                            </h5>
                            {{-- ✅ loan_requests.notes --}}
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                                placeholder="Catatan tambahan atau informasi penting lainnya... (opsional)"
                                maxlength="1000">{{ old('notes', $loanRequest->notes) }}</textarea>
                            @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ===================== TANDA TANGAN (read-only) ===================== --}}
                        {{-- ✅ loan_requests.requester_signature --}}
                        @if($loanRequest->requester_signature)
                        <div class="border-2 border-gray-200 rounded-xl p-6 mb-6 bg-gray-50">
                            <h5 class="font-bold text-lg mb-4 flex items-center text-purple-700">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tanda Tangan Pemohon
                            </h5>
                            <div class="bg-white border-2 border-gray-300 rounded-lg p-4 flex items-center justify-center min-h-[120px]">
                                {{-- ✅ FIX BUG 4: Gunakan asset('storage/') bukan Storage::url() --}}
                                <img src="{{ asset('storage/' . $loanRequest->requester_signature) }}"
                                    alt="Tanda Tangan Pemohon"
                                    class="max-h-40 max-w-full object-contain"
                                    onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                                <p style="display:none;" class="text-xs text-red-400 italic">TTD tidak dapat dimuat</p>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                Tanda tangan tidak dapat diubah setelah pengajuan dibuat
                            </p>
                        </div>
                        @endif

                        {{-- ===================== LAMPIRAN EXISTING ===================== --}}
                        {{-- ✅ loan_request_attachments: file_name, file_url, file_size_bytes, uploaded_at --}}
                        @if($loanRequest->attachments && $loanRequest->attachments->count() > 0)
                        <div class="border-2 border-gray-200 rounded-xl p-6 mb-6">
                            <h5 class="font-bold text-lg mb-4 flex items-center text-gray-700">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                Lampiran Existing
                            </h5>
                            <div class="space-y-2">
                                @foreach($loanRequest->attachments as $attachment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <div class="flex items-center space-x-3 min-w-0">
                                        <svg class="w-8 h-8 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <div class="min-w-0">
                                            {{-- ✅ file_name --}}
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $attachment->file_name ?? 'File Lampiran' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{-- ✅ file_size_bytes — BUKAN file_size! --}}
                                                @if($attachment->file_size_bytes)
                                                {{ number_format($attachment->file_size_bytes / 1024, 1) }} KB ·
                                                @endif
                                                {{-- ✅ uploaded_at — BUKAN created_at! --}}
                                                {{ $attachment->uploaded_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2 flex-shrink-0">
                                        {{-- Preview/Download --}}
                                        <a href="{{ asset('storage/' . $attachment->file_url) }}"
                                            target="_blank"
                                            class="text-blue-600 hover:text-blue-800 text-xs font-medium px-2 py-1 rounded hover:bg-blue-50 transition">
                                            Lihat
                                        </a>
                                        {{-- Hapus lampiran --}}
                                        <form action="{{ route('loan-requests.attachment.delete', $attachment) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus lampiran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 text-xs font-medium px-2 py-1 rounded hover:bg-red-50 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

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

                        {{-- Info Footer --}}
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-5 mb-6">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-blue-800 mb-1">Catatan Edit:</p>
                                    <ul class="text-sm text-blue-700 space-y-1">
                                        <li>• Pastikan semua perubahan sudah benar sebelum menyimpan</li>
                                        <li>• Tanda tangan tidak dapat diubah</li>
                                        <li>• Lampiran lama bisa dihapus satu per satu di atas</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="flex justify-end space-x-4 pt-6 border-t-2 border-gray-200">
                            <a href="{{ route('loan-requests.index', $loanRequest) }}"
                                class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batal
                            </a>
                            <button type="submit" id="submitBtn"
                                class="px-8 py-3 bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const departAt = document.getElementById('depart_at');
            const expectedReturnAt = document.getElementById('expected_return_at');

            // Auto-fill return date +1 hari saat depart_at diubah
            // Hanya update jika expected_return_at masih kosong
            if (departAt) {
                departAt.addEventListener('change', function() {
                    if (!expectedReturnAt.value) {
                        const departDate = new Date(this.value);
                        if (!isNaN(departDate)) {
                            const returnDate = new Date(departDate.getTime() + (24 * 60 * 60 * 1000));
                            const offset = returnDate.getTimezoneOffset() * 60000;
                            const localISOTime = (new Date(returnDate - offset)).toISOString().slice(0, 16);
                            expectedReturnAt.value = localISOTime;
                        }
                    }
                });
            }

            // Prevent double submit
            const form = document.getElementById('editLoanForm');
            const submitBtn = document.getElementById('submitBtn');
            let isSubmitting = false;

            if (form) {
                form.addEventListener('submit', function(e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }
                    isSubmitting = true;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin w-5 h-5 mr-2 inline" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    `;
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                });
            }
        });
    </script>

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>

</x-app-layout>