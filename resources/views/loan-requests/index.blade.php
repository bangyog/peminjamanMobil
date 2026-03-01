<x-app-layout>
    <x-slot name="header">Riwayat Pengajuan</x-slot>

    {{-- ===== PAGE HERO BAR ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-gray-800 leading-tight">Riwayat Pengajuan Peminjaman</h1>
            <p class="text-sm text-gray-400 mt-0.5">Kelola dan pantau semua pengajuan kendaraan Anda</p>
        </div>
        <a href="{{ route('loan-requests.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white rounded-xl shadow-lg transition flex-shrink-0"
            style="background: linear-gradient(135deg, #0052A3, #0066CC);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Pengajuan
        </a>
    </div>

    {{-- ===== STATS CARDS ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">

        <div class="stat-card bg-white rounded-2xl p-4 relative overflow-hidden"
            style="box-shadow:0 2px 12px rgba(234,179,8,0.1); border:1px solid #fef9c3;">
            <div class="absolute -bottom-3 -right-3 w-14 h-14 rounded-full opacity-5" style="background:#eab308;"></div>
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-2.5" style="background:#fefce8;">
                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-extrabold text-yellow-500 leading-none">{{ $stats['pending'] }}</p>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mt-1">Menunggu</p>
        </div>

        <div class="stat-card bg-white rounded-2xl p-4 relative overflow-hidden"
            style="box-shadow:0 2px 12px rgba(34,197,94,0.1); border:1px solid #dcfce7;">
            <div class="absolute -bottom-3 -right-3 w-14 h-14 rounded-full opacity-5" style="background:#22c55e;"></div>
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-2.5" style="background:#f0fdf4;">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-extrabold text-green-500 leading-none">{{ $stats['approved'] }}</p>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mt-1">Disetujui</p>
        </div>

        <div class="stat-card bg-white rounded-2xl p-4 relative overflow-hidden"
            style="box-shadow:0 2px 12px rgba(59,130,246,0.1); border:1px solid #dbeafe;">
            <div class="absolute -bottom-3 -right-3 w-14 h-14 rounded-full opacity-5" style="background:#3b82f6;"></div>
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-2.5" style="background:#eff6ff;">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <p class="text-2xl font-extrabold text-blue-500 leading-none">{{ $stats['in_use'] }}</p>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mt-1">Sedang Pakai</p>
        </div>

        <div class="stat-card bg-white rounded-2xl p-4 relative overflow-hidden"
            style="box-shadow:0 2px 12px rgba(100,116,139,0.08); border:1px solid #e2e8f0;">
            <div class="absolute -bottom-3 -right-3 w-14 h-14 rounded-full opacity-5" style="background:#64748b;"></div>
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-2.5" style="background:#f8fafc;">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-2xl font-extrabold text-slate-600 leading-none">{{ $stats['completed'] }}</p>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mt-1">Selesai</p>
        </div>

        <div class="stat-card bg-white rounded-2xl p-4 relative overflow-hidden"
            style="box-shadow:0 2px 12px rgba(239,68,68,0.1); border:1px solid #fee2e2;">
            <div class="absolute -bottom-3 -right-3 w-14 h-14 rounded-full opacity-5" style="background:#ef4444;"></div>
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-2.5" style="background:#fef2f2;">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <p class="text-2xl font-extrabold text-red-500 leading-none">{{ $stats['rejected'] }}</p>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mt-1">Ditolak</p>
        </div>

    </div>

    {{-- ===== FILTER BAR ===== --}}
    <div class="bg-white rounded-2xl p-4 mb-5"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">
        <form method="GET" action="{{ route('loan-requests.index') }}"
            class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">

            {{-- Search --}}
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari tujuan atau keperluan..."
                    class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    style="border-color:#e2e8f0; background:#fafbff;">
            </div>

            {{-- Status Filter --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                </div>
                <select name="status"
                    class="pl-10 pr-8 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition appearance-none w-full md:w-56"
                    style="border-color:#e2e8f0; background:#fafbff;">
                    <option value="">Semua Status</option>
                    <option value="submitted"       {{ request('status') == 'submitted'       ? 'selected' : '' }}>⏳ Menunggu Persetujuan</option>
                    <option value="approved_kepala" {{ request('status') == 'approved_kepala' ? 'selected' : '' }}>✅ Disetujui Kepala</option>
                    <option value="approved_ga"     {{ request('status') == 'approved_ga'     ? 'selected' : '' }}>✅ Disetujui GA</option>
                    <option value="assigned"        {{ request('status') == 'assigned'        ? 'selected' : '' }}>🚙 Kendaraan Ditugaskan</option>
                    <option value="in_use"          {{ request('status') == 'in_use'          ? 'selected' : '' }}>🔄 Sedang Digunakan</option>
                    <option value="returned"        {{ request('status') == 'returned'        ? 'selected' : '' }}>✅ Selesai</option>
                    <option value="rejected"        {{ request('status') == 'rejected'        ? 'selected' : '' }}>❌ Ditolak</option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 flex-shrink-0">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white rounded-xl transition"
                    style="background: linear-gradient(135deg, #0052A3, #0066CC);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
                @if(request('search') || request('status'))
                <a href="{{ route('loan-requests.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl transition"
                    style="background:#f1f5f9; color:#64748b;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset
                </a>
                @endif
            </div>

        </form>

        {{-- Active filter indicator --}}
        @if(request('search') || request('status'))
        <div class="flex items-center gap-2 mt-3 pt-3" style="border-top:1px dashed #e2e8f0;">
            <span class="text-xs text-gray-400">Filter aktif:</span>
            @if(request('search'))
            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full"
                style="background:#eff6ff; color:#1d4ed8;">
                🔍 "{{ request('search') }}"
            </span>
            @endif
            @if(request('status'))
            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full"
                style="background:#f0fdf4; color:#15803d;">
                🏷️ {{ request('status') }}
            </span>
            @endif
        </div>
        @endif
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="bg-white rounded-2xl overflow-hidden"
        style="box-shadow:0 4px 24px rgba(0,0,0,0.06); border:1px solid #f0f4ff;">

        {{-- Table Header Bar --}}
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color:#f0f4ff;">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                    style="background: linear-gradient(135deg, #0052A3, #0066CC);">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="text-sm font-bold text-gray-700">Daftar Pengajuan</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                    style="background:#eff6ff; color:#1d4ed8;">
                    {{ $requests->total() }} data
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No.</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tgl. Berangkat</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Keperluan / Tujuan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $item)
                    @php
                        $statusConfig = [
                            'submitted'       => ['pill' => 'bg-amber-50 text-amber-700 border border-amber-200',   'label' => '⏳ Menunggu Kepala'],
                            'approved_kepala' => ['pill' => 'bg-blue-50 text-blue-700 border border-blue-200',      'label' => '📋 Menunggu GA'],
                            'approved_ga'     => ['pill' => 'bg-indigo-50 text-indigo-700 border border-indigo-200','label' => '✅ Disetujui GA'],
                            'assigned'        => ['pill' => 'bg-cyan-50 text-cyan-700 border border-cyan-200',      'label' => '🔑 Kendaraan Siap'],
                            'in_use'          => ['pill' => 'bg-purple-50 text-purple-700 border border-purple-200','label' => '🚗 Sedang Pakai'],
                            'returned'        => ['pill' => 'bg-green-50 text-green-700 border border-green-200',   'label' => '✔️ Selesai'],
                            'rejected'        => ['pill' => 'bg-red-50 text-red-700 border border-red-200',         'label' => '❌ Ditolak'],
                            'cancelled'       => ['pill' => 'bg-gray-50 text-gray-500 border border-gray-200',      'label' => '🚫 Dibatalkan'],
                        ];
                        $cfg = $statusConfig[$item->status] ?? ['pill' => 'bg-gray-50 text-gray-600 border border-gray-200', 'label' => $item->status];
                    @endphp
                    <tr class="group hover:bg-blue-50/30 transition-colors" style="border-bottom:1px solid #f8fafc;">

                        {{-- No --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-xs font-bold text-slate-300 font-mono">#{{ $item->id }}</span>
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($item->depart_at)
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $item->depart_at->format('d M Y') }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $item->depart_at->format('H:i') }} WIB
                                </p>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>

                        {{-- Keperluan / Tujuan --}}
                        <td class="px-5 py-4" style="max-width:220px;">
                            <p class="text-sm font-semibold text-gray-800 truncate">
                                {{ Str::limit($item->purpose, 35) }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5 truncate flex items-center gap-1">
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ Str::limit($item->destination, 30) }}
                            </p>
                        </td>

                        {{-- Kendaraan --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($item->assignment?->assignedVehicle)
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $item->assignment->assignedVehicle->brand }}
                                    {{ $item->assignment->assignedVehicle->model }}
                                </p>
                                <span class="inline-block text-xs font-mono font-bold px-2 py-0.5 rounded-lg mt-0.5"
                                    style="background:#f1f5f9; color:#475569;">
                                    {{ $item->assignment->assignedVehicle->plate_no }}
                                </span>
                            @elseif($item->preferredVehicle)
                                <p class="text-xs text-gray-400 italic">
                                    Preferensi:
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $item->preferredVehicle->brand }}
                                    {{ $item->preferredVehicle->model }}
                                </p>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Belum ditentukan
                                </span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $cfg['pill'] }}">
                                {{ $cfg['label'] }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">

                                {{-- Detail --}}
                                <a href="{{ route('loan-requests.show', $item) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg transition"
                                    style="background:#eff6ff; color:#1d4ed8;">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>

                                {{-- Edit — hanya saat submitted --}}
                                @if($item->status === 'submitted')
                                <a href="{{ route('loan-requests.edit', $item) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg transition"
                                    style="background:#f0fdf4; color:#15803d;">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                @endif

                                {{-- Kembalikan — hanya saat in_use --}}
                                @if($item->status === 'in_use')
                                <a href="{{ route('loan-requests.show', $item) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg transition"
                                    style="background:#faf5ff; color:#7e22ce;">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                    Kembalikan
                                </a>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
                                    style="background: linear-gradient(135deg, #eff6ff, #dbeafe);">
                                    <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <p class="text-base font-bold text-gray-600">Tidak ada pengajuan ditemukan</p>
                                <p class="text-sm text-gray-400 mt-1 max-w-xs">
                                    @if(request('search') || request('status'))
                                        Coba ubah kata kunci atau filter yang digunakan
                                    @else
                                        Belum ada pengajuan peminjaman kendaraan
                                    @endif
                                </p>
                                @if(request('search') || request('status'))
                                <a href="{{ route('loan-requests.index') }}"
                                    class="inline-flex items-center gap-2 mt-4 px-4 py-2 text-sm font-semibold rounded-xl transition"
                                    style="background:#f1f5f9; color:#475569;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Reset Filter
                                </a>
                                @else
                                <a href="{{ route('loan-requests.create') }}"
                                    class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 text-sm font-bold text-white rounded-xl transition"
                                    style="background: linear-gradient(135deg, #0052A3, #0066CC);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Buat Pengajuan Pertama
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($requests->hasPages())
        <div class="px-6 py-4 flex items-center justify-between"
            style="border-top:1px solid #f0f4ff; background:#fafbff;">
            <p class="text-xs text-gray-400">
                Menampilkan
                <span class="font-semibold text-gray-600">{{ $requests->firstItem() }}–{{ $requests->lastItem() }}</span>
                dari <span class="font-semibold text-gray-600">{{ $requests->total() }}</span> data
            </p>
            <div class="pagination-wrapper">
                {{ $requests->links() }}
            </div>
        </div>
        @endif

    </div>

</x-app-layout>