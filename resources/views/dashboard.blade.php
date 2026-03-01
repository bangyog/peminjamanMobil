<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    {{-- ===== WELCOME BANNER ===== --}}
    <div class="relative overflow-hidden rounded-2xl mb-6 p-6 sm:p-8"
        style="background: linear-gradient(135deg, #0047AB 0%, #0066CC 50%, #1a8fe3 100%);">
        {{-- Decorative circles --}}
        <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full opacity-10"
            style="background:rgba(255,255,255,0.4);"></div>
        <div class="absolute -bottom-10 right-20 w-28 h-28 rounded-full opacity-10"
            style="background:rgba(255,255,255,0.4);"></div>
        <div class="absolute top-4 right-40 w-10 h-10 rounded-full opacity-10"
            style="background:rgba(255,255,255,0.4);"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-blue-200 text-sm font-medium mb-1">
                    {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </p>
                <h1 class="text-white text-2xl sm:text-3xl font-bold leading-tight">
                    Selamat Datang, {{ auth()->user()->full_name }}! 👋
                </h1>
                <p class="text-blue-100/80 text-sm mt-1.5">
                    Dashboard Sistem Peminjaman Kendaraan — PT. SWA
                </p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('loan-requests.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-blue-700 text-sm font-bold rounded-xl shadow-lg hover:bg-blue-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajukan Peminjaman
                </a>
            </div>
        </div>
    </div>

    {{-- ===== STATISTICS CARDS ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">

        {{-- Total --}}
        <div class="stat-card bg-white rounded-2xl p-5 relative overflow-hidden"
            style="box-shadow: 0 2px 12px rgba(0,82,163,0.08); border:1px solid #e8f0fe;">
            <div class="absolute -top-3 -right-3 w-16 h-16 rounded-full opacity-5" style="background:#3b82f6;"></div>
            <div class="flex flex-col h-full">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#eff6ff;">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-3xl font-extrabold text-gray-900 leading-none">{{ $stats['total'] }}</p>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-1.5">Total</p>
            </div>
        </div>

        {{-- Menunggu --}}
        <div class="stat-card bg-white rounded-2xl p-5 relative overflow-hidden"
            style="box-shadow: 0 2px 12px rgba(234,179,8,0.1); border:1px solid #fef9c3;">
            <div class="absolute -top-3 -right-3 w-16 h-16 rounded-full opacity-5" style="background:#eab308;"></div>
            <div class="flex flex-col h-full">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#fefce8;">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-3xl font-extrabold text-yellow-500 leading-none">{{ $stats['pending'] }}</p>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-1.5">Menunggu</p>
            </div>
        </div>

        {{-- Disetujui --}}
        <div class="stat-card bg-white rounded-2xl p-5 relative overflow-hidden"
            style="box-shadow: 0 2px 12px rgba(34,197,94,0.1); border:1px solid #dcfce7;">
            <div class="absolute -top-3 -right-3 w-16 h-16 rounded-full opacity-5" style="background:#22c55e;"></div>
            <div class="flex flex-col h-full">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#f0fdf4;">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-3xl font-extrabold text-green-500 leading-none">{{ $stats['approved'] }}</p>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-1.5">Disetujui</p>
            </div>
        </div>

        {{-- Digunakan --}}
        <div class="stat-card bg-white rounded-2xl p-5 relative overflow-hidden"
            style="box-shadow: 0 2px 12px rgba(168,85,247,0.1); border:1px solid #f3e8ff;">
            <div class="absolute -top-3 -right-3 w-16 h-16 rounded-full opacity-5" style="background:#a855f7;"></div>
            <div class="flex flex-col h-full">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#faf5ff;">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <p class="text-3xl font-extrabold text-purple-500 leading-none">{{ $stats['in_use'] }}</p>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-1.5">Digunakan</p>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="stat-card bg-white rounded-2xl p-5 relative overflow-hidden"
            style="box-shadow: 0 2px 12px rgba(59,130,246,0.1); border:1px solid #dbeafe;">
            <div class="absolute -top-3 -right-3 w-16 h-16 rounded-full opacity-5" style="background:#3b82f6;"></div>
            <div class="flex flex-col h-full">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#eff6ff;">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-3xl font-extrabold text-blue-500 leading-none">{{ $stats['completed'] }}</p>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-1.5">Selesai</p>
            </div>
        </div>

        {{-- Ditolak --}}
        <div class="stat-card bg-white rounded-2xl p-5 relative overflow-hidden"
            style="box-shadow: 0 2px 12px rgba(239,68,68,0.1); border:1px solid #fee2e2;">
            <div class="absolute -top-3 -right-3 w-16 h-16 rounded-full opacity-5" style="background:#ef4444;"></div>
            <div class="flex flex-col h-full">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#fef2f2;">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-3xl font-extrabold text-red-500 leading-none">{{ $stats['rejected'] }}</p>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-1.5">Ditolak</p>
            </div>
        </div>

    </div>

    {{-- ===== MAIN CONTENT ROW ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== ACTIVE LOAN CARD ===== --}}
        @if(isset($activeLoan) && $activeLoan)
        <div class="lg:col-span-1">
            <div class="relative overflow-hidden rounded-2xl h-full"
                style="background: linear-gradient(145deg, #7c3aed 0%, #6d28d9 50%, #4c1d95 100%);
                       box-shadow: 0 8px 32px rgba(109,40,217,0.3);">

                {{-- Decorative --}}
                <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full opacity-10"
                    style="background:rgba(255,255,255,0.5);"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 rounded-full opacity-5"
                    style="background:rgba(255,255,255,0.5);"></div>

                <div class="relative z-10 p-6">
                    {{-- Header --}}
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                            style="background:rgba(255,255,255,0.15);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-base leading-tight">Peminjaman Aktif</h3>
                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                style="background:rgba(255,255,255,0.15); color:rgba(255,255,255,0.9);">
                                {{ match($activeLoan->status) {
                                    'approved_ga' => 'Menunggu Assignment',
                                    'assigned'    => 'Kendaraan Siap',
                                    'in_use'      => 'Sedang Digunakan',
                                    default       => ucfirst(str_replace('_', ' ', $activeLoan->status))
                                } }}
                            </span>
                        </div>
                    </div>

                    {{-- Vehicle --}}
                    <div class="rounded-xl p-4 mb-4" style="background:rgba(0,0,0,0.15);">
                        <p class="text-xs font-medium mb-1.5" style="color:rgba(216,180,254,0.8);">🚗 Kendaraan</p>
                        @if($activeLoan->assignment?->assignedVehicle)
                            <p class="text-white font-bold text-base leading-tight">
                                {{ $activeLoan->assignment->assignedVehicle->brand }}
                                {{ $activeLoan->assignment->assignedVehicle->model }}
                            </p>
                            <p class="text-sm mt-0.5 font-mono" style="color:rgba(216,180,254,0.85);">
                                {{ $activeLoan->assignment->assignedVehicle->plate_no }}
                            </p>
                        @else
                            <p class="text-sm" style="color:rgba(216,180,254,0.7);">Belum ditentukan</p>
                        @endif
                    </div>

                    {{-- Info rows --}}
                    <div class="space-y-3 mb-5">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:rgba(216,180,254,0.7);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div>
                                <p class="text-xs" style="color:rgba(216,180,254,0.65);">Tujuan</p>
                                <p class="text-white font-semibold text-sm">{{ $activeLoan->destination }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:rgba(216,180,254,0.7);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-xs" style="color:rgba(216,180,254,0.65);">Rencana Kembali</p>
                                <p class="text-white font-semibold text-sm">
                                    {{ $activeLoan->return_date
                                        ? \Carbon\Carbon::parse($activeLoan->return_date)->locale('id')->isoFormat('D MMM Y')
                                        : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Action --}}
                    @if($activeLoan->status === 'in_use')
                    <a href="{{ route('loan-requests.show', $activeLoan) }}"
                        class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition"
                        style="background:rgba(255,255,255,0.95); color:#6d28d9;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Kembalikan Kendaraan
                    </a>
                    @else
                    <a href="{{ route('loan-requests.show', $activeLoan) }}"
                        class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-semibold transition"
                        style="background:rgba(255,255,255,0.12); color:rgba(255,255,255,0.85); border:1px solid rgba(255,255,255,0.2);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Detail
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- ===== RECENT LOANS ===== --}}
        <div class="{{ (isset($activeLoan) && $activeLoan) ? 'lg:col-span-2' : 'lg:col-span-3' }}">
            <div class="bg-white rounded-2xl overflow-hidden h-full"
                style="box-shadow: 0 4px 24px rgba(0,0,0,0.06); border:1px solid #f0f4ff;">

                {{-- Card Header --}}
                <div class="flex items-center justify-between px-6 py-4"
                    style="background: linear-gradient(135deg, #0052A3 0%, #0066CC 100%);">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                            style="background:rgba(255,255,255,0.15);">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-white font-bold text-base">Riwayat Peminjaman Terbaru</h3>
                    </div>
                    <a href="{{ route('loan-requests.index') }}"
                        class="text-xs font-semibold px-3 py-1.5 rounded-lg transition"
                        style="background:rgba(255,255,255,0.15); color:rgba(255,255,255,0.9);">
                        Lihat Semua →
                    </a>
                </div>

                @if(isset($recentLoans) && $recentLoans->count() > 0)
                <div class="divide-y" style="divide-color:#f8fafc;">
                    @foreach($recentLoans as $loan)
                    @php
                        $statusConfig = match($loan->status) {
                            'submitted'       => ['bg-amber-50 text-amber-700 border-amber-200',   '⏳ Menunggu Kepala'],
                            'approved_kepala' => ['bg-blue-50 text-blue-700 border-blue-200',      '📋 Menunggu GA'],
                            'approved_ga'     => ['bg-indigo-50 text-indigo-700 border-indigo-200','✅ Disetujui GA'],
                            'assigned'        => ['bg-cyan-50 text-cyan-700 border-cyan-200',      '🔑 Kendaraan Siap'],
                            'in_use'          => ['bg-purple-50 text-purple-700 border-purple-200','🚗 Sedang Digunakan'],
                            'returned'        => ['bg-green-50 text-green-700 border-green-200',   '✔️ Selesai'],
                            'rejected'        => ['bg-red-50 text-red-700 border-red-200',         '❌ Ditolak'],
                            'cancelled'       => ['bg-gray-50 text-gray-500 border-gray-200',      '🚫 Dibatalkan'],
                            default           => ['bg-gray-50 text-gray-600 border-gray-200',      ucfirst($loan->status)],
                        };
                    @endphp
                    <div class="px-5 py-4 hover:bg-blue-50/30 transition group">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">

                                {{-- Top row --}}
                                <div class="flex items-center flex-wrap gap-2 mb-2">
                                    <span class="text-xs font-bold text-slate-300 font-mono">#{{ $loan->id }}</span>
                                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full border {{ $statusConfig[0] }}">
                                        {{ $statusConfig[1] }}
                                    </span>
                                </div>

                                {{-- Destination --}}
                                <p class="font-bold text-gray-800 text-sm leading-snug truncate">
                                    {{ $loan->destination }}
                                </p>

                                {{-- Purpose --}}
                                <p class="text-xs text-gray-400 mt-0.5 truncate">
                                    {{ Str::limit($loan->purpose, 65) }}
                                </p>

                                {{-- Meta info --}}
                                <div class="flex items-center flex-wrap gap-3 mt-2.5">
                                    <span class="inline-flex items-center gap-1 text-xs text-slate-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $loan->departure_date
                                            ? \Carbon\Carbon::parse($loan->departure_date)->locale('id')->isoFormat('D MMM Y')
                                            : '-' }}
                                    </span>
                                    @if($loan->assignment?->assignedVehicle)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        {{ $loan->assignment->assignedVehicle->plate_no }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Detail Button --}}
                            <a href="{{ route('loan-requests.show', $loan) }}"
                                class="flex-shrink-0 flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-xl transition
                                       text-blue-600 bg-blue-50 hover:bg-blue-100 opacity-0 group-hover:opacity-100">
                                Detail
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Footer link --}}
                <div class="px-5 py-3 border-t" style="border-color:#f0f4ff; background:#fafbff;">
                    <a href="{{ route('loan-requests.index') }}"
                        class="flex items-center justify-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                        Lihat semua riwayat peminjaman
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                @else
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-5"
                        style="background: linear-gradient(135deg, #eff6ff, #dbeafe);">
                        <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="font-bold text-gray-700 text-base">Belum ada riwayat peminjaman</p>
                    <p class="text-gray-400 text-sm mt-1 max-w-xs">Mulai dengan membuat pengajuan peminjaman kendaraan pertama Anda</p>
                    <a href="{{ route('loan-requests.create') }}"
                        class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 text-sm font-bold text-white rounded-xl transition"
                        style="background: linear-gradient(135deg, #0052A3, #0066CC);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Pengajuan Sekarang
                    </a>
                </div>
                @endif
            </div>
        </div>

    </div>

</x-app-layout>