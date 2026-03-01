<x-app-layout>
    <x-slot name="header">Pengembalian Kendaraan</x-slot>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition
        class="mb-5 flex items-center justify-between gap-3 px-4 py-3 rounded-2xl text-sm font-semibold"
        style="background:#f0fdf4; border:1.5px solid #86efac; color:#15803d;">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
        <button @click="show = false" class="text-green-400 hover:text-green-700 transition flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition
        class="mb-5 flex items-center justify-between gap-3 px-4 py-3 rounded-2xl text-sm font-semibold"
        style="background:#fef2f2; border:1.5px solid #fca5a5; color:#b91c1c;">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
        <button @click="show = false" class="text-red-400 hover:text-red-700 transition flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    @endif

    {{-- ===== PAGE HERO BAR ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-gray-800 leading-tight">Pengembalian Kendaraan</h1>
            <p class="text-sm text-gray-400 mt-0.5">Rekap seluruh data pengembalian kendaraan</p>
        </div>
        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full flex-shrink-0"
            style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block animate-pulse"></span>
            {{ now()->translatedFormat('l, d F Y') }}
        </span>
    </div>

    {{-- ========================= STATS ========================= --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        {{-- Total Return --}}
        <div class="rounded-2xl p-5 relative overflow-hidden"
            style="background:linear-gradient(135deg,#1e1b4b,#3730a3); box-shadow:0 4px 20px rgba(55,48,163,0.25);">
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10" style="background:#fff;"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-indigo-300 uppercase tracking-wider mb-1">Total Return</p>
                <p class="text-3xl font-extrabold text-white">{{ $stats['total_returns'] }}</p>
                <p class="text-xs text-indigo-300 mt-1">Seluruh periode</p>
            </div>
        </div>

        {{-- Hari Ini --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #bfdbfe;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Hari Ini</p>
                    <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $stats['today'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ now()->format('d M Y') }}</p>
                </div>
                <div class="p-3 rounded-xl" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Bulan Ini --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #ddd6fe;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Bulan Ini</p>
                    <p class="text-3xl font-extrabold text-purple-600 mt-1">{{ $stats['this_month'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="p-3 rounded-xl" style="background:linear-gradient(135deg,#ede9fe,#ddd6fe);">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Belum Diproses --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition relative overflow-hidden"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid {{ $stats['unprocessed'] > 0 ? '#fde68a' : '#e2e8f0' }};">
            @if($stats['unprocessed'] > 0)
            <div class="absolute top-2 right-2 w-2 h-2 rounded-full bg-amber-400 animate-ping"></div>
            @endif
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Belum Diproses</p>
                    <p class="text-3xl font-extrabold {{ $stats['unprocessed'] > 0 ? 'text-amber-500' : 'text-gray-400' }} mt-1">
                        {{ $stats['unprocessed'] }}
                    </p>
                    <p class="text-xs {{ $stats['unprocessed'] > 0 ? 'text-amber-500 font-semibold animate-pulse' : 'text-gray-400' }} mt-1">
                        {{ $stats['unprocessed'] > 0 ? '⚡ Perlu tindakan' : 'Semua beres' }}
                    </p>
                </div>
                <div class="p-3 rounded-xl"
                    style="background:{{ $stats['unprocessed'] > 0 ? 'linear-gradient(135deg,#fffbeb,#fef3c7)' : 'linear-gradient(135deg,#f1f5f9,#e2e8f0)' }};">
                    <svg class="w-7 h-7 {{ $stats['unprocessed'] > 0 ? 'text-amber-500' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- ========================= FILTER ========================= --}}
    <div class="bg-white rounded-2xl p-5 mb-6"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">

        <form method="GET" action="{{ route('admin.returns.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

                {{-- Search --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                        Cari Peminjam
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Nama peminjam..."
                            class="w-full pl-9 pr-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            style="border-color:#e2e8f0; background:#fafbff;">
                    </div>
                </div>

                {{-- Dari Tanggal --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                        Dari Tanggal
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full pl-9 pr-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            style="border-color:#e2e8f0; background:#fafbff;">
                    </div>
                </div>

                {{-- Sampai Tanggal --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                        Sampai Tanggal
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full pl-9 pr-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            style="border-color:#e2e8f0; background:#fafbff;">
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex flex-col justify-end gap-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white rounded-xl transition"
                        style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Terapkan Filter
                    </button>
                </div>

            </div>

            {{-- Active tags + reset --}}
            @if(request('search') || request('date_from') || request('date_to'))
            <div class="flex items-center gap-2 flex-wrap pt-4" style="border-top:1px dashed #e2e8f0;">
                <span class="text-xs text-gray-400">Filter aktif:</span>
                @if(request('search'))
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#eff6ff; color:#1d4ed8;">
                    🔍 {{ request('search') }}
                </span>
                @endif
                @if(request('date_from'))
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#eff6ff; color:#1d4ed8;">
                    📅 Dari {{ request('date_from') }}
                </span>
                @endif
                @if(request('date_to'))
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#eff6ff; color:#1d4ed8;">
                    📅 S/d {{ request('date_to') }}
                </span>
                @endif
                <a href="{{ route('admin.returns.index') }}"
                    class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full transition"
                    style="background:#f1f5f9; color:#64748b;">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset
                </a>
            </div>
            @endif

        </form>
    </div>

    {{-- ========================= TABEL ========================= --}}
    <div class="bg-white rounded-2xl overflow-hidden"
        style="box-shadow:0 4px 24px rgba(0,0,0,0.06); border:1px solid #f0f4ff;">

        {{-- Table header bar --}}
        <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color:#f0f4ff; background:#fafbff;">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                    style="background:linear-gradient(135deg,#1e1b4b,#3730a3);">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <span class="text-sm font-extrabold text-gray-700">Data Pengembalian</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                    style="background:#eff6ff; color:#1d4ed8;">
                    {{ $returns->total() }} record
                </span>
            </div>
            @if($stats['unprocessed'] > 0)
            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full"
                style="background:#fffbeb; color:#92400e; border:1px solid #fde68a;">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse inline-block"></span>
                {{ $stats['unprocessed'] }} belum diproses
            </span>
            @endif
        </div>

        {{-- ===== DESKTOP TABLE ===== --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Peminjam</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tgl Kembali</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Total Biaya</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Diterima Oleh</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $return)
                    @php
                        $isUnprocessed = is_null($return->received_by);
                        $vehicle       = $return->loanRequest->assignment->assignedVehicle ?? null;
                        $initials      = strtoupper(substr($return->loanRequest->requester->full_name ?? 'U', 0, 2));
                        $avatarColors  = [
                            ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                            ['bg' => '#dcfce7', 'text' => '#15803d'],
                            ['bg' => '#ede9fe', 'text' => '#6d28d9'],
                            ['bg' => '#fce7f3', 'text' => '#be185d'],
                            ['bg' => '#ffedd5', 'text' => '#c2410c'],
                        ];
                        $ac = $avatarColors[crc32($return->loanRequest->requester->full_name ?? '') % count($avatarColors)];
                    @endphp
                    <tr class="hover:bg-indigo-50/20 transition {{ $isUnprocessed ? '' : '' }}"
                        style="border-bottom:1px solid #f8fafc; {{ $isUnprocessed ? 'background:#fffdf0;' : '' }}">

                        {{-- Peminjam --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-extrabold flex-shrink-0"
                                    style="background:{{ $ac['bg'] }}; color:{{ $ac['text'] }};">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">
                                        {{ $return->loanRequest->requester->full_name ?? '-' }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ $return->loanRequest->unit->name ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- Kendaraan --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($vehicle)
                            <p class="text-sm font-semibold text-gray-700">
                                {{ $vehicle->brand }} {{ $vehicle->model }}
                            </p>
                            <p class="text-xs font-mono text-gray-400">{{ $vehicle->plate_no }}</p>
                            @else
                            <span class="text-xs text-gray-300 italic">—</span>
                            @endif
                        </td>

                        {{-- Tgl Kembali --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($return->returned_at)
                            <p class="text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($return->returned_at)->format('d M Y') }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($return->returned_at)->format('H:i') }} WIB
                            </p>
                            @else
                            <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>

                        {{-- Total Biaya --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @php $total = $return->expenses->sum('amount'); @endphp
                            @if($total > 0)
                            <span class="text-sm font-extrabold text-indigo-700">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                            @else
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                                style="background:#f0fdf4; color:#15803d;">Rp 0</span>
                            @endif
                        </td>

                        {{-- Diterima Oleh --}}
                        <td class="px-5 py-3.5 whitespace-nowrap text-sm">
                            @if($return->receivedBy)
                            <p class="font-semibold text-gray-700">{{ $return->receivedBy->full_name }}</p>
                            @else
                            <span class="text-xs text-gray-400 italic">Belum ada</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($isUnprocessed)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full"
                                style="background:#fffbeb; color:#92400e; border:1px solid #fde68a;">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                Belum Diproses
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full"
                                style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Sudah Diproses
                            </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-3.5 whitespace-nowrap text-right">
                            @if($isUnprocessed && auth()->user()->isAdminGA())
                            <a href="{{ route('admin.returns.show', $return) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white rounded-xl transition-all duration-150"
                                style="background:linear-gradient(135deg,#d97706,#f59e0b); box-shadow:0 3px 10px rgba(217,119,6,0.3);"
                                onmouseover="this.style.boxShadow='0 5px 16px rgba(217,119,6,0.45)'; this.style.transform='translateY(-1px)';"
                                onmouseout="this.style.boxShadow='0 3px 10px rgba(217,119,6,0.3)'; this.style.transform='translateY(0)';">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Proses
                            </a>
                            @else
                            <a href="{{ route('admin.returns.show', $return) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-xl transition-all"
                                style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;"
                                onmouseover="this.style.background='#1d4ed8'; this.style.color='#fff'; this.style.borderColor='#1d4ed8';"
                                onmouseout="this.style.background='#eff6ff'; this.style.color='#1d4ed8'; this.style.borderColor='#bfdbfe';">
                                Detail
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
                                    style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                                    <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-500 text-base">Tidak ada data pengembalian</p>
                                <p class="text-sm text-gray-400 mt-1">Data muncul setelah peminjam melakukan pengembalian</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== MOBILE CARDS ===== --}}
        <div class="sm:hidden divide-y" style="border-color:#f8fafc;">
            @forelse($returns as $return)
            @php
                $isUnprocessed = is_null($return->received_by);
                $vehicle       = $return->loanRequest->assignment->assignedVehicle ?? null;
                $initials      = strtoupper(substr($return->loanRequest->requester->full_name ?? 'U', 0, 2));
                $avatarColors  = [
                    ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                    ['bg' => '#dcfce7', 'text' => '#15803d'],
                    ['bg' => '#ede9fe', 'text' => '#6d28d9'],
                    ['bg' => '#fce7f3', 'text' => '#be185d'],
                    ['bg' => '#ffedd5', 'text' => '#c2410c'],
                ];
                $ac = $avatarColors[crc32($return->loanRequest->requester->full_name ?? '') % count($avatarColors)];
                $total = $return->expenses->sum('amount');
            @endphp
            <div class="px-4 py-4 {{ $isUnprocessed ? '' : '' }}"
                style="{{ $isUnprocessed ? 'background:#fffdf0;' : '' }}">
                <div class="flex items-start gap-3">
                    {{-- Avatar --}}
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xs font-extrabold flex-shrink-0"
                        style="background:{{ $ac['bg'] }}; color:{{ $ac['text'] }};">
                        {{ $initials }}
                    </div>
                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <p class="text-sm font-bold text-gray-800 truncate">
                                {{ $return->loanRequest->requester->full_name ?? '-' }}
                            </p>
                            @if($isUnprocessed)
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 text-xs font-bold rounded-full flex-shrink-0"
                                style="background:#fffbeb; color:#92400e; border:1px solid #fde68a;">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                Belum
                            </span>
                            @else
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 text-xs font-bold rounded-full flex-shrink-0"
                                style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;">
                                ✓ Proses
                            </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 mb-1">
                            {{ $return->loanRequest->unit->name ?? '-' }}
                        </p>
                        @if($vehicle)
                        <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            {{ $vehicle->brand }} {{ $vehicle->model }}
                            <span class="font-mono text-gray-400">· {{ $vehicle->plate_no }}</span>
                        </p>
                        @endif
                        <div class="flex items-center justify-between mt-2 gap-3">
                            <div>
                                <p class="text-xs text-gray-400">Kembali: {{ $return->returned_at ? \Carbon\Carbon::parse($return->returned_at)->format('d M Y H:i') : '-' }}</p>
                                @if($total > 0)
                                <p class="text-xs font-extrabold text-indigo-700">Rp {{ number_format($total, 0, ',', '.') }}</p>
                                @endif
                            </div>
                            @if($isUnprocessed && auth()->user()->isAdminGA())
                            <a href="{{ route('admin.returns.show', $return) }}"
                                class="inline-flex items-center gap-1 px-3 py-2 text-xs font-bold text-white rounded-xl flex-shrink-0"
                                style="background:linear-gradient(135deg,#d97706,#f59e0b);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Proses
                            </a>
                            @else
                            <a href="{{ route('admin.returns.show', $return) }}"
                                class="inline-flex items-center gap-1 px-3 py-2 text-xs font-bold rounded-xl flex-shrink-0"
                                style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;">
                                Detail →
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-4 py-14 flex flex-col items-center justify-center text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                    style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                    <svg class="w-7 h-7 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <p class="font-bold text-gray-400">Tidak ada data pengembalian</p>
            </div>
            @endforelse
        </div>

        {{-- ===== PAGINATION ===== --}}
        @if($returns->hasPages())
        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
            style="border-top:1px solid #f0f4ff; background:#fafbff;">
            <p class="text-xs text-gray-400">
                Menampilkan
                <span class="font-bold text-gray-600">{{ $returns->firstItem() }}–{{ $returns->lastItem() }}</span>
                dari <span class="font-bold text-gray-600">{{ $returns->total() }}</span> data
            </p>
            <div>{{ $returns->links() }}</div>
        </div>
        @endif

    </div>

</x-app-layout>