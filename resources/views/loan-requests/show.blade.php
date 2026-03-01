<x-app-layout>
    <x-slot name="header">Detail Pengajuan</x-slot>

    @php
    $kepalaApproval = $loanRequest->approvals->where('approval_level', 'kepala')->first();
    $gaApproval = $loanRequest->approvals->where('approval_level', 'ga')->first();
    $kepalaSig = $kepalaApproval?->approver_signature ?? $loanRequest->kepala_signature;
    $gaSig = $gaApproval?->approver_signature ?? $loanRequest->approver_signature;

    $statusMap = [
    'submitted' => ['pill' => 'bg-amber-100 text-amber-800 border-amber-300', 'label' => '⏳ Menunggu Persetujuan', 'step' => 0],
    'approved_kepala' => ['pill' => 'bg-blue-100 text-blue-800 border-blue-300', 'label' => '📋 Disetujui Kepala Dept', 'step' => 1],
    'approved_ga' => ['pill' => 'bg-indigo-100 text-indigo-800 border-indigo-300', 'label' => '✅ Disetujui Admin GA', 'step' => 2],
    'assigned' => ['pill' => 'bg-cyan-100 text-cyan-800 border-cyan-300', 'label' => '🔑 Kendaraan Ditugaskan', 'step' => 3],
    'in_use' => ['pill' => 'bg-purple-100 text-purple-800 border-purple-300', 'label' => '🚗 Sedang Digunakan', 'step' => 4],
    'returned' => ['pill' => 'bg-green-100 text-green-800 border-green-300', 'label' => '✔️ Selesai / Dikembalikan', 'step' => 5],
    'rejected' => ['pill' => 'bg-red-100 text-red-800 border-red-300', 'label' => '❌ Ditolak', 'step' => -1],
    ];
    $sCfg = $statusMap[$loanRequest->status] ?? ['pill' => 'bg-gray-100 text-gray-700 border-gray-200', 'label' => strtoupper(str_replace('_', ' ', $loanRequest->status)), 'step' => 0];
    $cStep = $sCfg['step'];
    @endphp

    {{-- ===== TOP IDENTITY BAR ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('loan-requests.index') }}"
                class="flex items-center justify-center w-9 h-9 rounded-xl transition flex-shrink-0"
                style="background:#f1f5f9; color:#64748b;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-extrabold text-gray-800 leading-tight">
                    Detail Pengajuan <span class="font-mono text-blue-600">#{{ $loanRequest->id }}</span>
                </h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    Dibuat {{ $loanRequest->created_at->locale('id')->diffForHumans() }} ·
                    {{ $loanRequest->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') }} WIB
                </p>
            </div>
        </div>
        <span class="inline-flex items-center px-4 py-2 text-sm font-bold rounded-full border {{ $sCfg['pill'] }} flex-shrink-0">
            {{ $sCfg['label'] }}
        </span>
    </div>

    {{-- ===== PROGRESS TRACKER (hanya kalau bukan rejected) ===== --}}
    @if($loanRequest->status !== 'rejected')
    @php
    $steps = [
    ['icon' => '📝', 'label' => 'Diajukan'],
    ['icon' => '👤', 'label' => 'Kepala Dept'],
    ['icon' => '🏢', 'label' => 'Admin GA'],
    ['icon' => '🔑', 'label' => 'Ditugaskan'],
    ['icon' => '🚗', 'label' => 'Digunakan'],
    ['icon' => '✅', 'label' => 'Selesai'],
    ];
    @endphp
    <div class="bg-white rounded-2xl px-6 py-4 mb-6"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">
        <div class="flex items-center justify-between">
            @foreach($steps as $i => $step)
            <div class="flex flex-col items-center flex-1">
                <div class="relative flex items-center w-full">
                    {{-- line before --}}
                    @if($i > 0)
                    <div class="flex-1 h-1 rounded"
                        style="{{ $cStep >= $i ? 'background: linear-gradient(90deg,#0052A3,#0066CC)' : 'background:#e2e8f0' }}">
                    </div>
                    @else
                    <div class="flex-1"></div>
                    @endif

                    {{-- dot --}}
                    <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center text-base z-10 transition-all"
                        style="{{ $cStep >= $i
                            ? 'background: linear-gradient(135deg,#0052A3,#0066CC); box-shadow:0 0 0 4px rgba(0,82,163,0.15);'
                            : 'background:#f1f5f9; border:2px solid #e2e8f0;' }}">
                        @if($cStep >= $i)
                        <span>{{ $step['icon'] }}</span>
                        @else
                        <span style="color:#cbd5e1; font-size:0.75rem; font-weight:700;">{{ $i + 1 }}</span>
                        @endif
                    </div>

                    {{-- line after --}}
                    @if($i < count($steps) - 1)
                        <div class="flex-1 h-1 rounded"
                        style="{{ $cStep > $i ? 'background: linear-gradient(90deg,#0066CC,#0052A3)' : 'background:#e2e8f0' }}">
                </div>
                @else
                <div class="flex-1"></div>
                @endif
            </div>
            <p class="text-xs mt-1.5 font-semibold text-center {{ $cStep >= $i ? 'text-blue-700' : 'text-gray-400' }}">
                {{ $step['label'] }}
            </p>
        </div>
        @endforeach
    </div>
    </div>
    @endif

    {{-- ===== MAIN CARD ===== --}}
    <div class="bg-white rounded-2xl overflow-hidden mb-6"
        style="box-shadow:0 4px 24px rgba(0,0,0,0.07); border:1px solid #f0f4ff;">

        {{-- Card Header --}}
        <div class="text-center py-6 px-6 border-b-2" style="border-color:#0052A3; background:linear-gradient(135deg,#fafbff,#eff6ff);">
            <p class="text-xs font-bold uppercase tracking-widest text-blue-400 mb-1">Formulir Resmi</p>
            <h3 class="text-xl font-extrabold text-gray-800 leading-tight">PT. SWABINA GATRA</h3>
            <h4 class="text-base font-bold text-blue-700 mt-1">PERMINTAAN PEMINJAMAN KENDARAAN DINAS</h4>
            <p class="text-xs text-gray-400 mt-2 flex items-center justify-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Surabaya, {{ $loanRequest->created_at->translatedFormat('d F Y') }}
            </p>
        </div>

        <div class="p-6 sm:p-8 space-y-8">

            {{-- ===== SECTION: PEMOHON ===== --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                        style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h5 class="text-sm font-extrabold text-gray-700 uppercase tracking-wider">Informasi Pemohon</h5>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-xl p-4" style="background:#fafbff; border:1px solid #e8f0fe;">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Nama Pemakai</p>
                        <p class="font-bold text-gray-800 text-base">{{ $loanRequest->requester->full_name ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl p-4" style="background:#fafbff; border:1px solid #e8f0fe;">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Unit / Departemen</p>
                        <p class="font-bold text-gray-800 text-base">{{ $loanRequest->unit->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <hr style="border-color:#f0f4ff;">

            {{-- ===== SECTION: KEPERLUAN & TUJUAN ===== --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                        style="background:linear-gradient(135deg,#0284c7,#0ea5e9);">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h5 class="text-sm font-extrabold text-gray-700 uppercase tracking-wider">Keperluan & Tujuan</h5>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-xl p-4" style="background:#fafbff; border:1px solid #e8f0fe;">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Keperluan</p>
                        <p class="text-gray-800 font-medium">{{ $loanRequest->purpose ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl p-4" style="background:#fafbff; border:1px solid #e8f0fe;">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Tujuan</p>
                        <p class="text-gray-800 font-medium">{{ $loanRequest->destination ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl p-4" style="background:#fafbff; border:1px solid #e8f0fe;">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Projek</p>
                        <p class="text-gray-800 font-medium">{{ $loanRequest->projek ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl p-4" style="background:#fafbff; border:1px solid #e8f0fe;">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Anggaran</p>
                        <p class="text-gray-800 font-medium">{{ $loanRequest->anggaran_awal ?? '-' }}</p>
                    </div>

                    <div class="rounded-xl p-4" style="background:#fafbff; border:1px solid #e8f0fe;">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Kendaraan Diminta</p>
                        @if($loanRequest->preferredVehicle)
                        <p class="font-bold text-gray-800">
                            {{ $loanRequest->preferredVehicle->brand }}
                            {{ $loanRequest->preferredVehicle->model }}
                        </p>
                        <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-lg mt-1 inline-block"
                            style="background:#f1f5f9; color:#475569;">
                            {{ $loanRequest->preferredVehicle->plate_no }}
                        </span>
                        @elseif($loanRequest->requested_vehicle_text)
                        <p class="text-gray-800">{{ $loanRequest->requested_vehicle_text }}</p>
                        @else
                        <p class="text-xs text-gray-400 italic">Tidak ada preferensi</p>
                        @endif
                    </div>
                    <div class="rounded-xl p-4" style="background:#fafbff; border:1px solid #e8f0fe;">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Kota Pengajuan</p>
                        <p class="text-gray-800 font-medium">{{ $loanRequest->request_city ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <hr style="border-color:#f0f4ff;">

            {{-- ===== SECTION: JADWAL PERJALANAN ===== --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                        style="background:linear-gradient(135deg,#7c3aed,#8b5cf6);">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h5 class="text-sm font-extrabold text-gray-700 uppercase tracking-wider">Jadwal Perjalanan</h5>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-xl p-4" style="background:#fdf8ff; border:1px solid #ede9fe;">
                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Siap Di</p>
                        <p class="text-gray-800 font-semibold">{{ $loanRequest->siap_di ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl p-4" style="background:#fdf8ff; border:1px solid #ede9fe;">
                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Jam & Tanggal Berangkat</p>
                        <p class="text-gray-800 font-semibold">
                            @if($loanRequest->depart_at)
                            {{ $loanRequest->depart_at->translatedFormat('d F Y, H:i') }} WIB
                            @else
                            -
                            @endif
                        </p>
                    </div>
                    <div class="rounded-xl p-4" style="background:#fdf8ff; border:1px solid #ede9fe;">
                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Kembali Di</p>
                        <p class="text-gray-800 font-semibold">{{ $loanRequest->kembali_di ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl p-4" style="background:#fdf8ff; border:1px solid #ede9fe;">
                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Jam & Tanggal Kembali</p>
                        <p class="text-gray-800 font-semibold">
                            @if($loanRequest->expected_return_at)
                            {{ $loanRequest->expected_return_at->translatedFormat('d F Y, H:i') }} WIB
                            @else
                            -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <hr style="border-color:#f0f4ff;">

            {{-- ===== SECTION: TANDA TANGAN ===== --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                        style="background:linear-gradient(135deg,#059669,#10b981);">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                    <h5 class="text-sm font-extrabold text-gray-700 uppercase tracking-wider">Tanda Tangan & Persetujuan</h5>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- 1. PEMOHON --}}
                    <div class="rounded-2xl overflow-hidden" style="border:2px solid #bfdbfe;">
                        <div class="px-4 py-3 flex items-center gap-2" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center" style="background:#3b82f6;">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h6 class="font-extrabold text-blue-800 text-sm tracking-wide">PEMOHON</h6>
                        </div>
                        <div class="p-4" style="background:#fafcff;">
                            <p class="text-xs text-center text-gray-500 mb-0.5">Nama</p>
                            <p class="font-bold text-center text-sm text-gray-800 mb-1">
                                {{ $loanRequest->requester->full_name ?? '-' }}
                            </p>
                            <p class="text-xs text-center text-gray-400 mb-3">
                                {{ $loanRequest->created_at->format('d/m/Y H:i') }}
                            </p>
                            @if($loanRequest->requester_signature)
                            <div class="bg-white rounded-xl border-2 border-blue-200 min-h-[120px] flex items-center justify-center p-2">
                                <img src="{{ asset('storage/' . $loanRequest->requester_signature) }}"
                                    alt="TTD Pemohon"
                                    class="max-h-28 max-w-full object-contain"
                                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <div style="display:none;" class="flex-col items-center justify-center w-full">
                                    <p class="text-xs text-red-400 italic text-center">TTD tidak dapat dimuat</p>
                                </div>
                            </div>
                            @else
                            <div class="bg-white rounded-xl border-2 border-dashed border-blue-200 min-h-[120px] flex flex-col items-center justify-center gap-1">
                                <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                <p class="text-xs text-gray-400 italic">Tidak ada TTD</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. KEPALA DEPARTEMEN --}}
                    <div class="rounded-2xl overflow-hidden" style="border:2px solid #bbf7d0;">
                        <div class="px-4 py-3 flex items-center gap-2" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center" style="background:#22c55e;">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h6 class="font-extrabold text-green-800 text-sm tracking-wide">KEPALA DEPT</h6>
                            @if($kepalaApproval)
                            <span class="ml-auto text-xs font-bold px-2 py-0.5 rounded-full
                                {{ $kepalaApproval->decision === 'approved' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                {{ $kepalaApproval->decision === 'approved' ? '✅ Setuju' : '❌ Tolak' }}
                            </span>
                            @endif
                        </div>
                        <div class="p-4" style="background:#fafffe;">
                            @if($kepalaApproval)
                            <p class="text-xs text-center text-gray-500 mb-0.5">Nama</p>
                            <p class="font-bold text-center text-sm text-gray-800 mb-1">
                                {{ $kepalaApproval->approver->full_name ?? '-' }}
                            </p>
                            <p class="text-xs text-center text-gray-400 mb-3">
                                {{ $kepalaApproval->decided_at->format('d/m/Y H:i') }}
                            </p>
                            @if($kepalaSig)
                            <div class="bg-white rounded-xl border-2 border-green-200 min-h-[120px] flex items-center justify-center p-2">
                                <img src="{{ asset('storage/' . $kepalaSig) }}"
                                    alt="TTD Kepala"
                                    class="max-h-28 max-w-full object-contain"
                                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <div style="display:none;" class="flex-col items-center justify-center w-full">
                                    <p class="text-xs text-red-400 italic text-center">TTD tidak dapat dimuat</p>
                                </div>
                            </div>
                            @else
                            <div class="bg-white rounded-xl border-2 border-dashed border-green-200 min-h-[120px] flex flex-col items-center justify-center gap-1">
                                <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                <p class="text-xs text-gray-400 italic">Approved tanpa TTD</p>
                            </div>
                            @endif
                            @else
                            <div class="bg-white rounded-xl border-2 border-dashed border-green-200 min-h-[150px] flex flex-col items-center justify-center gap-2">
                                <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-gray-400 italic text-center">
                                    {{ $loanRequest->status === 'submitted' ? '⏳ Menunggu Approval' : 'Belum Diproses' }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- 3. ADMIN GA --}}
                    <div class="rounded-2xl overflow-hidden" style="border:2px solid #ddd6fe;">
                        <div class="px-4 py-3 flex items-center gap-2" style="background:linear-gradient(135deg,#faf5ff,#ede9fe);">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center" style="background:#8b5cf6;">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h6 class="font-extrabold text-purple-800 text-sm tracking-wide">ADMIN GA</h6>
                            @if($gaApproval)
                            <span class="ml-auto text-xs font-bold px-2 py-0.5 rounded-full
                                {{ $gaApproval->decision === 'approved' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                {{ $gaApproval->decision === 'approved' ? '✅ Setuju' : '❌ Tolak' }}
                            </span>
                            @endif
                        </div>
                        <div class="p-4" style="background:#fdfcff;">
                            @if($gaApproval)
                            <p class="text-xs text-center text-gray-500 mb-0.5">Nama</p>
                            <p class="font-bold text-center text-sm text-gray-800 mb-1">
                                {{ $gaApproval->approver->full_name ?? '-' }}
                            </p>
                            <p class="text-xs text-center text-gray-400 mb-3">
                                {{ $gaApproval->decided_at->format('d/m/Y H:i') }}
                            </p>
                            @if($gaSig)
                            <div class="bg-white rounded-xl border-2 border-purple-200 min-h-[120px] flex items-center justify-center p-2">
                                <img src="{{ asset('storage/' . $gaSig) }}"
                                    alt="TTD Admin GA"
                                    class="max-h-28 max-w-full object-contain"
                                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <div style="display:none;" class="flex-col items-center justify-center w-full">
                                    <p class="text-xs text-red-400 italic text-center">TTD tidak dapat dimuat</p>
                                </div>
                            </div>
                            @else
                            <div class="bg-white rounded-xl border-2 border-dashed border-purple-200 min-h-[120px] flex flex-col items-center justify-center gap-1">
                                <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                <p class="text-xs text-gray-400 italic">Approved tanpa TTD</p>
                            </div>
                            @endif
                            @else
                            <div class="bg-white rounded-xl border-2 border-dashed border-purple-200 min-h-[150px] flex flex-col items-center justify-center gap-2">
                                <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-gray-400 italic text-center">
                                    {{ $loanRequest->status === 'approved_kepala' ? '⏳ Menunggu Approval' : 'Belum Diproses' }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>{{-- end space-y-8 --}}
    </div>{{-- end main card --}}

    {{-- ===== KENDARAAN DITUGASKAN ===== --}}
    @if($loanRequest->assignment && $loanRequest->assignment->assignedVehicle)
    <div class="bg-white rounded-2xl overflow-hidden mb-6"
        style="box-shadow:0 4px 24px rgba(109,40,217,0.08); border:2px solid #ddd6fe;">
        <div class="flex items-center gap-3 px-6 py-4" style="background:linear-gradient(135deg,#faf5ff,#ede9fe);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                style="background:linear-gradient(135deg,#7c3aed,#8b5cf6);">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h5 class="font-extrabold text-purple-800 text-sm uppercase tracking-wider">Kendaraan yang Ditugaskan</h5>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-xl p-4" style="background:#fdf8ff; border:1px solid #ede9fe;">
                    <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-2">Kendaraan</p>
                    <p class="font-extrabold text-purple-900 text-base">
                        {{ $loanRequest->assignment->assignedVehicle->brand }}
                        {{ $loanRequest->assignment->assignedVehicle->model }}
                    </p>
                    <span class="inline-block text-xs font-mono font-bold px-2.5 py-1 rounded-lg mt-2"
                        style="background:#ede9fe; color:#6d28d9;">
                        {{ $loanRequest->assignment->assignedVehicle->plate_no }}
                    </span>
                </div>
                <div class="rounded-xl p-4" style="background:#fdf8ff; border:1px solid #ede9fe;">
                    <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-2">Kapasitas</p>
                    <p class="font-extrabold text-purple-900 text-2xl leading-none">
                        {{ $loanRequest->assignment->assignedVehicle->seat_capacity ?? '-' }}
                    </p>
                    <p class="text-xs text-purple-400 mt-1">kursi</p>
                </div>
                <div class="rounded-xl p-4" style="background:#fdf8ff; border:1px solid #ede9fe;">
                    <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-2">Di-assign Oleh</p>
                    <p class="font-extrabold text-purple-900">
                        {{ $loanRequest->assignment->assignedBy->full_name ?? 'Admin GA' }}
                    </p>
                    @if($loanRequest->assignment->assigned_at)
                    <p class="text-xs text-purple-400 mt-1">
                        {{ $loanRequest->assignment->assigned_at->format('d M Y, H:i') }} WIB
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== CATATAN PEMOHON ===== --}}
    @if($loanRequest->notes)
    <div class="bg-white rounded-2xl overflow-hidden mb-6"
        style="box-shadow:0 2px 12px rgba(234,179,8,0.08); border:2px solid #fde68a;">
        <div class="flex items-center gap-3 px-6 py-3" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);">
            <span class="text-lg">📝</span>
            <h5 class="font-extrabold text-amber-700 text-sm uppercase tracking-wider">Catatan Tambahan</h5>
        </div>
        <div class="p-5">
            <p class="text-gray-700 leading-relaxed">{{ $loanRequest->notes }}</p>
        </div>
    </div>
    @endif

    {{-- ===== CATATAN APPROVAL ===== --}}
    @if($kepalaApproval?->reason || $gaApproval?->reason)
    <div class="bg-white rounded-2xl overflow-hidden mb-6"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">
        <div class="flex items-center gap-3 px-6 py-3 border-b" style="border-color:#f0f4ff;">
            <span class="text-lg">💬</span>
            <h5 class="font-extrabold text-gray-700 text-sm uppercase tracking-wider">Catatan Approval</h5>
        </div>
        <div class="p-5 space-y-3">
            @if($kepalaApproval?->reason)
            <div class="flex gap-3 p-4 rounded-xl" style="background:#f0fdf4; border-left:4px solid #22c55e;">
                <div>
                    <p class="text-xs font-bold text-green-700 mb-1">
                        Kepala Departemen
                        @if($kepalaApproval->approver) — {{ $kepalaApproval->approver->full_name }} @endif
                    </p>
                    <p class="text-sm text-gray-700">{{ $kepalaApproval->reason }}</p>
                </div>
            </div>
            @endif
            @if($gaApproval?->reason)
            <div class="flex gap-3 p-4 rounded-xl" style="background:#faf5ff; border-left:4px solid #8b5cf6;">
                <div>
                    <p class="text-xs font-bold text-purple-700 mb-1">
                        Admin GA
                        @if($gaApproval->approver) — {{ $gaApproval->approver->full_name }} @endif
                    </p>
                    <p class="text-sm text-gray-700">{{ $gaApproval->reason }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ===== ALASAN PENOLAKAN ===== --}}
    @if($loanRequest->status === 'rejected')
    @php $rejectedApproval = $loanRequest->approvals->where('decision', 'rejected')->first(); @endphp
    @if($rejectedApproval)
    <div class="bg-white rounded-2xl overflow-hidden mb-6"
        style="box-shadow:0 2px 12px rgba(239,68,68,0.1); border:2px solid #fca5a5;">
        <div class="flex items-center gap-3 px-6 py-3" style="background:linear-gradient(135deg,#fef2f2,#fee2e2);">
            <span class="text-lg">❌</span>
            <h5 class="font-extrabold text-red-700 text-sm uppercase tracking-wider">Alasan Penolakan</h5>
        </div>
        <div class="p-5">
            <p class="text-gray-800 leading-relaxed">{{ $rejectedApproval->reason ?? 'Tidak ada keterangan.' }}</p>
            <p class="text-xs text-gray-400 mt-3 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Ditolak pada {{ $rejectedApproval->decided_at->format('d M Y, H:i') }} WIB
                @if($rejectedApproval->approver) oleh {{ $rejectedApproval->approver->full_name }} @endif
            </p>
        </div>
    </div>
    @endif
    @endif

    {{-- ===== LAMPIRAN ===== --}}
    @if($loanRequest->attachments && $loanRequest->attachments->count() > 0)
    <div class="bg-white rounded-2xl overflow-hidden mb-6"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">
        <div class="flex items-center gap-3 px-6 py-3 border-b" style="border-color:#f0f4ff;">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                style="background:linear-gradient(135deg,#0369a1,#0284c7);">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
            </div>
            <h5 class="font-extrabold text-gray-700 text-sm uppercase tracking-wider">Lampiran Dokumen</h5>
            <span class="ml-auto text-xs font-semibold px-2.5 py-0.5 rounded-full"
                style="background:#eff6ff; color:#1d4ed8;">
                {{ $loanRequest->attachments->count() }} file
            </span>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($loanRequest->attachments as $attachment)
            <a href="{{ asset('storage/' . $attachment->file_url) }}" target="_blank"
                class="group flex items-center gap-3 p-3.5 rounded-xl transition"
                style="border:1px solid #e2e8f0; background:#fafbff;">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition group-hover:scale-105"
                    style="{{ str_contains($attachment->mime_type ?? '', 'image') ? 'background:#eff6ff;' : (str_contains($attachment->mime_type ?? '', 'pdf') ? 'background:#fef2f2;' : 'background:#f8fafc;') }}">
                    @if(str_contains($attachment->mime_type ?? '', 'image'))
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    @elseif(str_contains($attachment->mime_type ?? '', 'pdf'))
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    @else
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm text-gray-800 truncate">
                        {{ $attachment->file_name ?? 'File Lampiran' }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        @if($attachment->file_size_bytes)
                        {{ number_format($attachment->file_size_bytes / 1024, 1) }} KB ·
                        @endif
                        {{ $attachment->uploaded_at->format('d/m/Y') }}
                    </p>
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== ACTION BUTTONS ===== --}}
    <div class="bg-white rounded-2xl px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">

        {{-- Back --}}
        <a href="{{ route('loan-requests.index') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl transition"
            style="background:#f1f5f9; color:#475569;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>

        <div class="flex items-center gap-2 flex-wrap">

            {{-- Download PDF --}}
            <a href="{{ route('loan-requests.pdf', $loanRequest) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white rounded-xl transition"
                style="background:linear-gradient(135deg,#dc2626,#ef4444);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                PDF
            </a>


            @if($loanRequest->status === 'submitted')
            <a href="{{ route('loan-requests.edit', $loanRequest) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white rounded-xl transition"
                style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <button
                type="button"
                @click="$dispatch('open-delete-modal', {
        label:  'pengajuan #{{ $loanRequest->id }}',
        formId: 'delete-loan-form'
    })"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white rounded-xl transition-all duration-150"
                style="background:linear-gradient(135deg,#b91c1c,#dc2626); box-shadow:0 4px 14px rgba(185,28,28,0.3);"
                onmouseover="this.style.boxShadow='0 6px 20px rgba(185,28,28,0.5)'; this.style.transform='translateY(-1px)';"
                onmouseout="this.style.boxShadow='0 4px 14px rgba(185,28,28,0.3)'; this.style.transform='translateY(0)';">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus
            </button>


            <form id="delete-loan-form"
                action="{{ route('loan-requests.destroy', $loanRequest) }}"
                method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>

            @endif

            {{-- Kembalikan — hanya saat in_use & pemilik --}}
            @if($loanRequest->status === 'in_use' && $loanRequest->requester_id === auth()->id())
            @if(!$loanRequest->vehicleReturn)
            <a href="{{ route('returns.create', $loanRequest) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white rounded-xl transition"
                style="background:linear-gradient(135deg,#6d28d9,#8b5cf6);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                </svg>
                Kembalikan Kendaraan
            </a>
            @endif
            @endif

            {{-- Detail Pengembalian — saat returned --}}
            @if($loanRequest->status === 'returned' && $loanRequest->requester_id === auth()->id())
            @php $existingReturn = $loanRequest->vehicleReturn; @endphp
            @if($existingReturn)
            <a href="{{ route('returns.show', $existingReturn) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-xl transition"
                style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Detail Pengembalian
            </a>
            @endif
            @endif

        </div>
    </div>

    {{-- ===== CUSTOM DELETE MODAL ===== --}}
<div x-data="deleteModal()" x-on:open-delete-modal.window="open($event.detail)">

    {{-- Backdrop --}}
    <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40"
        style="background:rgba(15,23,42,0.5); backdrop-filter:blur(3px);"
        x-cloak
    ></div>

    {{-- Modal Panel --}}
    <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-3"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-3"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @keydown.escape.window="isOpen = false"
        x-cloak
    >
        <div class="w-full max-w-sm rounded-2xl overflow-hidden"
            style="background:#fff; box-shadow:0 25px 60px rgba(0,0,0,0.2);"
            @click.outside="isOpen = false">

            {{-- Accent bar shimmer --}}
            <div class="h-1.5"
                style="background:linear-gradient(90deg,#ef4444,#f97316,#ef4444);
                       background-size:200%;
                       animation:shimmer 2s linear infinite;">
            </div>

            <div class="p-7">

                {{-- Icon --}}
                <div class="flex justify-center mb-5">
                    <div class="relative">
                        <div class="w-20 h-20 rounded-2xl flex items-center justify-center"
                            style="background:linear-gradient(135deg,#fef2f2,#fee2e2);">
                            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-7 h-7 rounded-full flex items-center justify-center"
                            style="background:linear-gradient(135deg,#dc2626,#ef4444); box-shadow:0 2px 8px rgba(220,38,38,0.4);">
                            <span class="text-white text-xs font-black">!</span>
                        </div>
                    </div>
                </div>

                {{-- Text --}}
                <h3 class="text-xl font-extrabold text-gray-800 text-center">Hapus Pengajuan?</h3>

                <div class="mt-3 mx-auto max-w-xs">
                    <div class="rounded-xl px-4 py-3 text-center" style="background:#fafafa; border:1px solid #f0f0f0;">
                        <p class="text-xs text-gray-400 mb-1">Yang akan dihapus</p>
                        <p class="font-extrabold text-gray-800 text-base" x-text="itemLabel"></p>
                    </div>
                    <p class="text-sm text-gray-500 text-center mt-3 leading-relaxed">
                        Data pengajuan ini akan
                        <span class="font-bold text-red-600">dihapus permanen</span>
                        dan tidak dapat dipulihkan kembali.
                    </p>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3 mt-6">
                    <button type="button" @click="isOpen = false"
                        class="flex-1 py-3 text-sm font-bold rounded-xl transition-all duration-150"
                        style="background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0;"
                        onmouseover="this.style.background='#e2e8f0';"
                        onmouseout="this.style.background='#f1f5f9';">
                        Batal
                    </button>
                    <button type="button" @click="submitForm()" x-ref="confirmBtn"
                        class="flex-1 py-3 text-sm font-bold text-white rounded-xl transition-all duration-150 flex items-center justify-center gap-2"
                        style="background:linear-gradient(135deg,#dc2626,#ef4444); box-shadow:0 4px 14px rgba(220,38,38,0.35);"
                        onmouseover="this.style.boxShadow='0 6px 20px rgba(220,38,38,0.5)'; this.style.transform='translateY(-1px)';"
                        onmouseout="this.style.boxShadow='0 4px 14px rgba(220,38,38,0.35)'; this.style.transform='translateY(0)';">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Ya, Hapus
                    </button>
                </div>

                {{-- Escape hint --}}
                <p class="text-xs text-gray-400 text-center mt-4 flex items-center justify-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tekan <kbd class="mx-1 px-1.5 py-0.5 rounded text-xs font-mono"
                        style="background:#f1f5f9; border:1px solid #e2e8f0;">Esc</kbd> untuk membatalkan
                </p>

            </div>
        </div>
    </div>
</div>

<script>
    function deleteModal() {
        return {
            isOpen:    false,
            itemLabel: '',
            formId:    '',
            open(detail) {
                this.itemLabel = detail.label;
                this.formId    = detail.formId;
                this.isOpen    = true;
                setTimeout(() => this.$refs.confirmBtn?.focus(), 250);
            },
            submitForm() {
                const form = document.getElementById(this.formId);
                if (form) form.submit();
            }
        }
    }
</script>

<style>
    @keyframes shimmer {
        0%   { background-position: 200% center; }
        100% { background-position: -200% center; }
    }
    [x-cloak] { display: none !important; }
</style>


</x-app-layout>