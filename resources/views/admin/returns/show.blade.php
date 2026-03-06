<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pengembalian #{{ $return->id }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Tombol Kembali --}}
            <div>
                <a href="{{ route('admin.returns.index') }}"
                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar Pengembalian
                </a>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
            @endif

            {{-- Status Banner --}}
            @php
            $isUnprocessed = is_null($return->received_by);
            $vehicle = $return->loanRequest?->assignment?->assignedVehicle;

            $conditionMap = [
            'good' => ['label' => 'Baik', 'class' => 'bg-green-100 text-green-800 border-green-200', 'icon' => '✅'],
            'minor_damage' => ['label' => 'Kerusakan Ringan', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200','icon' => '⚠️'],
            'major_damage' => ['label' => 'Kerusakan Berat', 'class' => 'bg-red-100 text-red-800 border-red-200', 'icon' => '🔴'],
            'needs_maintenance' => ['label' => 'Perlu Servis', 'class' => 'bg-orange-100 text-orange-800 border-orange-200','icon' => '🔧'],
            ];
            $conditionCfg = $conditionMap[$return->vehicle_condition] ?? ['label' => '-', 'class' => 'bg-gray-100 text-gray-700 border-gray-200', 'icon' => '—'];

            // Status kendaraan setelah diproses
            $vehicleStatusAfter = match($return->vehicle_condition) {
            'good' => 'Tersedia',
            default => 'Maintenance',
            };
            @endphp

            @if($isUnprocessed)
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-start gap-3">
                <span class="text-2xl">⏳</span>
                <div>
                    <p class="font-semibold text-amber-800">Belum Diproses</p>
                    <p class="text-sm text-amber-700 mt-0.5">
                        Pengembalian ini menunggu verifikasi. Periksa detail lalu proses jika sudah sesuai.
                    </p>
                    <p class="text-xs text-amber-600 mt-1">
                        Peminjam: <strong>{{ $return->loanRequest?->requester?->full_name ?? '-' }}</strong>
                    </p>
                </div>
            </div>
            @else
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-start gap-3">
                <span class="text-2xl">✅</span>
                <div>
                    <p class="font-semibold text-green-800">Sudah Diproses</p>
                    <p class="text-sm text-green-700 mt-0.5">
                        Diterima oleh <strong>{{ $return->receivedBy?->full_name ?? '-' }}</strong>
                        pada {{ \Carbon\Carbon::parse($return->returned_at)->format('d M Y, H:i') }} WIB
                    </p>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom Kiri: Info Peminjaman --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Informasi Peminjam --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-5 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-800">👤 Informasi Peminjam</h3>
                        </div>
                        <div class="p-5 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Nama</p>
                                <p class="text-gray-800 font-medium">
                                    {{ $return->loanRequest?->requester?->full_name ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Unit / Departemen</p>
                                <p class="text-gray-800 font-medium">
                                    {{ $return->loanRequest?->unit?->name ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Tujuan</p>
                                <p class="text-gray-800">{{ $return->loanRequest?->destination ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Projek</p>
                                <p class="text-gray-800">{{ $return->loanRequest?->projek ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Kendaraan --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-5 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-800">🚗 Informasi Kendaraan</h3>
                        </div>
                        <div class="p-5 text-sm">
                            @if($vehicle)
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Kendaraan</p>
                                    <p class="text-gray-800 font-medium">
                                        {{ $vehicle->brand }} {{ $vehicle->model }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Nomor Polisi</p>
                                    <p class="text-gray-800 font-medium">{{ $vehicle->plate_no }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Odometer Awal</p>
                                    <p class="text-gray-800">{{ number_format($vehicle->odometer_km) }} km</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Odometer Kembali</p>
                                    <p class="text-gray-800 font-medium">
                                        {{ number_format($return->odometer_km_end) }} km
                                    </p>
                                    @if($return->odometer_km_end && $vehicle->odometer_km)
                                    <p class="text-xs text-blue-500 mt-0.5">
                                        +{{ number_format($return->odometer_km_end - $vehicle->odometer_km) }} km ditempuh
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @else
                            <p class="text-gray-400 italic">Data kendaraan tidak ditemukan</p>
                            @endif
                        </div>
                    </div>

                    {{-- ✅ Kondisi Kendaraan (BARU - ganti Pengeluaran) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-5 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-800">🔍 Kondisi Kendaraan Saat Kembali</h3>
                        </div>
                        <div class="p-5 space-y-4 text-sm">
                            {{-- Badge Kondisi --}}
                            <div>
                                <p class="text-xs text-gray-400 font-semibold uppercase mb-2">Status Kondisi</p>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold border {{ $conditionCfg['class'] }}">
                                    {{ $conditionCfg['icon'] }} {{ $conditionCfg['label'] }}
                                </span>
                            </div>

                            {{-- Keterangan Kondisi --}}
                            <div>
                                <p class="text-xs text-gray-400 font-semibold uppercase mb-2">Keterangan Kondisi</p>
                                @if($return->return_note)
                                <p class="text-gray-800 bg-gray-50 rounded-lg p-3 border border-gray-100">
                                    {{ $return->return_note }}
                                </p>
                                @else
                                <p class="text-gray-400 italic">Tidak ada keterangan tambahan</p>
                                @endif
                            </div>

                            {{-- Info status kendaraan setelah diproses --}}
                            @if($isUnprocessed)
                            <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 text-xs text-blue-700">
                                ℹ️ Setelah diproses, status kendaraan akan otomatis berubah menjadi
                                <strong>{{ $vehicleStatusAfter }}</strong>
                                berdasarkan kondisi di atas.
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Lampiran --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-5 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-800">📎 Lampiran Foto Kendaraan</h3>
                        </div>
                        <div class="p-5">
                            @if($return->attachments && $return->attachments->count())
                            <div class="space-y-2">
                                @foreach($return->attachments as $attachment)
                                @php
                                $bytes = $attachment->file_size_bytes;
                                $fileSize = $bytes
                                ? ($bytes >= 1048576
                                ? number_format($bytes / 1048576, 1) . ' MB'
                                : number_format($bytes / 1024, 0) . ' KB')
                                : '';
                                @endphp
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <div class="min-w-0">
                                            <p class="text-sm text-gray-700 font-medium truncate">
                                                {{ $attachment->file_name ?? 'File' }}
                                            </p>
                                            @if($fileSize)
                                            <p class="text-xs text-gray-400">{{ $fileSize }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($attachment->file_url) }}" target="_blank"
                                        class="flex-shrink-0 ml-3 text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        Lihat File
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-sm text-gray-400 italic">Tidak ada lampiran yang diupload</p>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan: Aksi --}}
                <div class="space-y-4">

                    {{-- Info Waktu --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-sm space-y-3">
                        <h3 class="font-semibold text-gray-800">🕐 Waktu Pengembalian</h3>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Tanggal Kembali</p>
                            <p class="font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($return->returned_at)->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($return->returned_at)->format('H:i') }} WIB
                            </p>
                        </div>
                        @if($return->loanRequest?->expected_return_at)
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Rencana Kembali</p>
                            <p class="text-gray-700">
                                {{ \Carbon\Carbon::parse($return->loanRequest->expected_return_at)->translatedFormat('d F Y, H:i') }} WIB
                            </p>
                            @php
                            $diff = \Carbon\Carbon::parse($return->returned_at)
                            ->diff(\Carbon\Carbon::parse($return->loanRequest->expected_return_at));
                            $isLate = \Carbon\Carbon::parse($return->returned_at)
                            ->gt(\Carbon\Carbon::parse($return->loanRequest->expected_return_at));
                            @endphp
                            @if($isLate)
                            <p class="text-xs text-red-500 mt-0.5">
                                ⚠️ Terlambat {{ $diff->days > 0 ? $diff->days . ' hari ' : '' }}{{ $diff->h }} jam
                            </p>
                            @else
                            <p class="text-xs text-green-500 mt-0.5">✅ Tepat waktu</p>
                            @endif
                        </div>
                        @endif
                    </div>

                    {{-- Tombol Proses (Admin GA) --}}
                    @if($isUnprocessed && auth()->user()->isAdminGA())
                    <div class="bg-white rounded-xl shadow-sm border border-amber-200 p-5">
                        <h3 class="font-semibold text-gray-800 mb-2">⚡ Proses Pengembalian</h3>
                        <p class="text-xs text-gray-500 mb-4">
                            Dengan memproses pengembalian ini, status kendaraan
                            @if($vehicle)
                            <strong>{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_no }})</strong>
                            @endif
                            akan otomatis berubah menjadi
                            <strong class="{{ $return->vehicle_condition === 'good' ? 'text-green-600' : 'text-orange-600' }}">
                                {{ $vehicleStatusAfter }}
                            </strong>
                            sesuai kondisi yang dilaporkan.
                        </p>
                        <button type="button" onclick="document.getElementById('processModal').classList.remove('hidden')"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Proses Pengembalian
                        </button>
                    </div>
                    @elseif(!auth()->user()->isAdminGA())
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-xs text-gray-500 text-center">
                        Hanya Admin GA yang dapat memproses pengembalian ini.
                    </div>
                    @endif

                    {{-- Link ke Loan Request --}}
                    @if($return->loanRequest)
                    <a href="{{ route('admin.loan-requests.show', $return->loanRequest) }}"
                        class="block w-full text-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                        Lihat Detail Peminjaman →
                    </a>
                    @endif

                </div>
            </div>

        </div>
    </div>

    {{-- Modal Konfirmasi Proses --}}
    <div id="processModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi Pengembalian</h3>
            <p class="text-sm text-gray-600 mb-1">
                Kendaraan
                @if($vehicle) <strong>{{ $vehicle->brand }} {{ $vehicle->model }}</strong> @endif
                akan ditandai sebagai
                <strong class="{{ $return->vehicle_condition === 'good' ? 'text-green-600' : 'text-orange-600' }}">
                    {{ $vehicleStatusAfter }}
                </strong>.
            </p>
            <p class="text-sm text-gray-500 mb-5">
                Kondisi dilaporkan:
                <span class="font-medium">{{ $conditionCfg['icon'] }} {{ $conditionCfg['label'] }}</span>.
                Lanjutkan?
            </p>
            <div class="flex gap-3">
                <button type="button"
                    onclick="document.getElementById('processModal').classList.add('hidden')"
                    class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                    Batal
                </button>

                {{-- ✅ BENAR — route POST, tidak perlu @method --}}
                <form action="{{ route('admin.returns.process', $return) }}" method="POST" class="flex-1">
                    @csrf
                    {{-- HAPUS baris @method('PATCH') --}}
                    <button type="submit"
                        class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                        Ya, Proses
                    </button>
                </form>

            </div>
        </div>
    </div>

</x-app-layout>