<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pengembalian Kendaraan — #{{ $loanRequest->id }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Tombol Kembali --}}
            <div class="mb-6">
                <a href="{{ route('loan-requests.show', $loanRequest) }}"
                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Detail Peminjaman
                </a>
            </div>

            {{-- Error --}}
            @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <strong class="font-bold">Terdapat kesalahan:</strong>
                <ul class="list-disc list-inside mt-2 ml-4">
                    @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Info Peminjaman --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-2">Informasi Peminjaman</h3>
                        <div class="text-sm text-blue-800 space-y-1">
                            <p>
                                <span class="font-medium">Kendaraan:</span>
                                @if($loanRequest->assignment?->assignedVehicle)
                                {{ $loanRequest->assignment->assignedVehicle->brand }}
                                {{ $loanRequest->assignment->assignedVehicle->model }}
                                ({{ $loanRequest->assignment->assignedVehicle->plate_no }})
                                — Odometer awal:
                                {{ number_format($loanRequest->assignment->assignedVehicle->odometer_km) }} km
                                @else
                                <span class="italic">Tidak ada data kendaraan</span>
                                @endif
                            </p>
                            <p>
                                <span class="font-medium">Tanggal Berangkat:</span>
                                {{ $loanRequest->depart_at?->translatedFormat('d F Y, H:i') ?? '-' }} WIB
                            </p>
                            <p>
                                <span class="font-medium">Rencana Kembali:</span>
                                {{ $loanRequest->expected_return_at?->translatedFormat('d F Y, H:i') ?? '-' }} WIB
                            </p>
                            <p>
                                <span class="font-medium">Tujuan:</span>
                                {{ $loanRequest->destination ?? '-' }}
                            </p>
                            <p>
                                <span class="font-medium">Projek:</span>
                                {{ $loanRequest->projek ?? '-' }}
                            </p>
                            <p>
                                <span class="font-medium">Anggaran Awal:</span>
                                {{ $loanRequest->anggaran_awal?? '-' }}
                            </p>


                            <p>
                                <span class="font-medium">Pemohon:</span>
                                {{ $loanRequest->requester->full_name ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Form Pengembalian</h3>
                    <p class="text-sm text-gray-600 mt-1">Lengkapi form berikut untuk mengembalikan kendaraan</p>
                </div>

                {{-- ✅ enctype WAJIB ada agar file bisa terupload --}}
                <form action="{{ route('returns.store', $loanRequest) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="p-6"
                    id="returnForm">
                    @csrf

                    <div class="space-y-6">

                        {{-- Tanggal Kembali --}}
                        <div>
                            <label for="returned_at" class="block text-sm font-semibold text-gray-700 mb-1">
                                Tanggal & Jam Pengembalian <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local"
                                id="returned_at"
                                name="returned_at"
                                required
                                value="{{ old('returned_at', now()->format('Y-m-d\TH:i')) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm
                                          focus:border-blue-500 focus:ring-blue-500
                                          @error('returned_at') border-red-500 @enderror">
                            @error('returned_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ✅ odometer_km_end — sesuai kolom DB --}}
                        <div>
                            <label for="odometer_km_end" class="block text-sm font-semibold text-gray-700 mb-1">
                                Odometer Saat Dikembalikan (km) <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                id="odometer_km_end"
                                name="odometer_km_end"
                                min="0"
                                required
                                value="{{ old('odometer_km_end') }}"
                                placeholder="Contoh: 18500"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm
                                          focus:border-blue-500 focus:ring-blue-500
                                          @error('odometer_km_end') border-red-500 @enderror">
                            @error('odometer_km_end')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">
                                Isi angka odometer kendaraan setelah digunakan
                            </p>
                        </div>
                        <!-- anggaran digunakan -->
                        <div>
                            <label for="anggaran_digunakan" class="block text-sm font-semibold text-gray-700 mb-1">
                                Anggaran yang Digunakan (Rp)
                            </label>
                            <input type="number"
                                id="anggaran_digunakan"
                                name="anggaran_digunakan"
                                min="0"
                                value="{{ old('anggaran_digunakan') }}"
                                placeholder="Contoh: 150000"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm
                                          focus:border-blue-500 focus:ring-blue-500
                                          @error('anggaran_digunakan') border-red-500 @enderror">
                            @error('anggaran_digunakan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">
                                Isi total anggaran yang digunakan selama peminjaman (opsional)
                            </p>
                        </div>

                        {{-- ✅ return_note — sesuai kolom DB --}}
                        <div>
                            <label for="return_note" class="block text-sm font-semibold text-gray-700 mb-1">
                                Catatan Pengembalian
                            </label>
                            <textarea id="return_note"
                                name="return_note"
                                rows="3"
                                maxlength="1000"
                                placeholder="Catatan kondisi kendaraan, penggunaan BBM, dll... (opsional)"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm
                                             focus:border-blue-500 focus:ring-blue-500">{{ old('return_note') }}</textarea>
                            @error('return_note')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pengeluaran --}}
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-base font-semibold text-gray-900 mb-1">
                                Pengeluaran Selama Peminjaman
                            </h4>
                            <p class="text-sm text-gray-600 mb-4">
                                Tambahkan rincian biaya yang dikeluarkan beserta struk buktinya (opsional)
                            </p>

                            <div id="expenses-container" class="space-y-4">
                                {{-- Row pertama (index 0) --}}
                                <div class="expense-row bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm font-medium text-gray-700">Pengeluaran #1</span>
                                        <button type="button" onclick="removeExpense(this)"
                                            class="text-red-600 hover:text-red-800 text-sm hidden">
                                            Hapus
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Jenis Pengeluaran
                                            </label>
                                            {{-- ✅ ENUM DB: fuel,toll,parking,repair,other --}}
                                            <select name="expenses[0][type]"
                                                class="block w-full border-gray-300 rounded-md shadow-sm
                                                           focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">Pilih jenis...</option>
                                                <option value="fuel" {{ old('expenses.0.type') === 'fuel'    ? 'selected' : '' }}>⛽ Bensin / BBM</option>
                                                <option value="toll" {{ old('expenses.0.type') === 'toll'    ? 'selected' : '' }}>🛣️ Tol</option>
                                                <option value="parking" {{ old('expenses.0.type') === 'parking' ? 'selected' : '' }}>🅿️ Parkir</option>
                                                <option value="repair" {{ old('expenses.0.type') === 'repair'  ? 'selected' : '' }}>🔧 Service / Perbaikan</option>
                                                <option value="other" {{ old('expenses.0.type') === 'other'   ? 'selected' : '' }}>📦 Lainnya</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Jumlah (Rp)
                                            </label>
                                            <input type="number"
                                                name="expenses[0][amount]"
                                                min="0" step="1000"
                                                value="{{ old('expenses.0.amount') }}"
                                                placeholder="50000"
                                                class="block w-full border-gray-300 rounded-md shadow-sm
                                                          focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Keterangan
                                            </label>
                                            {{-- ✅ description — sesuai kolom DB --}}
                                            <input type="text"
                                                name="expenses[0][description]"
                                                value="{{ old('expenses.0.description') }}"
                                                placeholder="Contoh: BBM di SPBU Jl. Sudirman"
                                                maxlength="255"
                                                class="block w-full border-gray-300 rounded-md shadow-sm
                                                          focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Struk / Bukti Pengeluaran
                                                <span class="text-gray-400 font-normal">(opsional)</span>
                                            </label>
                                            {{-- ✅ receipt → disimpan ke receipt_url di DB --}}
                                            <input type="file"
                                                name="expenses[0][receipt]"
                                                accept="image/jpeg,image/png,.pdf"
                                                class="block w-full text-sm text-gray-500
                                                          file:mr-4 file:py-2 file:px-4 file:rounded-md
                                                          file:border-0 file:text-sm file:font-medium
                                                          file:bg-blue-50 file:text-blue-700
                                                          hover:file:bg-blue-100">
                                            <p class="text-xs text-gray-400 mt-1">
                                                JPG / PNG / PDF · Maks 2MB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tambah Pengeluaran --}}
                            <div class="mt-4">
                                <button type="button" onclick="addExpense()"
                                    class="inline-flex items-center px-4 py-2 bg-gray-100 border
                                               border-gray-300 rounded-md text-sm font-medium
                                               text-gray-700 hover:bg-gray-200 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Pengeluaran
                                </button>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('loan-requests.show', $loanRequest) }}"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm
                                      font-medium text-gray-700 hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" id="submitBtn"
                                class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700
                                           text-white text-sm font-semibold rounded-md shadow transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Ajukan Pengembalian
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ✅ Script inline — tidak pakai @push agar pasti ter-load --}}
    <script>
        let expenseCount = 1;

        function addExpense() {
            const container = document.getElementById('expenses-container');
            const index = expenseCount;
            expenseCount++;

            const newRow = document.createElement('div');
            newRow.className = 'expense-row bg-gray-50 p-4 rounded-lg border border-gray-200';
            newRow.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-700">Pengeluaran #${expenseCount}</span>
                    <button type="button" onclick="removeExpense(this)"
                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                        Hapus
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pengeluaran</label>
                        <select name="expenses[${index}][type]"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih jenis...</option>
                            <option value="fuel">⛽ Bensin / BBM</option>
                            <option value="toll">🛣️ Tol</option>
                            <option value="parking">🅿️ Parkir</option>
                            <option value="repair">🔧 Service / Perbaikan</option>
                            <option value="other">📦 Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp)</label>
                        <input type="number" name="expenses[${index}][amount]"
                               min="0" step="1000" placeholder="50000"
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <input type="text" name="expenses[${index}][description]"
                               placeholder="Contoh: BBM di SPBU Jl. Sudirman" maxlength="255"
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Struk / Bukti Pengeluaran
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="file" name="expenses[${index}][receipt]"
                               accept="image/jpeg,image/png,.pdf"
                               class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                      file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                        <p class="text-xs text-gray-400 mt-1">JPG / PNG / PDF · Maks 2MB</p>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
        }

        function removeExpense(button) {
            button.closest('.expense-row').remove();
        }

        document.getElementById('returnForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Menyimpan...
            `;
        });
    </script>

</x-app-layout>