<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pengembalian Kendaraan #{{ $loanRequest->id }}
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
                    <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
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
                                    | Odometer awal:
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

                {{-- enctype WAJIB ada agar file bisa terupload --}}
                <form action="{{ route('returns.store', $loanRequest) }}" method="POST" 
                      enctype="multipart/form-data" class="p-6" id="returnForm">
                    @csrf

                    <div class="space-y-6">
                        {{-- Tanggal Kembali --}}
                        <div>
                            <label for="returned_at" class="block text-sm font-semibold text-gray-700 mb-1">
                                Tanggal & Jam Pengembalian <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="returned_at" name="returned_at" required
                                   value="{{ old('returned_at', now()->format('Y-m-d\TH:i')) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm 
                                          focus:border-blue-500 focus:ring-blue-500
                                          @error('returned_at') border-red-500 @enderror">
                            @error('returned_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- odometer_km_end sesuai kolom DB --}}
                        <div>
                            <label for="odometer_km_end" class="block text-sm font-semibold text-gray-700 mb-1">
                                Odometer Saat Dikembalikan (km) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="odometer_km_end" name="odometer_km_end" min="0" required
                                   value="{{ old('odometer_km_end') }}" placeholder="Contoh: 18500"
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

                        {{-- ✅ BARU: Kondisi Unit --}}
                        <div>
                            <label for="vehicle_condition" class="block text-sm font-semibold text-gray-700 mb-1">
                                Kondisi Kendaraan <span class="text-red-500">*</span>
                            </label>
                            <select id="vehicle_condition" name="vehicle_condition" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm 
                                           focus:border-blue-500 focus:ring-blue-500
                                           @error('vehicle_condition') border-red-500 @enderror">
                                <option value="">Pilih kondisi...</option>
                                <option value="good" {{ old('vehicle_condition') == 'good' ? 'selected' : '' }}>
                                    Baik (Tidak ada masalah)
                                </option>
                                <option value="minor_damage" {{ old('vehicle_condition') == 'minor_damage' ? 'selected' : '' }}>
                                    Kerusakan Ringan (Lecet, baret kecil)
                                </option>
                                <option value="major_damage" {{ old('vehicle_condition') == 'major_damage' ? 'selected' : '' }}>
                                    Kerusakan Berat (Penyok, kaca pecah, dll)
                                </option>
                                <option value="needs_maintenance" {{ old('vehicle_condition') == 'needs_maintenance' ? 'selected' : '' }}>
                                    Perlu Servis (Masalah mesin, rem, dll)
                                </option>
                            </select>
                            @error('vehicle_condition')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ✅ BARU: Keterangan Kondisi --}}
                        <div>
                            <label for="condition_notes" class="block text-sm font-semibold text-gray-700 mb-1">
                                Keterangan Kondisi Kendaraan
                            </label>
                            <textarea id="condition_notes" name="condition_notes" rows="4" maxlength="1000"
                                      placeholder="Jelaskan detail kondisi kendaraan, kerusakan (jika ada), atau catatan penting lainnya..."
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm 
                                             focus:border-blue-500 focus:ring-blue-500
                                             @error('condition_notes') border-red-500 @enderror">{{ old('condition_notes') }}</textarea>
                            @error('condition_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">
                                Jelaskan kondisi kendaraan saat dikembalikan (maksimal 1000 karakter)
                            </p>
                        </div>

                        {{-- Lampiran Foto/Dokumen --}}
                        <div>
                            <label for="attachments" class="block text-sm font-semibold text-gray-700 mb-1">
                                Lampiran Foto Kendaraan
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <input type="file" id="attachments" name="attachments[]" multiple
                                   accept="image/jpeg,image/png,application/pdf"
                                   class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-medium
                                          file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100">
                            <p class="text-xs text-gray-400 mt-1">
                                Foto kondisi kendaraan, dashboard odometer, atau dokumen lainnya. JPG, PNG, atau PDF. Maksimal 5 file @ 2MB.
                            </p>
                            @error('attachments.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                        <a href="{{ route('loan-requests.show', $loanRequest) }}"
                           class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" id="submitBtn"
                                class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-md shadow transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7" />
                            </svg>
                            Ajukan Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script loading button --}}
    <script>
        document.getElementById('returnForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
        });
    </script>
</x-app-layout>
