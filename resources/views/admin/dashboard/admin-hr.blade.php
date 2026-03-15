<x-app-layout>
    <x-slot name="header">Dashboard Admin HR</x-slot>

    {{-- ===== PAGE HERO BAR ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-gray-800 leading-tight">
                Dashboard Admin HR
            </h1>
            <p class="text-sm text-gray-400 mt-0.5 flex items-center gap-1.5">
                <span class="font-semibold text-gray-600">{{ Auth::user()->full_name }}</span>
                <span class="text-gray-300">·</span>
                <span>Unit:</span>
                <span class="font-bold text-blue-600">{{ $unit->name ?? '-' }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            {{-- Tombol akses history seluruh unit --}}
            <a href="{{ route('admin.monitoring.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full text-white transition"
                style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                History Semua Unit
            </a>
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full"
                style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse inline-block"></span>
                {{ now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    {{-- ===== STATS CARDS (unit sendiri) ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        {{-- Perlu Saya Setujui --}}
        <div class="stat-card bg-white rounded-2xl p-5 relative overflow-hidden transition-all"
            style="{{ $stats['need_my_approval'] > 0
                ? 'box-shadow:0 4px 20px rgba(234,179,8,0.2); border:2px solid #fde68a;'
                : 'box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #e2e8f0;' }}">
            <div class="absolute -bottom-4 -right-4 w-16 h-16 rounded-full opacity-5"
                style="{{ $stats['need_my_approval'] > 0 ? 'background:#eab308;' : 'background:#94a3b8;' }}"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                    style="{{ $stats['need_my_approval'] > 0 ? 'background:#fef3c7;' : 'background:#f1f5f9;' }}">
                    <svg class="w-5 h-5 {{ $stats['need_my_approval'] > 0 ? 'text-yellow-600' : 'text-slate-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                @if($stats['need_my_approval'] > 0)
                <span class="flex h-2.5 w-2.5 relative mt-1">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-yellow-500"></span>
                </span>
                @endif
            </div>
            <p class="text-3xl font-extrabold leading-none {{ $stats['need_my_approval'] > 0 ? 'text-yellow-600' : 'text-gray-300' }}">
                {{ $stats['need_my_approval'] }}
            </p>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mt-1.5">Perlu Saya Setujui</p>
            @if($stats['need_my_approval'] > 0)
            <p class="text-xs text-yellow-600 font-bold mt-1.5 flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                </svg>
                Perlu tindakan segera
            </p>
            @else
            <p class="text-xs text-gray-400 mt-1.5">Semua sudah diproses</p>
            @endif
        </div>

        {{-- Saya Setujui --}}
        <div class="stat-card bg-white rounded-2xl p-5 relative overflow-hidden"
            style="box-shadow:0 2px 12px rgba(34,197,94,0.1); border:1px solid #dcfce7;">
            <div class="absolute -bottom-4 -right-4 w-16 h-16 rounded-full opacity-5" style="background:#22c55e;"></div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#f0fdf4;">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-extrabold text-green-500 leading-none">{{ $stats['approved_by_me'] }}</p>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mt-1.5">Saya Setujui</p>
            <p class="text-xs text-gray-400 mt-1.5">Total disetujui</p>
        </div>

        {{-- Saya Tolak --}}
        <div class="stat-card bg-white rounded-2xl p-5 relative overflow-hidden"
            style="box-shadow:0 2px 12px rgba(239,68,68,0.1); border:1px solid #fee2e2;">
            <div class="absolute -bottom-4 -right-4 w-16 h-16 rounded-full opacity-5" style="background:#ef4444;"></div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#fef2f2;">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <p class="text-3xl font-extrabold text-red-500 leading-none">{{ $stats['rejected_by_me'] }}</p>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mt-1.5">Saya Tolak</p>
            <p class="text-xs text-gray-400 mt-1.5">Total ditolak</p>
        </div>

        {{-- Dipinjam Unit --}}
        <div class="stat-card bg-white rounded-2xl p-5 relative overflow-hidden"
            style="box-shadow:0 2px 12px rgba(59,130,246,0.1); border:1px solid #dbeafe;">
            <div class="absolute -bottom-4 -right-4 w-16 h-16 rounded-full opacity-5" style="background:#3b82f6;"></div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#eff6ff;">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <p class="text-3xl font-extrabold text-blue-500 leading-none">{{ $stats['in_use_unit'] }}</p>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mt-1.5">Dipinjam Unit</p>
            <p class="text-xs text-gray-400 mt-1.5">Sedang digunakan</p>
        </div>

    </div>

    {{-- ===== RINGKASAN HISTORY SELURUH UNIT (khusus Admin HR) ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @php
            $hCards = [
                ['label'=>'Total Semua Peminjaman', 'value'=>$historyStats['total_all'],    'color'=>'text-slate-700',  'bg'=>'#f8fafc', 'border'=>'#e2e8f0'],
                ['label'=>'Sedang Dipinjam',         'value'=>$historyStats['in_use_all'],   'color'=>'text-purple-600', 'bg'=>'#faf5ff', 'border'=>'#e9d5ff'],
                ['label'=>'Sudah Dikembalikan',      'value'=>$historyStats['returned_all'], 'color'=>'text-green-600',  'bg'=>'#f0fdf4', 'border'=>'#bbf7d0'],
                ['label'=>'Ditolak',                 'value'=>$historyStats['rejected_all'], 'color'=>'text-red-500',    'bg'=>'#fef2f2', 'border'=>'#fecaca'],
            ];
        @endphp
        @foreach($hCards as $card)
        <div class="bg-white rounded-2xl p-4 relative overflow-hidden"
            style="border:1px solid {{ $card['border'] }}; background:{{ $card['bg'] }};">
            <p class="text-2xl font-extrabold {{ $card['color'] }} leading-none">{{ $card['value'] }}</p>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mt-1.5">{{ $card['label'] }}</p>
            <p class="text-xs text-gray-400 mt-1">
                <a href="{{ route('admin.monitoring.index') }}" class="underline hover:text-blue-500">Lihat detail →</a>
            </p>
        </div>
        @endforeach
    </div>

    {{-- ===== URGENT: MENUNGGU PERSETUJUAN ===== --}}
    @if($pendingApproval->count() > 0)
    <div class="rounded-2xl overflow-hidden mb-6"
        style="box-shadow:0 4px 24px rgba(234,179,8,0.15); border:2px solid #fde68a;">
        <div class="flex items-center justify-between px-6 py-4" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#eab308;">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-amber-900">Menunggu Persetujuan Anda</h3>
                    <p class="text-xs text-amber-600 mt-0.5">{{ $pendingApproval->count() }} pengajuan perlu ditindaklanjuti</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full"
                style="background:#fef08a; color:#78350f;">
                <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse inline-block"></span>
                URGENT
            </span>
        </div>

        <div class="bg-white p-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
            @foreach($pendingApproval as $req)
            <div class="rounded-xl p-4 border transition hover:shadow-md"
                style="border-color:#fde68a; background:#fffdf5;">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs font-mono font-bold text-amber-400">#{{ $req->id }}</span>
                            <span class="font-bold text-gray-800 text-sm">{{ $req->requester->full_name ?? '-' }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Diajukan {{ $req->created_at->locale('id')->diffForHumans() }}
                        </p>
                    </div>
                    <span class="flex-shrink-0 inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full"
                        style="background:#fef3c7; color:#92400e; border:1px solid #fde68a;">
                        ⏳ Menunggu
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div class="rounded-lg p-2.5" style="background:#fffbeb;">
                        <p class="text-xs text-amber-500 font-semibold uppercase tracking-wide">Keperluan</p>
                        <p class="text-sm font-semibold text-gray-800 mt-0.5 truncate">{{ Str::limit($req->purpose, 30) }}</p>
                    </div>
                    <div class="rounded-lg p-2.5" style="background:#fffbeb;">
                        <p class="text-xs text-amber-500 font-semibold uppercase tracking-wide">Tujuan</p>
                        <p class="text-sm text-gray-700 mt-0.5 truncate">{{ Str::limit($req->destination, 25) }}</p>
                    </div>
                    <div class="rounded-lg p-2.5" style="background:#fffbeb;">
                        <p class="text-xs text-amber-500 font-semibold uppercase tracking-wide">Berangkat</p>
                        <p class="text-sm text-gray-700 mt-0.5">{{ $req->depart_at?->format('d M Y, H:i') ?? '-' }}</p>
                    </div>
                    <div class="rounded-lg p-2.5" style="background:#fffbeb;">
                        <p class="text-xs text-amber-500 font-semibold uppercase tracking-wide">Rencana Kembali</p>
                        <p class="text-sm text-gray-700 mt-0.5">{{ $req->expected_return_at?->format('d M Y, H:i') ?? '-' }}</p>
                    </div>
                    @if($req->notes)
                    <div class="col-span-2 rounded-lg p-2.5" style="background:#fffbeb;">
                        <p class="text-xs text-amber-500 font-semibold uppercase tracking-wide">Catatan</p>
                        <p class="text-sm text-gray-600 italic mt-0.5">{{ Str::limit($req->notes, 60) }}</p>
                    </div>
                    @endif
                </div>

                {{-- ✅ DIPERBAIKI: route hr bukan akuntansi --}}
                <a href="{{ route('approvals.hr.approve.form', $req) }}"
                    class="flex items-center justify-center gap-2 w-full py-2.5 text-sm font-bold text-white rounded-xl transition"
                    style="background:linear-gradient(135deg,#d97706,#f59e0b);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Review & Putuskan
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="rounded-2xl p-6 text-center mb-6"
        style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); border:1px solid #bbf7d0;">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3"
            style="background:linear-gradient(135deg,#22c55e,#16a34a);">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="font-extrabold text-green-800 text-base">Semua pengajuan sudah diproses!</p>
        <p class="text-sm text-green-600 mt-1">Tidak ada pengajuan yang menunggu persetujuan Anda saat ini</p>
    </div>
    @endif

    {{-- ===== RIWAYAT PENGAJUAN UNIT ===== --}}
    <div class="bg-white rounded-2xl overflow-hidden"
        style="box-shadow:0 4px 24px rgba(0,0,0,0.06); border:1px solid #f0f4ff;">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color:#f0f4ff;">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center"
                    style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-gray-800">Riwayat Pengajuan Unit</h3>
                    <p class="text-xs text-blue-600 font-semibold">{{ $unit->name ?? '-' }}</p>
                </div>
                @if($unitRequests->count() > 0)
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                    style="background:#eff6ff; color:#1d4ed8;">
                    {{ $unitRequests->count() }} data
                </span>
                @endif
            </div>
        </div>

        @if($unitRequests->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No.</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Pemohon</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Keperluan / Tujuan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Berangkat</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unitRequests as $req)
                    @php
                        $sCfg = match($req->status) {
                            'submitted'       => ['bg-amber-50 text-amber-700 border border-amber-200',    '⏳ Menunggu'],
                            'approved_kepala' => ['bg-blue-50 text-blue-700 border border-blue-200',       '✅ Disetujui Kepala'],
                            'approved_ga'     => ['bg-indigo-50 text-indigo-700 border border-indigo-200', '⏳ Menunggu GA'],
                            'assigned'        => ['bg-cyan-50 text-cyan-700 border border-cyan-200',       '🔑 Ditugaskan'],
                            'in_use'          => ['bg-purple-50 text-purple-700 border border-purple-200', '🚗 Sedang Pakai'],
                            'returned'        => ['bg-green-50 text-green-700 border border-green-200',    '✔️ Selesai'],
                            'rejected'        => ['bg-red-50 text-red-700 border border-red-200',          '❌ Ditolak'],
                            default           => ['bg-gray-50 text-gray-600 border border-gray-200', $req->status],
                        };
                    @endphp
                    <tr class="group hover:bg-blue-50/30 transition-colors" style="border-bottom:1px solid #f8fafc;">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="text-xs font-mono font-bold text-slate-300">#{{ $req->id }}</span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <p class="text-sm font-bold text-gray-800">{{ $req->requester->full_name ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-3.5" style="max-width:200px;">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ Str::limit($req->purpose, 30) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 truncate flex items-center gap-1">
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ Str::limit($req->destination, 25) }}
                            </p>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($req->assignment?->assignedVehicle)
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $req->assignment->assignedVehicle->brand }}
                                    {{ $req->assignment->assignedVehicle->model }}
                                </p>
                                <span class="inline-block text-xs font-mono font-bold px-2 py-0.5 rounded-lg mt-0.5"
                                    style="background:#f1f5f9; color:#475569;">
                                    {{ $req->assignment->assignedVehicle->plate_no }}
                                </span>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($req->depart_at)
                                <p class="text-sm font-semibold text-gray-800">{{ $req->depart_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $req->depart_at->format('H:i') }} WIB</p>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $sCfg[0] }}">
                                {{ $sCfg[1] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="py-14 text-center">
            <p class="font-bold text-gray-500">Belum ada pengajuan dari unit ini</p>
            <p class="text-sm text-gray-400 mt-1">Pengajuan dari anggota unit akan muncul di sini</p>
        </div>
        @endif
    </div>

</x-app-layout>