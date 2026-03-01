<x-app-layout>
    <x-slot name="header">Detail Pengembalian</x-slot>

    @php $isUnprocessed = is_null($return->received_by); @endphp

    {{-- ===== PAGE HERO BAR ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-gray-800 leading-tight">Detail Pengembalian</h1>
            <p class="text-sm text-gray-400 mt-0.5">
                Peminjam:
                <span class="font-semibold text-gray-600">
                    {{ $return->loanRequest->requester->full_name ?? '-' }}
                </span>
            </p>
        </div>
        <a href="{{ route('admin.returns.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold rounded-xl transition-all flex-shrink-0"
            style="background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0;"
            onmouseover="this.style.background='#e2e8f0';"
            onmouseout="this.style.background='#f1f5f9';">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="max-w-4xl mx-auto space-y-5">

        {{-- ===== FLASH MESSAGES ===== --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="flex items-center justify-between gap-3 px-4 py-3 rounded-2xl text-sm font-semibold"
            style="background:#f0fdf4; border:1.5px solid #86efac; color:#15803d;">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-green-400 hover:text-green-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="flex items-center justify-between gap-3 px-4 py-3 rounded-2xl text-sm font-semibold"
            style="background:#fef2f2; border:1.5px solid #fca5a5; color:#b91c1c;">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
            <button @click="show = false" class="text-red-400 hover:text-red-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        {{-- ===== STATUS BANNER ===== --}}
        @if($isUnprocessed)
        <div class="rounded-2xl px-5 py-4 flex items-center gap-4"
            style="background:linear-gradient(135deg,#fffbeb,#fef3c7); border:1.5px solid #fde68a;">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background:linear-gradient(135deg,#fef3c7,#fde68a);">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-extrabold text-amber-800">⏳ Belum Diproses</p>
                <p class="text-xs text-amber-700 mt-0.5">
                    Pengembalian ini menunggu verifikasi. Periksa detail lalu proses jika sudah sesuai.
                </p>
            </div>
        </div>
        @else
        <div class="rounded-2xl px-5 py-4 flex items-center gap-4"
            style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); border:1.5px solid #86efac;">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-extrabold text-green-800">✅ Sudah Diproses</p>
                <p class="text-xs text-green-700 mt-0.5">
                    Diterima oleh
                    <span class="font-bold">{{ $return->receivedBy->full_name ?? '-' }}</span>
                    pada {{ \Carbon\Carbon::parse($return->returned_at)->format('d M Y, H:i') }} WIB
                </p>
            </div>
        </div>
        @endif

        {{-- ===== INFO CARDS ===== --}}
        @php $vehicle = $return->loanRequest->assignment->assignedVehicle ?? null; @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Info Peminjam --}}
            <div class="bg-white rounded-2xl overflow-hidden"
                style="box-shadow:0 2px 12px rgba(0,0,0,0.06); border:1px solid #f0f4ff;">
                <div class="px-5 py-4 border-b flex items-center gap-2"
                    style="border-color:#f0f4ff; background:#fafbff;">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center"
                        style="background:linear-gradient(135deg,#1e1b4b,#3730a3);">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-extrabold text-gray-700">Info Peminjam</h3>
                </div>
                <div class="px-5 py-5 space-y-4">
                    @php
                        $name = $return->loanRequest->requester->full_name ?? '-';
                        $initials = strtoupper(substr($name, 0, 2));
                        $palette = [['#dbeafe','#1d4ed8'],['#dcfce7','#15803d'],['#ede9fe','#6d28d9'],['#fce7f3','#be185d'],['#ffedd5','#c2410c']];
                        $ac = $palette[crc32($name) % count($palette)];
                    @endphp
                    {{-- Avatar + nama --}}
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-sm font-extrabold flex-shrink-0"
                            style="background:{{ $ac[0] }}; color:{{ $ac[1] }};">
                            {{ $initials }}
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-gray-800">{{ $name }}</p>
                            <p class="text-xs text-gray-400">{{ $return->loanRequest->unit->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="h-px" style="background:linear-gradient(90deg,transparent,#e2e8f0,transparent);"></div>

                    <div class="grid grid-cols-1 gap-3">
                        <div class="flex items-start justify-between gap-3">
                            <span class="text-xs text-gray-400 flex-shrink-0">Keperluan</span>
                            <span class="text-xs font-semibold text-gray-700 text-right">
                                {{ $return->loanRequest->purpose ?? '-' }}
                            </span>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <span class="text-xs text-gray-400 flex-shrink-0">Tujuan</span>
                            <span class="text-xs font-semibold text-gray-700 text-right">
                                {{ $return->loanRequest->destination ?? '-' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">Tgl Berangkat</span>
                            <span class="text-xs font-semibold text-gray-700">
                                {{ \Carbon\Carbon::parse($return->loanRequest->depart_at)->format('d M Y') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">Tgl Kembali</span>
                            <span class="text-xs font-semibold text-gray-700">
                                {{ $return->returned_at
                                    ? \Carbon\Carbon::parse($return->returned_at)->format('d M Y, H:i')
                                    : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Kendaraan --}}
            <div class="bg-white rounded-2xl overflow-hidden"
                style="box-shadow:0 2px 12px rgba(0,0,0,0.06); border:1px solid #f0f4ff;">
                <div class="px-5 py-4 border-b flex items-center gap-2"
                    style="border-color:#f0f4ff; background:#fafbff;">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center"
                        style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-extrabold text-gray-700">Info Kendaraan</h3>
                </div>

                @if($vehicle)
                <div class="px-5 py-5 space-y-4">
                    {{-- Vehicle badge --}}
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl flex-shrink-0"
                            style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">🚗</div>
                        <div>
                            <p class="text-sm font-extrabold text-gray-800">
                                {{ $vehicle->brand }} {{ $vehicle->model }}
                            </p>
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-extrabold font-mono rounded-lg"
                                style="background:#1e1b4b; color:#e0e7ff;">
                                {{ $vehicle->plate_no }}
                            </span>
                        </div>
                    </div>

                    <div class="h-px" style="background:linear-gradient(90deg,transparent,#e2e8f0,transparent);"></div>

                    <div class="grid grid-cols-1 gap-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">Odometer Kembali</span>
                            <span class="text-xs font-bold text-gray-700">
                                {{ $return->odometer_km_end
                                    ? number_format($return->odometer_km_end) . ' km'
                                    : '-' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">Anggaran Digunakan</span>
                            <span class="text-xs font-bold text-indigo-700">
                                {{ $return->anggaran_digunakan
                                    ? 'Rp ' . number_format($return->anggaran_digunakan, 0, ',', '.')
                                    : '-' }}
                            </span>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <span class="text-xs text-gray-400 flex-shrink-0">Catatan</span>
                            <span class="text-xs font-semibold text-gray-700 text-right">
                                {{ $return->return_note ?: '-' }}
                            </span>
                        </div>
                    </div>
                </div>
                @else
                <div class="px-5 py-10 flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3"
                        style="background:#f1f5f9;">
                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-400 italic">Data kendaraan tidak ditemukan</p>
                </div>
                @endif
            </div>
        </div>

        {{-- ===== RINCIAN BIAYA ===== --}}
        <div class="bg-white rounded-2xl overflow-hidden"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.06); border:1px solid #f0f4ff;">
            <div class="px-5 py-4 border-b flex items-center justify-between"
                style="border-color:#f0f4ff; background:#fafbff;">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center"
                        style="background:linear-gradient(135deg,#15803d,#16a34a);">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-extrabold text-gray-700">Rincian Biaya Perjalanan</h3>
                </div>
                @if($return->expenses->isNotEmpty())
                <span class="text-sm font-extrabold text-indigo-700">
                    Rp {{ number_format($return->expenses->sum('amount'), 0, ',', '.') }}
                </span>
                @endif
            </div>

            @php
                $typeConfig = [
                    'fuel'    => ['label' => 'Bensin / BBM',    'icon' => '⛽', 'style' => 'background:#fffbeb; color:#92400e; border:1px solid #fde68a;'],
                    'toll'    => ['label' => 'Tol',              'icon' => '🛣️', 'style' => 'background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe;'],
                    'parking' => ['label' => 'Parkir',           'icon' => '🅿️', 'style' => 'background:#faf5ff; color:#5b21b6; border:1px solid #ddd6fe;'],
                    'repair'  => ['label' => 'Service/Perbaikan','icon' => '🔧', 'style' => 'background:#fef2f2; color:#991b1b; border:1px solid #fecaca;'],
                    'other'   => ['label' => 'Lainnya',          'icon' => '📋', 'style' => 'background:#f8fafc; color:#475569; border:1px solid #e2e8f0;'],
                ];
            @endphp

            @if($return->expenses->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kategori</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Keterangan</th>
                            <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($return->expenses as $expense)
                        @php
                            $cfg = $typeConfig[$expense->type] ?? ['label' => ucfirst($expense->type), 'icon' => '📋', 'style' => 'background:#f8fafc; color:#475569; border:1px solid #e2e8f0;'];
                        @endphp
                        <tr class="hover:bg-indigo-50/20 transition" style="border-bottom:1px solid #f8fafc;">
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-full"
                                    style="{{ $cfg['style'] }}">
                                    {{ $cfg['icon'] }} {{ $cfg['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-sm text-gray-600">
                                {{ $expense->description ?: '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-sm font-extrabold text-gray-800 text-right whitespace-nowrap">
                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f0f4ff; border-top:2px solid #e0e7ff;">
                            <td colspan="2" class="px-5 py-4 text-sm font-extrabold text-indigo-800 text-right">
                                Total Biaya
                            </td>
                            <td class="px-5 py-4 text-base font-extrabold text-right"
                                style="color:#1e1b4b;">
                                Rp {{ number_format($return->expenses->sum('amount'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="py-12 flex flex-col items-center justify-center text-center">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3"
                    style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-400">Tidak ada biaya dilaporkan</p>
            </div>
            @endif
        </div>

        {{-- ===== STRUK & BUKTI ===== --}}
        @php
            $receiptAttachments = $return->attachments->where('type', 'expense_receipt');
        @endphp
        <div class="bg-white rounded-2xl overflow-hidden"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.06); border:1px solid #f0f4ff;">
            <div class="px-5 py-4 border-b flex items-center justify-between"
                style="border-color:#f0f4ff; background:#fafbff;">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center"
                        style="background:linear-gradient(135deg,#6d28d9,#7c3aed);">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-extrabold text-gray-700">Struk & Bukti Pengeluaran</h3>
                </div>
                @if($receiptAttachments->isNotEmpty())
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                    style="background:#faf5ff; color:#5b21b6; border:1px solid #ddd6fe;">
                    {{ $receiptAttachments->count() }} file
                </span>
                @endif
            </div>

            @if($receiptAttachments->isNotEmpty())
            <div class="p-5">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($receiptAttachments as $attachment)
                    @php
                        $isPdf    = str_contains($attachment->mime_type ?? '', 'pdf');
                        $fileUrl  = asset('storage/' . $attachment->file_url);
                        $fileSize = $attachment->file_size_bytes
                                    ? round($attachment->file_size_bytes / 1024, 1) . ' KB'
                                    : '-';
                    @endphp
                    <div class="rounded-2xl overflow-hidden hover:shadow-md transition-all"
                        style="border:1px solid #e2e8f0;">
                        {{-- Preview --}}
                        @if($isPdf)
                        <div class="h-28 flex flex-col items-center justify-center gap-1"
                            style="background:linear-gradient(135deg,#fef2f2,#fee2e2);">
                            <svg class="w-9 h-9 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs font-extrabold text-red-500 tracking-wider">PDF</span>
                        </div>
                        @else
                        <div class="h-28 overflow-hidden bg-gray-100">
                            <img src="{{ $fileUrl }}"
                                alt="{{ $attachment->file_name }}"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                                onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-xs text-gray-400\'>Gambar tidak tersedia</div>'">
                        </div>
                        @endif

                        {{-- Info --}}
                        <div class="p-3">
                            <p class="text-xs font-bold text-gray-700 truncate" title="{{ $attachment->file_name }}">
                                {{ $attachment->file_name ?? 'File' }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $fileSize }}</p>
                            <a href="{{ $fileUrl }}" target="_blank"
                                class="mt-2 w-full inline-flex items-center justify-center gap-1 px-3 py-1.5
                                    text-xs font-bold rounded-xl transition-all"
                                style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;"
                                onmouseover="this.style.background='#1d4ed8'; this.style.color='#fff';"
                                onmouseout="this.style.background='#eff6ff'; this.style.color='#1d4ed8';">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                Lihat File
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="py-12 flex flex-col items-center justify-center text-center">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3"
                    style="background:linear-gradient(135deg,#faf5ff,#ede9fe);">
                    <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-400">Tidak ada struk yang diupload</p>
            </div>
            @endif
        </div>

        {{-- ===== FORM PROSES / INFO ===== --}}
        @if($isUnprocessed && auth()->user()->isAdminGA())

        <div x-data="{ showConfirm: false }">

            {{-- Info banner --}}
            <div class="rounded-2xl px-5 py-4 mb-4 flex items-start gap-3"
                style="background:#eff6ff; border:1.5px solid #bfdbfe;">
                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-blue-800">
                    Dengan memproses pengembalian ini, status kendaraan
                    @if($vehicle)
                        <strong>{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_no }})</strong>
                    @endif
                    akan otomatis berubah menjadi <strong>Tersedia</strong>.
                </p>
            </div>

            {{-- Trigger button --}}
            <div class="bg-white rounded-2xl px-5 py-5 flex items-center justify-between gap-4"
                style="box-shadow:0 2px 12px rgba(0,0,0,0.06); border:1px solid #f0f4ff;">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-gray-800">Proses Pengembalian</p>
                        <p class="text-xs text-gray-400">Konfirmasi kendaraan sudah diterima kembali</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.returns.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs font-bold rounded-xl transition"
                        style="background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0;"
                        onmouseover="this.style.background='#e2e8f0';"
                        onmouseout="this.style.background='#f1f5f9';">
                        Batal
                    </a>
                    <button @click="showConfirm = true"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white rounded-xl transition-all"
                        style="background:linear-gradient(135deg,#15803d,#16a34a); box-shadow:0 4px 12px rgba(21,128,61,0.35);"
                        onmouseover="this.style.boxShadow='0 6px 20px rgba(21,128,61,0.5)'; this.style.transform='translateY(-1px)';"
                        onmouseout="this.style.boxShadow='0 4px 12px rgba(21,128,61,0.35)'; this.style.transform='translateY(0)';">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Proses Pengembalian
                    </button>
                </div>
            </div>

            {{-- Confirm Modal --}}
            <div x-show="showConfirm"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                style="background:rgba(0,0,0,0.45); backdrop-filter:blur(4px);"
                @keydown.escape.window="showConfirm = false"
                x-cloak>
                <div x-show="showConfirm"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    @click.outside="showConfirm = false"
                    class="bg-white rounded-3xl p-6 w-full max-w-sm text-center"
                    style="box-shadow:0 25px 60px rgba(0,0,0,0.2);">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4"
                        style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-extrabold text-gray-800 mb-1">Konfirmasi Proses</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Kendaraan
                        @if($vehicle)
                            <span class="font-bold text-gray-700">{{ $vehicle->brand }} {{ $vehicle->model }}</span>
                        @endif
                        akan ditandai <span class="font-bold text-green-600">Tersedia</span>. Lanjutkan?
                    </p>
                    <div class="flex gap-3">
                        <button @click="showConfirm = false"
                            class="flex-1 py-2.5 text-sm font-bold rounded-xl"
                            style="background:#f1f5f9; color:#64748b;"
                            onmouseover="this.style.background='#e2e8f0';"
                            onmouseout="this.style.background='#f1f5f9';">
                            Batal
                        </button>
                        <form action="{{ route('admin.returns.process', $return) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="w-full py-2.5 text-sm font-bold text-white rounded-xl"
                                style="background:linear-gradient(135deg,#15803d,#16a34a); box-shadow:0 4px 12px rgba(21,128,61,0.35);">
                                ✅ Ya, Proses
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        @elseif($isUnprocessed && auth()->user()->isAdminAkuntansi())
        <div class="rounded-2xl px-5 py-4 flex items-center gap-3"
            style="background:#f8fafc; border:1.5px solid #e2e8f0;">
            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-gray-500">
                Hanya <span class="font-bold text-gray-700">Admin GA</span> yang dapat memproses pengembalian ini.
            </p>
        </div>
        @endif

    </div>

</x-app-layout>