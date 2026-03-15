<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('approvals.hr.index') }}"
                   class="p-2 rounded-lg hover:bg-gray-100 transition text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Verifikasi Pengajuan #{{ $loanRequest->id }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-0.5">Approval Level: Admin HR</p>
                </div>
            </div>
            <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                ✅ Sudah Disetujui Kepala — Menunggu HR
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ========================= INFO APPROVAL KEPALA ========================= --}}
            @php
                $approvalKepala = $loanRequest->approvals->where('approval_level', 'kepala')->first();
            @endphp
            @if($approvalKepala)
            <div class="bg-green-50 border border-green-200 rounded-xl px-5 py-4 flex items-start gap-4">
                <div class="p-2 bg-green-100 rounded-full flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-green-800">Sudah disetujui oleh Kepala Departemen</p>
                    <p class="text-xs text-green-700 mt-0.5">
                        Oleh: <span class="font-semibold">{{ $approvalKepala->approver->full_name ?? '-' }}</span>
                        · {{ $approvalKepala->decided_at?->format('d M Y, H:i') ?? '-' }}
                    </p>
                    @if($approvalKepala->approver_signature)
                    <div class="mt-2">
                        <p class="text-xs text-green-600 mb-1">TTD Kepala:</p>
                        <div class="inline-block border border-green-200 rounded-lg p-1 bg-white">
                            <img src="{{ Storage::url($approvalKepala->approver_signature) }}"
                                 alt="TTD Kepala" class="h-14 object-contain">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- ========================= DETAIL PENGAJUAN ========================= --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Detail Pengajuan</h3>
                </div>
                <div class="p-6 space-y-5">

                    {{-- Info Pemohon --}}
                    <div class="flex items-start gap-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="p-2 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-blue-500 font-medium">Pemohon</p>
                            <p class="text-base font-bold text-blue-800">{{ $loanRequest->requester->full_name ?? '-' }}</p>
                            <p class="text-sm text-blue-600">
                                {{ $loanRequest->requester->unit->name ?? '-' }}
                                · {{ $loanRequest->requester->phone ?? '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Detail Perjalanan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Keperluan</p>
                                <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $loanRequest->purpose }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Tujuan Perjalanan</p>
                                <p class="text-sm text-gray-800 mt-0.5">{{ $loanRequest->destination ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Kota Pengajuan</p>
                                <p class="text-sm text-gray-800 mt-0.5">{{ $loanRequest->request_city ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Anggaran Awal</p>
                                <p class="text-sm font-semibold text-gray-800 mt-0.5">
                                    {{ $loanRequest->anggaran_awal ? 'Rp ' . number_format($loanRequest->anggaran_awal, 0, ',', '.') : '-' }}
                                </p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Waktu Berangkat</p>
                                <p class="text-sm font-semibold text-gray-800 mt-0.5">
                                    {{ $loanRequest->depart_at?->format('d M Y, H:i') ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Rencana Kembali</p>
                                <p class="text-sm font-semibold text-gray-800 mt-0.5">
                                    {{ $loanRequest->expected_return_at?->format('d M Y, H:i') ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Durasi</p>
                                @if($loanRequest->depart_at && $loanRequest->expected_return_at)
                                    <p class="text-sm text-gray-800 mt-0.5">
                                        {{ $loanRequest->depart_at->diffForHumans($loanRequest->expected_return_at, true) }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-400 mt-0.5">-</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Lokasi Siap & Kembali --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">📍 Berangkat dari</p>
                            <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $loanRequest->siap_di ?? '-' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">📍 Kembali ke</p>
                            <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $loanRequest->kembali_di ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Preferensi Kendaraan --}}
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-2">Preferensi Kendaraan</p>
                        @if($loanRequest->preferredVehicle)
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🚗</span>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">
                                        {{ $loanRequest->preferredVehicle->brand }}
                                        {{ $loanRequest->preferredVehicle->model }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Plat: <span class="font-mono font-semibold">{{ $loanRequest->preferredVehicle->plate_no }}</span>
                                        · Kapasitas: {{ $loanRequest->preferredVehicle->seat_capacity }} orang
                                        · Status:
                                        <span class="{{ $loanRequest->preferredVehicle->status === 'available' ? 'text-green-600' : 'text-red-500' }} font-semibold">
                                            {{ ucfirst($loanRequest->preferredVehicle->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        @elseif($loanRequest->requested_vehicle_text)
                            <p class="text-sm text-gray-700">{{ $loanRequest->requested_vehicle_text }}</p>
                        @else
                            <p class="text-sm text-gray-400 italic">Tidak ada preferensi kendaraan</p>
                        @endif
                    </div>

                    {{-- Catatan --}}
                    @if($loanRequest->notes)
                    <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-xs text-yellow-600 font-medium mb-1">📝 Catatan Pemohon:</p>
                        <p class="text-sm text-yellow-800">{{ $loanRequest->notes }}</p>
                    </div>
                    @endif

                    {{-- TTD Pemohon --}}
                    @if($loanRequest->requester_signature)
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-2">Tanda Tangan Pemohon</p>
                        <div class="inline-block border border-gray-200 rounded-lg p-2 bg-white">
                            <img src="{{ Storage::url($loanRequest->requester_signature) }}"
                                 alt="TTD Pemohon" class="h-20 object-contain">
                        </div>
                    </div>
                    @endif

                    {{-- Lampiran --}}
                    @if($loanRequest->attachments && $loanRequest->attachments->count() > 0)
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-2">
                            Lampiran ({{ $loanRequest->attachments->count() }})
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($loanRequest->attachments as $att)
                            <a href="{{ Storage::url($att->file_url) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200
                                      text-gray-700 text-xs font-medium rounded-lg transition">
                                📎 {{ $att->file_name ?? 'Lampiran' }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            {{-- ========================= FORM KEPUTUSAN HR ========================= --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Keputusan Admin HR</h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Tanda tangan <span class="text-red-500 font-semibold">wajib</span> diisi sebelum menyetujui.
                        Setelah disetujui, pengajuan diteruskan ke Admin GA.
                    </p>
                </div>
                <div class="p-6 space-y-5">

                    {{-- ===== SIGNATURE PAD ===== --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Tanda Tangan Anda <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-3">
                            Tanda tangani di dalam kotak di bawah ini menggunakan mouse atau sentuhan
                        </p>

                        <div id="signatureWrapper"
                             class="border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 p-3">
                            <canvas id="signatureCanvas"
                                    class="w-full rounded-lg border border-gray-200 bg-white block"
                                    style="height: 180px;">
                            </canvas>
                            <div class="flex items-center justify-between mt-2 px-1">
                                <span id="signatureStatus" class="text-sm text-gray-500 italic">
                                    Belum ada tanda tangan
                                </span>
                                <button type="button" id="clearSignature"
                                        class="text-xs text-red-500 hover:text-red-700 font-semibold
                                               flex items-center gap-1 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </div>

                        <p id="signatureError"
                           class="hidden mt-2 text-sm text-red-600 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Tanda tangan wajib diisi sebelum menyetujui!
                        </p>
                    </div>

                    {{-- FORM SETUJUI --}}
                    <form action="{{ route('approvals.hr.approve', $loanRequest) }}"
                          method="POST" id="approveForm">
                        @csrf
                        <input type="hidden" name="signature" id="signatureInput">
                        <button type="submit" id="approveBtn"
                                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold
                                       rounded-xl text-sm transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            ✅ VERIFIKASI & TERUSKAN ke Admin GA
                        </button>
                    </form>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-3 bg-white text-gray-400">atau</span>
                        </div>
                    </div>

                    {{-- FORM TOLAK --}}
                    <div x-data="{ open: false }">
                        <button type="button" @click="open = !open"
                                class="w-full py-3 bg-white hover:bg-red-50 text-red-600 font-bold
                                       rounded-xl text-sm border-2 border-red-200 hover:border-red-400
                                       transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            ❌ TOLAK Pengajuan Ini
                        </button>

                        <div x-show="open" x-transition class="mt-3">
                            <form action="{{ route('approvals.hr.reject', $loanRequest) }}" method="POST">
                                @csrf
                                <div class="p-4 bg-red-50 border border-red-200 rounded-xl space-y-3">
                                    <label class="block text-sm font-semibold text-red-700">
                                        Alasan Penolakan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="reason" rows="3" required
                                              placeholder="Tulis alasan penolakan dengan jelas..."
                                              class="w-full text-sm border border-red-300 rounded-lg px-3 py-2
                                                     focus:ring-2 focus:ring-red-300 focus:border-red-400 resize-none">{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <p class="text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                    <button type="submit"
                                            class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white
                                                   font-semibold rounded-lg text-sm transition">
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

    {{-- ✅ INLINE SCRIPT — persis seperti loan/create.blade.php, BUKAN @push --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ==================== SIGNATURE PAD ====================
            const canvas           = document.getElementById('signatureCanvas');
            const signatureInput   = document.getElementById('signatureInput');
            const clearBtn         = document.getElementById('clearSignature');
            const signatureStatus  = document.getElementById('signatureStatus');
            const signatureError   = document.getElementById('signatureError');
            const signatureWrapper = document.getElementById('signatureWrapper');
            const approveForm      = document.getElementById('approveForm');
            const approveBtn       = document.getElementById('approveBtn');

            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)',
                minWidth: 1,
                maxWidth: 3,
                velocityFilterWeight: 0.7
            });

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const rect  = canvas.getBoundingClientRect();
                const data  = signaturePad.toData();

                canvas.width        = rect.width * ratio;
                canvas.height       = rect.height * ratio;
                canvas.style.width  = rect.width + 'px';
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
                    signatureStatus.className   = 'text-sm text-green-600 font-semibold';
                    signatureInput.value        = signaturePad.toDataURL('image/png');
                    signatureError.classList.add('hidden');
                    signatureWrapper.classList.remove('border-red-400', 'border-dashed', 'border-gray-300');
                    signatureWrapper.classList.add('border-green-400');
                } else {
                    signatureStatus.textContent = 'Belum ada tanda tangan';
                    signatureStatus.className   = 'text-sm text-gray-500 italic';
                    signatureInput.value        = '';
                    signatureWrapper.classList.remove('border-green-400');
                    signatureWrapper.classList.add('border-dashed', 'border-gray-300');
                }
            }

            signaturePad.addEventListener('endStroke', updateSignatureStatus);

            clearBtn.addEventListener('click', function () {
                signaturePad.clear();
                signatureStatus.textContent = 'Belum ada tanda tangan';
                signatureStatus.className   = 'text-sm text-gray-500 italic';
                signatureInput.value        = '';
                signatureError.classList.add('hidden');
                signatureWrapper.classList.remove('border-green-400', 'border-red-400');
                signatureWrapper.classList.add('border-dashed', 'border-gray-300');
            });

            // ==================== FORM SUBMIT ====================
            let isSubmitting = false;

            approveForm.addEventListener('submit', function (e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }

                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    signatureError.classList.remove('hidden');
                    signatureWrapper.classList.add('border-red-400');
                    signatureWrapper.classList.remove('border-dashed', 'border-gray-300', 'border-green-400');
                    canvas.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    signatureWrapper.style.animation = 'shake 0.5s';
                    setTimeout(() => signatureWrapper.style.animation = '', 500);
                    return false;
                }

                signatureInput.value = signaturePad.toDataURL('image/png');

                isSubmitting = true;
                approveBtn.disabled = true;
                approveBtn.innerHTML = `
                    <svg class="animate-spin w-5 h-5 mr-2 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                `;
                approveBtn.classList.add('opacity-75', 'cursor-not-allowed');
            });
        });
    </script>

    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        #signatureCanvas {
            display: block !important;
            touch-action: none !important;
            user-select: none !important;
        }
    </style>

</x-app-layout>
