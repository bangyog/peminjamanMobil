<x-app-layout>
    <x-slot name="header">Detail Pengajuan</x-slot>

    @php
    $statusConfig = [
    'submitted' => ['label' => 'Menunggu Persetujuan', 'icon' => '⏳', 'bg' => '#fffbeb', 'border' => '#fde68a', 'text' => '#92400e', 'dot' => 'bg-yellow-400'],
    'approved_kepala' => ['label' => 'Disetujui Kepala', 'icon' => '✅', 'bg' => '#eff6ff', 'border' => '#bfdbfe', 'text' => '#1e40af', 'dot' => 'bg-blue-400'],
    'approved_ga' => ['label' => 'Disetujui GA', 'icon' => '✅', 'bg' => '#eef2ff', 'border' => '#c7d2fe', 'text' => '#3730a3', 'dot' => 'bg-indigo-400'],
    'assigned' => ['label' => 'Kendaraan Ditugaskan', 'icon' => '🚗', 'bg' => '#faf5ff', 'border' => '#ddd6fe', 'text' => '#5b21b6', 'dot' => 'bg-purple-400'],
    'in_use' => ['label' => 'Sedang Digunakan', 'icon' => '🚦', 'bg' => '#f0fdf4', 'border' => '#bbf7d0', 'text' => '#15803d', 'dot' => 'bg-green-400'],
    'returned' => ['label' => 'Sudah Dikembalikan', 'icon' => '🏁', 'bg' => '#f8fafc', 'border' => '#e2e8f0', 'text' => '#475569', 'dot' => 'bg-slate-400'],
    'rejected' => ['label' => 'Ditolak', 'icon' => '❌', 'bg' => '#fef2f2', 'border' => '#fecaca', 'text' => '#991b1b', 'dot' => 'bg-red-400'],
    ];
    $sc = $statusConfig[$loanRequest->status] ?? ['label' => strtoupper($loanRequest->status), 'icon' => '📋', 'bg' => '#f8fafc', 'border' => '#e2e8f0', 'text' => '#475569', 'dot' => 'bg-gray-400'];
    @endphp

    {{-- ===== PAGE HERO BAR ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-gray-800 leading-tight">Detail Pengajuan</h1>
            <p class="text-sm text-gray-400 mt-0.5">
                Pemohon:
                <span class="font-semibold text-gray-600">
                    {{ $loanRequest->requester->full_name ?? '-' }}
                </span>
            </p>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            {{-- Status badge --}}
            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-extrabold rounded-full"
                style="background:{{ $sc['bg'] }}; color:{{ $sc['text'] }}; border:1.5px solid {{ $sc['border'] }};">
                <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}
                    {{ in_array($loanRequest->status, ['submitted','in_use']) ? 'animate-pulse' : '' }}
                    inline-block"></span>
                {{ $sc['icon'] }} {{ $sc['label'] }}
            </span>
            {{-- Tombol kembali --}}
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl transition-all"
                style="background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0;"
                onmouseover="this.style.background='#e2e8f0';"
                onmouseout="this.style.background='#f1f5f9';">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <ul class="list-disc list-inside text-sm text-red-800">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="text-center border-b-2 border-gray-800 pb-4 mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">PT. SWA</h3>
                        <h4 class="text-xl font-semibold text-gray-700 mt-2">PERMINTAAN PEMINJAMAN KENDARAAN DINAS</h4>
                        <p class="text-sm text-gray-600 mt-2">{{ $loanRequest->created_at->format('d F Y') }}</p>
                    </div>

                    {{-- Informasi Pemohon --}}
                    <div class="mb-6">
                        <h5 class="font-bold text-lg mb-3 bg-gray-100 px-4 py-2 rounded">📋 Informasi Pemohon</h5>
                        <div class="grid grid-cols-2 gap-4 px-4">
                            <div>
                                <p class="text-sm text-gray-600">Nama Pemakai</p>
                                <p class="font-semibold text-lg">{{ $loanRequest->requester->full_name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Unit</p>
                                <p class="font-semibold">{{ $loanRequest->unit->name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Keperluan & Tujuan --}}
                    <div class="mb-6 px-4">
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 font-semibold">Keperluan:</p>
                            <p class="text-gray-800 border-b border-gray-300 pb-2">{{ $loanRequest->purpose ?? '-' }}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 font-semibold">Tujuan:</p>
                            <p class="text-gray-800 border-b border-gray-300 pb-2">{{ $loanRequest->destination ?? '-' }}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 font-semibold">Projek:</p>
                            <p class="text-gray-800 border-b border-gray-300 pb-2">{{ $loanRequest->projek ?? '-' }}</p>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-600 font-semibold">Anggaran Awal:</p>
                            <p class="text-gray-800 border-b border-gray-300 pb-2">
                                {{ $loanRequest->anggaran_awal ? 'Rp ' . number_format($loanRequest->anggaran_awal, 0, ',', '.') : '-' }}
                            </p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 font-semibold">Kendaraan yang Diminta:</p>
                                <p class="text-gray-800">
                                    @if($loanRequest->vehicle)
                                    {{ $loanRequest->vehicle->brand }} {{ $loanRequest->vehicle->model }}
                                    @elseif($loanRequest->requested_vehicle_text)
                                    {{ $loanRequest->requested_vehicle_text }}
                                    @else
                                    <span class="text-gray-500 italic">Tidak ada preferensi</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Jadwal Perjalanan --}}
                    <div class="mb-6 px-4">
                        <h5 class="font-bold text-lg mb-3 bg-gray-100 px-4 py-2 rounded -mx-4">🚗 Jadwal Perjalanan</h5>
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <p class="text-sm text-gray-600 font-semibold">Siap di:</p>
                                <p class="text-gray-800 border-b border-gray-300 pb-2">{{ $loanRequest->siap_di ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-semibold">Jam & Tanggal Berangkat:</p>
                                <p class="text-gray-800 border-b border-gray-300 pb-2">
                                    {{ $loanRequest->depart_at ? $loanRequest->depart_at->format('d M Y, H:i') . ' WIB' : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-semibold">Kembali di:</p>
                                <p class="text-gray-800 border-b border-gray-300 pb-2">{{ $loanRequest->kembali_di ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-semibold">Jam & Tanggal Kembali:</p>
                                <p class="text-gray-800 border-b border-gray-300 pb-2">
                                    {{ $loanRequest->expected_return_at ? $loanRequest->expected_return_at->format('d M Y, H:i') . ' WIB' : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Tanda Tangan --}}
                    <div class="mb-6">
                        <h5 class="font-bold text-lg mb-3 bg-gray-100 px-4 py-2 rounded">✍️ Tanda Tangan</h5>
                        <div class="grid grid-cols-2 gap-6 px-4">
                            {{-- TTD PEMINJAM --}}
                            <div class="border-2 border-blue-200 rounded-lg p-4 bg-blue-50">
                                <h6 class="font-bold text-blue-800 mb-3">✈️ PEMINJAM</h6>
                                @if($loanRequest->requester_signature)
                                <div class="mt-2 pt-3 border-t border-blue-300">
                                    <p class="text-xs text-gray-600 mb-2">Paraf Pemakai:</p>
                                    <div class="bg-white p-2 rounded border border-blue-300 flex justify-center">
                                        <img src="{{ asset('storage/' . $loanRequest->requester_signature) }}"
                                            alt="TTD Pemakai" class="h-20 object-contain"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<p class=\'text-gray-500 text-xs italic\'>TTD tidak dapat dimuat</p>';">
                                    </div>
                                </div>
                                @else
                                <div class="mt-4 pt-3 border-t border-blue-300">
                                    <p class="text-xs text-gray-500 italic text-center">Tidak ada tanda tangan</p>
                                </div>
                                @endif
                            </div>

                            {{-- TTD APPROVAL --}}
                            <div class="border-2 border-green-200 rounded-lg p-4 bg-green-50">
                                <h6 class="font-bold text-green-800 mb-3">✅ APPROVAL</h6>
                                @php
                                $latestApproval = $loanRequest->approvals->where('decision', 'approved')->last();
                                @endphp
                                @if($latestApproval)
                                <div class="space-y-2 mb-3">
                                    <div>
                                        <p class="text-xs text-gray-600">Oleh:</p>
                                        <p class="font-semibold text-gray-800">
                                            {{ $latestApproval->approver->full_name ?? 'Admin GA' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600">Tanggal:</p>
                                        <p class="font-semibold text-gray-800">
                                            {{ $latestApproval->decided_at ? \Carbon\Carbon::parse($latestApproval->decided_at)->format('d M Y, H:i') . ' WIB' : '-' }}
                                        </p>
                                    </div>
                                </div>
                                @if($latestApproval->approver_signature)
                                <div class="mt-2 pt-3 border-t border-green-300">
                                    <p class="text-xs text-gray-600 mb-2">Paraf Approver:</p>
                                    <div class="bg-white p-2 rounded border border-green-300 flex justify-center">
                                        <img src="{{ asset('storage/' . $latestApproval->approver_signature) }}"
                                            alt="TTD Approver" class="h-20 object-contain"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<p class=\'text-gray-500 text-xs italic\'>TTD tidak dapat dimuat</p>';">
                                    </div>
                                </div>
                                @else
                                <div class="mt-4 pt-3 border-t border-green-300">
                                    <p class="text-xs text-gray-500 italic text-center">Approval tanpa tanda tangan</p>
                                </div>
                                @endif
                                @else
                                <div class="flex items-center justify-center h-24">
                                    <p class="text-sm text-gray-500 italic text-center">Belum ada approval</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Kendaraan yang Ditugaskan --}}
                    @if($loanRequest->assignment && $loanRequest->assignment->assignedVehicle)
                    <div class="mb-6">
                        <h5 class="font-bold text-lg mb-3 bg-purple-100 px-4 py-2 rounded">🚙 Kendaraan yang Diperintahkan</h5>
                        <div class="px-4">
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Dengan kendaraan:</p>
                                        <p class="font-bold text-lg">
                                            {{ $loanRequest->assignment->assignedVehicle->brand ?? '' }}
                                            {{ $loanRequest->assignment->assignedVehicle->model ?? '' }}
                                            ({{ $loanRequest->assignment->assignedVehicle->plate_no ?? '' }})
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Di-assign oleh:</p>
                                        <p class="font-bold text-lg">
                                            {{ $loanRequest->assignment->assignedBy->full_name ?? 'Admin GA' }}
                                        </p>
                                        @if($loanRequest->assignment->assigned_at)
                                        <p class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($loanRequest->assignment->assigned_at)->diffForHumans() }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Informasi Approval --}}
                    @if($loanRequest->approvals->isNotEmpty())
                    <div class="mb-6">
                        <h5 class="font-bold text-lg mb-3 bg-gray-100 px-4 py-2 rounded">ℹ️ Informasi Approval</h5>
                        <div class="px-4">
                            @foreach($loanRequest->approvals as $approval)
                            <div class="border-l-4 {{ $approval->decision === 'approved' ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50' }} p-4 rounded-r-lg {{ !$loop->last ? 'mb-4' : '' }}">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $approval->decision === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $approval->decision === 'approved' ? '✅ Disetujui' : '❌ Ditolak' }}
                                </span>
                                <p class="text-sm text-gray-600 mt-2">
                                    Oleh <strong>{{ $approval->approver->full_name ?? 'Admin GA' }}</strong>
                                </p>
                                @if($approval->decided_at)
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($approval->decided_at)->diffForHumans() }}
                                </p>
                                @endif
                                @if($approval->reason)
                                <div class="mt-3 bg-white rounded p-3">
                                    <p class="text-sm font-medium text-gray-700">Catatan:</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $approval->reason }}</p>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Catatan --}}
                    @if($loanRequest->notes)
                    <div class="mb-6 px-4">
                        <p class="text-sm text-gray-600 font-semibold">Catatan:</p>
                        <p class="text-gray-800 bg-yellow-50 border border-yellow-200 rounded p-3">
                            {{ $loanRequest->notes }}
                        </p>
                    </div>
                    @endif

                    {{-- Lampiran --}}
                    @if($loanRequest->attachments && $loanRequest->attachments->count() > 0)
                    <div class="mb-6">
                        <h5 class="font-bold text-lg mb-3 bg-gray-100 px-4 py-2 rounded">📎 Lampiran</h5>
                        <div class="px-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($loanRequest->attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment->file_url) }}" target="_blank"
                                class="flex items-center space-x-3 p-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="font-medium text-sm">{{ $attachment->file_name ?? 'File' }}</p>
                                    @if(isset($attachment->file_size_bytes))
                                    <p class="text-xs text-gray-500">
                                        {{ number_format($attachment->file_size_bytes / 1024, 2) }} KB
                                    </p>
                                    @endif
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="flex justify-between items-center pt-6 border-t">
                        <a href="{{ route('admin.loan-requests.index') }}"
                            class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                            ← Kembali
                        </a>
                    </div>
                </div>
            </div>

            {{-- APPROVAL SECTION --}}
            @if(in_array($loanRequest->status, ['approved_kepala', 'approved_ga']))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 border-b-2 border-gray-300 pb-3">
                        🔐 Admin GA Action - Approval
                    </h3>

                    <div class="mb-6">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8">
                                <button type="button" onclick="showTab('approve')" id="tab-approve"
                                    class="tab-button border-b-2 border-green-500 text-green-600 py-3 px-1 font-semibold text-sm">
                                    {{-- ✅ UBAH label --}}
                                    ✅ Setujui & Langsung Tugaskan
                                </button>
                                <button type="button" onclick="showTab('reject')" id="tab-reject"
                                    class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 py-3 px-1 text-sm">
                                    ❌ Tolak Pengajuan
                                </button>
                            </nav>
                        </div>
                    </div>

                    {{-- Form Approve --}}
                    <div id="form-approve" class="tab-content">
                        <form action="{{ route('admin.loan-requests.approve', $loanRequest) }}"
                            method="POST" id="approveForm">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih Kendaraan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="assigned_vehicle_id" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                        <option value="">-- Pilih Kendaraan --</option>
                                        @foreach($availableVehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">
                                            {{ $vehicle->brand }} {{ $vehicle->model }} - {{ $vehicle->plate_no }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Catatan (Opsional)
                                    </label>
                                    <textarea name="notes" rows="3" placeholder="Catatan untuk peminjam..."
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanda Tangan <span class="text-red-500">*</span>
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                                    <canvas id="approverSignaturePad" width="600" height="200"
                                        class="w-full cursor-crosshair"></canvas>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <button type="button" onclick="clearSignature()"
                                        class="text-sm text-red-600 hover:text-red-800">
                                        🗑️ Hapus Tanda Tangan
                                    </button>
                                    <span class="text-sm text-gray-500">Gambar tanda tangan di area di atas</span>
                                </div>
                                <input type="hidden" name="signature" id="approverSignatureInput">
                            </div>

                            <div class="mt-8">
                                <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg transition text-lg">
                                    {{-- ✅ UBAH label --}}
                                    ✅ Setujui & Langsung Tugaskan
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Form Reject --}}
                    <div id="form-reject" class="tab-content hidden">
                        <form action="{{ route('admin.loan-requests.reject', $loanRequest) }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Alasan Penolakan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="rejection_reason" rows="8" required
                                    placeholder="Jelaskan alasan penolakan dengan jelas..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"></textarea>
                                <p class="text-sm text-red-500 mt-2">⚠️ Wajib diisi!</p>
                            </div>
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                                <p class="text-sm text-red-700">
                                    <strong>Perhatian:</strong> Pengajuan yang ditolak tidak dapat diubah.
                                </p>
                            </div>
                            {{-- Ganti jadi trigger modal --}}
                            <button type="button"
                                onclick="openRejectModal()"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-6 rounded-lg transition text-lg">
                                ❌ Tolak Pengajuan
                            </button>

                        </form>
                    </div>
                </div>
            </div>
            @endif

            {{-- ✅ BLOCK ASSIGNED DIHAPUS TOTAL --}}

            {{-- VERIFY RETURN SECTION --}}
            @if($loanRequest->status === 'returned' && auth()->user()->isAdminGA())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-6">
                <div class="flex items-start mb-4">
                    <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h3 class="font-semibold text-yellow-900">Menunggu Verifikasi Pengembalian</h3>
                        <p class="text-sm text-yellow-800 mt-1">User telah submit pengembalian. Silakan verifikasi kondisi kendaraan.</p>
                    </div>
                </div>

                <a href="{{ route('admin.returns.show', $loanRequest->{'return'}) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700
          text-white text-sm font-semibold rounded-lg transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                 -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail Pengembalian
                </a>
            </div>
            @endif

        </div>
    </div>

    @if(in_array($loanRequest->status, ['approved_kepala', 'approved_ga']))
    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
            document.querySelectorAll('.tab-button').forEach(b => {
                b.classList.remove('border-green-500', 'border-red-500', 'text-green-600', 'text-red-600');
                b.classList.add('border-transparent', 'text-gray-500');
            });
            document.getElementById('form-' + tabName).classList.remove('hidden');
            const active = document.getElementById('tab-' + tabName);
            if (tabName === 'approve') {
                active.classList.add('border-green-500', 'text-green-600');
            } else {
                active.classList.add('border-red-500', 'text-red-600');
            }
        }

        const canvas = document.getElementById('approverSignaturePad');
        const ctx = canvas.getContext('2d');
        let isDrawing = false;

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            if (e.touches && e.touches[0]) {
                return {
                    x: e.touches[0].clientX - rect.left,
                    y: e.touches[0].clientY - rect.top
                };
            }
            return {
                x: e.clientX - rect.left,
                y: e.clientY - rect.top
            };
        }

        function startDrawing(e) {
            e.preventDefault();
            isDrawing = true;
            const p = getPos(e);
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
        }

        function draw(e) {
            if (!isDrawing) return;
            e.preventDefault();
            const p = getPos(e);
            ctx.lineTo(p.x, p.y);
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.stroke();
        }

        function stopDrawing(e) {
            if (!isDrawing) return;
            e.preventDefault();
            isDrawing = false;
        }

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);
        canvas.addEventListener('touchstart', startDrawing, {
            passive: false
        });
        canvas.addEventListener('touchmove', draw, {
            passive: false
        });
        canvas.addEventListener('touchend', stopDrawing, {
            passive: false
        });
        canvas.addEventListener('touchcancel', stopDrawing, {
            passive: false
        });

        function clearSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            document.getElementById('approverSignatureInput').value = '';
        }

        document.getElementById('approveForm').addEventListener('submit', function() {
            document.getElementById('approverSignatureInput').value = canvas.toDataURL('image/png');
        });
    </script>
    @endif

    {{-- ===== MODAL KONFIRMASI REJECT ===== --}}
    <div id="rejectModal"
        class="fixed inset-0 z-50 hidden items-center justify-center"
        style="background: rgba(0,0,0,0.55); backdrop-filter: blur(3px);">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden
                transform transition-all duration-300 scale-95 opacity-0"
            id="rejectModalBox">

            {{-- Header merah --}}
            <div class="bg-gradient-to-r from-red-600 to-red-500 px-6 py-5 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-extrabold text-lg leading-tight">Tolak Pengajuan?</h3>
                    <p class="text-red-100 text-xs mt-0.5">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5">
                {{-- Warning box --}}
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                    <div class="text-sm text-red-700">
                        <p class="font-semibold mb-1">Perhatian!</p>
                        <ul class="list-disc list-inside space-y-0.5 text-xs">
                            <li>Pengajuan akan berstatus <strong>Ditolak</strong></li>
                            <li>Pemohon akan mendapat notifikasi penolakan</li>
                            <li>Status tidak bisa diubah setelah ditolak</li>
                        </ul>
                    </div>
                </div>

                {{-- Info pemohon --}}
                <div class="bg-gray-50 rounded-xl px-4 py-3 mb-2 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($loanRequest->requester->full_name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Pemohon</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $loanRequest->requester->full_name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Footer tombol --}}
            <div class="px-6 pb-6 flex gap-3">
                <button type="button" onclick="closeRejectModal()"
                    class="flex-1 px-4 py-3 rounded-xl font-semibold text-sm transition-all
                       bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-200">
                    Batal
                </button>
                <button type="button" onclick="submitRejectForm()"
                    class="flex-1 px-4 py-3 rounded-xl font-bold text-sm transition-all
                       bg-red-600 hover:bg-red-700 active:scale-95 text-white shadow-lg shadow-red-200
                       flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Ya, Tolak Sekarang
                </button>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal() {
            const modal = document.getElementById('rejectModal');
            const modalBox = document.getElementById('rejectModalBox');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // animasi masuk
            setTimeout(() => {
                modalBox.classList.remove('scale-95', 'opacity-0');
                modalBox.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            const modalBox = document.getElementById('rejectModalBox');
            modalBox.classList.remove('scale-100', 'opacity-100');
            modalBox.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        function submitRejectForm() {
            // Validasi alasan diisi dulu
            const reason = document.querySelector('textarea[name="rejection_reason"]');
            if (!reason || reason.value.trim() === '') {
                closeRejectModal();
                reason.focus();
                reason.classList.add('border-red-500', 'ring-2', 'ring-red-300');
                reason.placeholder = '⚠️ Alasan wajib diisi sebelum menolak!';
                return;
            }
            reason.closest('form').submit();
        }

        // Klik di luar modal = tutup
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });

        // ESC = tutup
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeRejectModal();
        });
    </script>

</x-app-layout>