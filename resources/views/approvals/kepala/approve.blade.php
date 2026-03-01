<x-app-layout>
    <x-slot name="header">Review Pengajuan</x-slot>

    {{-- ===== TOP BAR ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('approvals.kepala.index') }}"
                class="flex items-center justify-center w-9 h-9 rounded-xl transition flex-shrink-0"
                style="background:#f1f5f9; color:#64748b;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-extrabold text-gray-800 leading-tight">
                    Review Pengajuan <span class="font-mono text-amber-600">#{{ $loanRequest->id }}</span>
                </h1>
                <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse inline-block"></span>
                    Approval Level: Kepala Departemen
                </p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold rounded-full flex-shrink-0"
            style="background:#fef3c7; color:#92400e; border:1px solid #fde68a;">
            ⏳ Menunggu Persetujuan Anda
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- ========================= KIRI: DETAIL ========================= --}}
        <div class="lg:col-span-3 space-y-5">

            {{-- Info Pemohon --}}
            <div class="bg-white rounded-2xl overflow-hidden"
                style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">
                <div class="px-5 py-3 border-b flex items-center gap-2" style="border-color:#f0f4ff; background:#fafbff;">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center"
                        style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xs font-extrabold text-gray-600 uppercase tracking-wider">Pemohon</h3>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-4">
                        @php
                            $initials = strtoupper(substr($loanRequest->requester->full_name ?? 'U', 0, 2));
                        @endphp
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-lg font-extrabold flex-shrink-0"
                            style="background:linear-gradient(135deg,#dbeafe,#bfdbfe); color:#1d4ed8;">
                            {{ $initials }}
                        </div>
                        <div>
                            <p class="text-lg font-extrabold text-gray-800">{{ $loanRequest->requester->full_name ?? '-' }}</p>
                            <p class="text-sm text-gray-500 mt-0.5 flex items-center gap-2 flex-wrap">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ $loanRequest->requester->unit->name ?? '-' }}
                                </span>
                                @if($loanRequest->requester->phone ?? false)
                                <span class="text-gray-300">·</span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $loanRequest->requester->phone }}
                                </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Perjalanan --}}
            <div class="bg-white rounded-2xl overflow-hidden"
                style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">
                <div class="px-5 py-3 border-b flex items-center gap-2" style="border-color:#f0f4ff; background:#fafbff;">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center"
                        style="background:linear-gradient(135deg,#0284c7,#0ea5e9);">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-xs font-extrabold text-gray-600 uppercase tracking-wider">Detail Perjalanan</h3>
                </div>
                <div class="p-5 space-y-4">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="rounded-xl p-3.5" style="background:#fafbff; border:1px solid #e8f0fe;">
                            <p class="text-xs font-bold text-blue-400 uppercase tracking-wide mb-1">Keperluan</p>
                            <p class="text-sm font-bold text-gray-800">{{ $loanRequest->purpose }}</p>
                        </div>
                        <div class="rounded-xl p-3.5" style="background:#fafbff; border:1px solid #e8f0fe;">
                            <p class="text-xs font-bold text-blue-400 uppercase tracking-wide mb-1">Tujuan</p>
                            <p class="text-sm text-gray-700">{{ $loanRequest->destination ?? '-' }}</p>
                        </div>
                        <div class="rounded-xl p-3.5" style="background:#fafbff; border:1px solid #e8f0fe;">
                            <p class="text-xs font-bold text-blue-400 uppercase tracking-wide mb-1">Anggaran</p>
                            <p class="text-sm text-gray-700">{{ $loanRequest->anggaran_awal ?? '-' }}</p>
                        </div>
                        <!-- projek -->
                        <div class="rounded-xl p-3.5" style="background:#fafbff; border:1px solid #e8f0fe;">
                            <p class="text-xs font-bold text-blue-400 uppercase tracking-wide mb-1">Projek</p>
                            <p class="text-sm text-gray-700">{{ $loanRequest->projek ?? '-' }}</p>
                        </div>
                        <div class="rounded-xl p-3.5" style="background:#fdf8ff; border:1px solid #ede9fe;">
                            <p class="text-xs font-bold text-purple-400 uppercase tracking-wide mb-1">Berangkat</p>
                            <p class="text-sm font-bold text-gray-800">{{ $loanRequest->depart_at?->format('d M Y') ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $loanRequest->depart_at?->format('H:i') ?? '' }} WIB</p>
                        </div>
                        <div class="rounded-xl p-3.5" style="background:#fdf8ff; border:1px solid #ede9fe;">
                            <p class="text-xs font-bold text-purple-400 uppercase tracking-wide mb-1">Rencana Kembali</p>
                            <p class="text-sm font-bold text-gray-800">{{ $loanRequest->expected_return_at?->format('d M Y') ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $loanRequest->expected_return_at?->format('H:i') ?? '' }} WIB</p>
                        </div>
                    </div>

                    {{-- Durasi --}}
                    @if($loanRequest->depart_at && $loanRequest->expected_return_at)
                    <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-sm"
                        style="background:#f0fdf4; border:1px solid #bbf7d0;">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-green-700 font-semibold">
                            Durasi:
                            <span class="font-extrabold">
                                {{ $loanRequest->depart_at->diffForHumans($loanRequest->expected_return_at, true) }}
                            </span>
                        </span>
                    </div>
                    @endif

                    {{-- Lokasi siap & kembali --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl p-3.5" style="background:#fafbff; border:1px solid #e8f0fe;">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                Berangkat dari
                            </p>
                            <p class="text-sm font-semibold text-gray-700">{{ $loanRequest->siap_di ?? '-' }}</p>
                        </div>
                        <div class="rounded-xl p-3.5" style="background:#fafbff; border:1px solid #e8f0fe;">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                Kembali ke
                            </p>
                            <p class="text-sm font-semibold text-gray-700">{{ $loanRequest->kembali_di ?? '-' }}</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Kendaraan --}}
            <div class="bg-white rounded-2xl overflow-hidden"
                style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">
                <div class="px-5 py-3 border-b flex items-center gap-2" style="border-color:#f0f4ff; background:#fafbff;">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center"
                        style="background:linear-gradient(135deg,#7c3aed,#8b5cf6);">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xs font-extrabold text-gray-600 uppercase tracking-wider">Preferensi Kendaraan</h3>
                </div>
                <div class="p-5">
                    @if($loanRequest->preferredVehicle)
                    <div class="flex items-start gap-4 p-4 rounded-xl" style="background:#fdf8ff; border:1px solid #ede9fe;">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 text-2xl"
                            style="background:#ede9fe;">🚗</div>
                        <div>
                            <p class="font-extrabold text-purple-900 text-base">
                                {{ $loanRequest->preferredVehicle->brand }}
                                {{ $loanRequest->preferredVehicle->model }}
                            </p>
                            <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-lg"
                                    style="background:#ede9fe; color:#6d28d9;">
                                    {{ $loanRequest->preferredVehicle->plate_no }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $loanRequest->preferredVehicle->seat_capacity }} kursi
                                </span>
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full
                                    {{ $loanRequest->preferredVehicle->status === 'available'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-red-100 text-red-600' }}">
                                    {{ ucfirst($loanRequest->preferredVehicle->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @elseif($loanRequest->requested_vehicle_text)
                    <p class="text-sm text-gray-700">{{ $loanRequest->requested_vehicle_text }}</p>
                    @else
                    <p class="text-sm text-gray-400 italic">Tidak ada preferensi kendaraan</p>
                    @endif
                </div>
            </div>

            {{-- Catatan + Lampiran + TTD (opsional) --}}
            @if($loanRequest->notes || ($loanRequest->attachments && $loanRequest->attachments->count() > 0) || $loanRequest->requester_signature)
            <div class="bg-white rounded-2xl overflow-hidden"
                style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">
                <div class="px-5 py-3 border-b flex items-center gap-2" style="border-color:#f0f4ff; background:#fafbff;">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center"
                        style="background:linear-gradient(135deg,#d97706,#f59e0b);">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                    </div>
                    <h3 class="text-xs font-extrabold text-gray-600 uppercase tracking-wider">Catatan & Lampiran</h3>
                </div>
                <div class="p-5 space-y-4">
                    @if($loanRequest->notes)
                    <div class="p-4 rounded-xl" style="background:#fffbeb; border:1px solid #fde68a;">
                        <p class="text-xs font-bold text-amber-600 mb-1.5">Catatan Pemohon</p>
                        <p class="text-sm text-amber-800 leading-relaxed">{{ $loanRequest->notes }}</p>
                    </div>
                    @endif

                    @if($loanRequest->requester_signature)
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Tanda Tangan Pemohon</p>
                        <div class="inline-block rounded-xl border-2 border-blue-100 p-2 bg-white">
                            <img src="{{ Storage::url($loanRequest->requester_signature) }}"
                                alt="TTD Pemohon" class="h-20 object-contain">
                        </div>
                    </div>
                    @endif

                    @if($loanRequest->attachments && $loanRequest->attachments->count() > 0)
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">
                            Lampiran ({{ $loanRequest->attachments->count() }})
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($loanRequest->attachments as $att)
                            <a href="{{ Storage::url($att->file_url) }}" target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-xl transition"
                                style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0;"
                                onmouseover="this.style.background='#e2e8f0';"
                                onmouseout="this.style.background='#f1f5f9';">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                {{ $att->file_name ?? 'Lampiran' }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>

        {{-- ========================= KANAN: FORM KEPUTUSAN ========================= --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl overflow-hidden sticky top-6"
                style="box-shadow:0 4px 24px rgba(0,0,0,0.08); border:1px solid #f0f4ff;">

                {{-- Header --}}
                <div class="px-5 py-4 text-center" style="background:linear-gradient(135deg,#fffbeb,#fef3c7); border-bottom:1px solid #fde68a;">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mx-auto mb-2"
                        style="background:linear-gradient(135deg,#d97706,#f59e0b);">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-extrabold text-amber-900 text-base">Keputusan Anda</h3>
                    <p class="text-xs text-amber-600 mt-0.5">
                        TTD <span class="font-bold text-red-500">wajib</span> diisi sebelum menyetujui
                    </p>
                </div>

                <div class="p-5 space-y-5">

                    {{-- ===== SIGNATURE PAD ===== --}}
                    <div>
                        <label class="block text-xs font-extrabold text-gray-600 uppercase tracking-wide mb-2">
                            Tanda Tangan Anda <span class="text-red-500">*</span>
                        </label>

                        <div id="signatureWrapper"
                            class="rounded-2xl p-2 transition-all duration-200"
                            style="border:2px dashed #cbd5e1; background:#f8fafc;">

                            <canvas id="signatureCanvas"
                                class="w-full rounded-xl block"
                                style="height:160px; background:#fff; touch-action:none;">
                            </canvas>

                            <div class="flex items-center justify-between mt-2 px-1">
                                <span id="signatureStatus" class="text-xs text-gray-400 italic flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    Tanda tangani di kotak atas
                                </span>
                                <button type="button" id="clearSignature"
                                    class="text-xs font-bold flex items-center gap-1 transition-colors"
                                    style="color:#ef4444;"
                                    onmouseover="this.style.color='#b91c1c';"
                                    onmouseout="this.style.color='#ef4444';">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </div>

                        <div id="signatureError"
                            class="hidden mt-2 text-xs text-red-600 font-semibold flex items-center gap-1 p-2 rounded-lg"
                            style="background:#fef2f2; border:1px solid #fecaca;">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Tanda tangan wajib diisi sebelum menyetujui!
                        </div>
                    </div>

                    {{-- ===== FORM SETUJUI ===== --}}
                    <form action="{{ route('approvals.kepala.approve', $loanRequest) }}"
                        method="POST" id="approveForm">
                        @csrf
                        <input type="hidden" name="signature" id="signatureInput">
                        <button type="submit" id="approveBtn"
                            class="w-full py-3.5 text-sm font-extrabold text-white rounded-xl transition-all duration-150 flex items-center justify-center gap-2"
                            style="background:linear-gradient(135deg,#16a34a,#22c55e); box-shadow:0 4px 14px rgba(22,163,74,0.3);"
                            onmouseover="this.style.boxShadow='0 6px 20px rgba(22,163,74,0.45)'; this.style.transform='translateY(-1px)';"
                            onmouseout="this.style.boxShadow='0 4px 14px rgba(22,163,74,0.3)'; this.style.transform='translateY(0)';">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            SETUJUI Pengajuan Ini
                        </button>
                    </form>

                    {{-- Divider --}}
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full" style="border-top:1px dashed #e2e8f0;"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-3 text-xs text-gray-400 bg-white">atau</span>
                        </div>
                    </div>

                    {{-- ===== FORM TOLAK ===== --}}
                    <div x-data="{ open: false }">

                        <button type="button" @click="open = !open"
                            class="w-full py-3.5 text-sm font-extrabold rounded-xl transition-all duration-150 flex items-center justify-center gap-2"
                            style="background:#fff; color:#dc2626; border:2px solid #fca5a5;"
                            onmouseover="this.style.background='#fef2f2'; this.style.borderColor='#ef4444';"
                            onmouseout="this.style.background='#fff'; this.style.borderColor='#fca5a5';">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span x-text="open ? 'Tutup Form Penolakan' : 'TOLAK Pengajuan Ini'"></span>
                        </button>

                        {{-- Reject form panel --}}
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="mt-3"
                        >
                            <form action="{{ route('approvals.kepala.reject', $loanRequest) }}" method="POST">
                                @csrf
                                <div class="rounded-2xl p-4 space-y-3" style="background:#fef2f2; border:2px solid #fca5a5;">

                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0"
                                            style="background:linear-gradient(135deg,#dc2626,#ef4444);">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </div>
                                        <label class="text-xs font-extrabold text-red-700 uppercase tracking-wide">
                                            Alasan Penolakan <span class="text-red-500">*</span>
                                        </label>
                                    </div>

                                    <textarea name="reason" rows="4" required
                                        placeholder="Tuliskan alasan penolakan dengan jelas dan spesifik..."
                                        class="w-full text-sm rounded-xl px-3.5 py-3 resize-none focus:outline-none focus:ring-2 transition"
                                        style="border:1px solid #fca5a5; background:#fff; focus-ring-color:#ef4444;">{{ old('reason') }}</textarea>

                                    @error('reason')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                    @enderror

                                    <button type="submit"
                                        class="w-full py-3 text-sm font-extrabold text-white rounded-xl transition-all duration-150 flex items-center justify-center gap-2"
                                        style="background:linear-gradient(135deg,#b91c1c,#dc2626); box-shadow:0 4px 14px rgba(185,28,28,0.3);"
                                        onmouseover="this.style.boxShadow='0 6px 20px rgba(185,28,28,0.45)'; this.style.transform='translateY(-1px)';"
                                        onmouseout="this.style.boxShadow='0 4px 14px rgba(185,28,28,0.3)'; this.style.transform='translateY(0)';">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Konfirmasi Penolakan
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- ===== SIGNATURE PAD SCRIPT ===== --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas           = document.getElementById('signatureCanvas');
            const signatureInput   = document.getElementById('signatureInput');
            const clearBtn         = document.getElementById('clearSignature');
            const signatureStatus  = document.getElementById('signatureStatus');
            const signatureError   = document.getElementById('signatureError');
            const signatureWrapper = document.getElementById('signatureWrapper');
            const approveForm      = document.getElementById('approveForm');
            const approveBtn       = document.getElementById('approveBtn');

            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255,255,255)',
                penColor: 'rgb(0,0,0)',
                minWidth: 1,
                maxWidth: 3,
                velocityFilterWeight: 0.7
            });

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const rect  = canvas.getBoundingClientRect();
                const data  = signaturePad.toData();
                canvas.width        = rect.width  * ratio;
                canvas.height       = rect.height * ratio;
                canvas.style.width  = rect.width  + 'px';
                canvas.style.height = rect.height + 'px';
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePad.clear();
                if (data && data.length > 0) signaturePad.fromData(data);
            }

            setTimeout(resizeCanvas, 100);
            window.addEventListener('resize', resizeCanvas);

            function updateStatus() {
                if (!signaturePad.isEmpty()) {
                    signatureStatus.innerHTML = `<svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> <span class="text-green-600 font-semibold">TTD sudah dibuat</span>`;
                    signatureInput.value = signaturePad.toDataURL('image/png');
                    signatureError.classList.add('hidden');
                    signatureWrapper.style.borderColor = '#22c55e';
                    signatureWrapper.style.borderStyle = 'solid';
                    signatureWrapper.style.background  = '#f0fdf4';
                } else {
                    signatureStatus.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg> Tanda tangani di kotak atas`;
                    signatureInput.value = '';
                    signatureWrapper.style.borderColor = '#cbd5e1';
                    signatureWrapper.style.borderStyle = 'dashed';
                    signatureWrapper.style.background  = '#f8fafc';
                }
            }

            signaturePad.addEventListener('endStroke', updateStatus);

            clearBtn.addEventListener('click', function () {
                signaturePad.clear();
                signatureError.classList.add('hidden');
                updateStatus();
            });

            let isSubmitting = false;

            approveForm.addEventListener('submit', function (e) {
                if (isSubmitting) { e.preventDefault(); return; }

                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    signatureError.classList.remove('hidden');
                    signatureWrapper.style.borderColor = '#ef4444';
                    signatureWrapper.style.borderStyle = 'solid';
                    signatureWrapper.style.background  = '#fef2f2';
                    canvas.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    signatureWrapper.style.animation = 'shake 0.5s';
                    setTimeout(() => signatureWrapper.style.animation = '', 500);
                    return;
                }

                signatureInput.value = signaturePad.toDataURL('image/png');
                isSubmitting = true;
                approveBtn.disabled = true;
                approveBtn.style.opacity = '0.75';
                approveBtn.style.cursor  = 'not-allowed';
                approveBtn.innerHTML = `
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                `;
            });
        });
    </script>

    <style>
        @keyframes shake {
            0%,100% { transform: translateX(0); }
            10%,30%,50%,70%,90% { transform: translateX(-5px); }
            20%,40%,60%,80%     { transform: translateX(5px); }
        }
        #signatureCanvas { display:block !important; touch-action:none !important; user-select:none !important; }
        [x-cloak] { display:none !important; }
    </style>

</x-app-layout>