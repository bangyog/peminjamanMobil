<x-app-layout>
    <x-slot name="header">Dashboard Admin GA</x-slot>

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition
        class="mb-5 flex items-center justify-between gap-3 px-4 py-3 rounded-2xl text-sm font-semibold"
        style="background:#f0fdf4; border:1.5px solid #86efac; color:#15803d;">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
        <button @click="show = false" class="text-green-400 hover:text-green-700 transition flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
        <div>
            <h1 class="text-lg sm:text-xl font-extrabold text-gray-800 leading-tight">Dashboard Admin GA</h1>
            <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                Selamat datang, <span class="font-semibold text-gray-600">{{ Auth::user()->full_name }}</span>
            </p>
        </div>
        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full self-start sm:self-auto"
            style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block animate-pulse"></span>
            {{ now()->translatedFormat('D, d M Y · H:i') }} WIB
        </span>
    </div>

    {{-- ===== STATS UTAMA — 4 kartu ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        {{-- Menunggu Kepala --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #fef9c3;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Menunggu Kepala</p>
                    <p class="text-3xl font-extrabold text-yellow-500 mt-1">{{ $stats['pending_kepala'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Belum disetujui</p>
                </div>
                <div class="p-3 rounded-xl" style="background:linear-gradient(135deg,#fef9c3,#fef08a);">
                    <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- ✅ FIX: Perlu Approve GA — kartu yang hilang --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition relative overflow-hidden"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #fed7aa;">
            @if($stats['need_ga_approve'] > 0)
            <div class="absolute top-2 right-2 w-2 h-2 rounded-full bg-orange-500 animate-ping"></div>
            @endif
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Perlu Approve GA</p>
                    <p class="text-3xl font-extrabold text-orange-500 mt-1">{{ $stats['need_ga_approve'] }}</p>
                    <p class="text-xs {{ $stats['need_ga_approve'] > 0 ? 'text-orange-500 font-semibold' : 'text-gray-400' }} mt-1">
                        {{ $stats['need_ga_approve'] > 0 ? '⚡ Menunggu tindakan' : 'Semua diproses' }}
                    </p>
                </div>
                <div class="p-3 rounded-xl" style="background:linear-gradient(135deg,#ffedd5,#fed7aa);">
                    <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Perlu Penugasan --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition relative overflow-hidden"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #ede9fe;">
            @if($stats['need_assignment'] > 0)
            <div class="absolute top-2 right-2 w-2 h-2 rounded-full bg-purple-500 animate-ping"></div>
            @endif
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Perlu Penugasan</p>
                    <p class="text-3xl font-extrabold text-purple-600 mt-1">{{ $stats['need_assignment'] }}</p>
                    <p class="text-xs {{ $stats['need_assignment'] > 0 ? 'text-purple-500 font-semibold animate-pulse' : 'text-gray-400' }} mt-1">
                        {{ $stats['need_assignment'] > 0 ? '⚡ Belum ada kendaraan' : 'telah disetujui' }}
                    </p>
                </div>
                <div class="p-3 rounded-xl" style="background:linear-gradient(135deg,#ede9fe,#ddd6fe);">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Sedang Digunakan --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #bfdbfe;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Sedang Digunakan</p>
                    <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $stats['in_use'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Peminjaman aktif</p>
                </div>
                <div class="p-3 rounded-xl" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== STATUS ARMADA ===== --}}
    <div class="bg-white rounded-2xl p-5 mb-6"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                    style="background:linear-gradient(135deg,#1e1b4b,#3730a3);">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-sm font-extrabold text-gray-700">Status Armada Kendaraan</h3>
            </div>
            <a href="{{ route('admin.vehicles.index') }}"
                class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-xl transition"
                style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;"
                onmouseover="this.style.background='#1d4ed8'; this.style.color='#fff'; this.style.borderColor='#1d4ed8';"
                onmouseout="this.style.background='#eff6ff'; this.style.color='#1d4ed8'; this.style.borderColor='#bfdbfe';">
                Kelola Armada
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        @php
        $totalVehicles = ($vehicleStats['available'] + $vehicleStats['in_use'] + $vehicleStats['maintenance'] + $vehicleStats['retired']) ?: 1;
        $armadaConfig = [
            ['label'=>'Tersedia',    'key'=>'available',   'icon'=>'✅', 'bg'=>'#f0fdf4', 'border'=>'#86efac', 'text'=>'#15803d', 'bar'=>'#22c55e'],
            ['label'=>'Digunakan',   'key'=>'in_use',      'icon'=>'🔄', 'bg'=>'#eff6ff', 'border'=>'#93c5fd', 'text'=>'#1d4ed8', 'bar'=>'#3b82f6'],
            ['label'=>'Maintenance', 'key'=>'maintenance', 'icon'=>'🔧', 'bg'=>'#fffbeb', 'border'=>'#fde68a', 'text'=>'#92400e', 'bar'=>'#f59e0b'],
            ['label'=>'Tidak Digunakan',   'key'=>'retired',     'icon'=>'🚫', 'bg'=>'#f8fafc', 'border'=>'#e2e8f0', 'text'=>'#475569', 'bar'=>'#94a3b8'],
        ];
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($armadaConfig as $a)
            @php $count = $vehicleStats[$a['key']]; $pct = round(($count / $totalVehicles) * 100); @endphp
            <div class="rounded-2xl p-4 transition hover:shadow-sm"
                style="background:{{ $a['bg'] }}; border:1.5px solid {{ $a['border'] }};">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xl">{{ $a['icon'] }}</span>
                    <span class="text-xs font-bold" style="color:{{ $a['text'] }};">{{ $pct }}%</span>
                </div>
                <p class="text-3xl font-extrabold" style="color:{{ $a['text'] }};">{{ $count }}</p>
                <p class="text-xs font-bold mt-0.5 mb-2" style="color:{{ $a['text'] }}; opacity:0.8;">{{ $a['label'] }}</p>
                <div class="h-1.5 rounded-full overflow-hidden" style="background:{{ $a['border'] }};">
                    <div class="h-full rounded-full" style="width:{{ $pct }}%; background:{{ $a['bar'] }};"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ===== ✅ FIX: PERLU APPROVE GA (needGaApproval) — section yang hilang ===== --}}
    @if($needGaApproval->count() > 0)
    <div class="rounded-2xl overflow-hidden mb-6"
        style="box-shadow:0 4px 20px rgba(234,88,12,0.1); border:1.5px solid #fed7aa;">

        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2"
            style="background:linear-gradient(135deg,#fff7ed,#ffedd5); border-bottom:1px solid #fed7aa;">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center"
                        style="background:linear-gradient(135deg,#ea580c,#f97316);">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-orange-400 animate-ping"></span>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-orange-900">Perlu Persetujuan Admin GA</h3>
                    <p class="text-xs text-orange-600">{{ $needGaApproval->count() }} pengajuan sudah disetujui Kepala, menunggu GA</p>
                </div>
            </div>
            <span class="inline-flex items-center text-xs font-bold px-3 py-1.5 rounded-full flex-shrink-0"
                style="background:#fff; color:#ea580c; border:1.5px solid #fdba74;">
                ✅ approved_kepala
            </span>
        </div>

        <div class="hidden sm:block overflow-x-auto bg-white">
            <table class="min-w-full">
                <thead>
                    <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Pemohon</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Keperluan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tujuan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Berangkat</th>
                        <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($needGaApproval as $req)
                    <tr class="hover:bg-orange-50/30 transition" style="border-bottom:1px solid #f8fafc;">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <p class="text-sm font-bold text-gray-800">{{ $req->requester->full_name ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $req->requester->unit->name ?? '' }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-700">{{ Str::limit($req->purpose, 35) }}</td>
                        <td class="px-5 py-3.5 text-sm text-gray-500">{{ Str::limit($req->destination, 30) }}</td>
                        <td class="px-5 py-3.5 text-sm text-gray-600 whitespace-nowrap">
                            {{ $req->depart_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-right">
                            <a href="{{ route('admin.loan-requests.show', $req) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white rounded-xl transition-all duration-150"
                                style="background:linear-gradient(135deg,#ea580c,#f97316); box-shadow:0 3px 10px rgba(234,88,12,0.3);"
                                onmouseover="this.style.boxShadow='0 5px 16px rgba(234,88,12,0.45)'; this.style.transform='translateY(-1px)';"
                                onmouseout="this.style.boxShadow='0 3px 10px rgba(234,88,12,0.3)'; this.style.transform='translateY(0)';">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Review & Approve
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="sm:hidden bg-white divide-y" style="border-color:#f8fafc;">
            @foreach($needGaApproval as $req)
            <a href="{{ route('admin.loan-requests.show', $req) }}"
                class="flex items-start gap-3 px-4 py-4 hover:bg-orange-50/30 transition">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-800">{{ $req->requester->full_name ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $req->requester->unit->name ?? '' }} · {{ $req->depart_at?->format('d M Y') ?? '-' }}</p>
                    <p class="text-xs text-gray-600 mt-1 truncate">{{ Str::limit($req->purpose, 45) }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-300 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== PERLU ASSIGN KENDARAAN ===== --}}
    @if($needAssignment->count() > 0)
    <div class="rounded-2xl overflow-hidden mb-6"
        style="box-shadow:0 4px 20px rgba(109,40,217,0.1); border:1.5px solid #ddd6fe;">

        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2"
            style="background:linear-gradient(135deg,#faf5ff,#f5f3ff); border-bottom:1px solid #ddd6fe;">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center"
                        style="background:linear-gradient(135deg,#6d28d9,#7c3aed);">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-purple-400 animate-ping"></span>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-purple-900">Perlu Penugasan Kendaraan</h3>
                    <p class="text-xs text-purple-600">{{ $needAssignment->count() }} pengajuan belum punya kendaraan</p>
                </div>
            </div>
            <span class="inline-flex items-center text-xs font-bold px-3 py-1.5 rounded-full flex-shrink-0"
                style="background:#fff; color:#6d28d9; border:1.5px solid #c4b5fd;">
                ✅ approved_ga
            </span>
        </div>

        <div class="hidden sm:block overflow-x-auto bg-white">
            <table class="min-w-full">
                <thead>
                    <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Pemohon</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Keperluan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Preferensi Kendaraan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Berangkat</th>
                        <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($needAssignment as $req)
                    <tr class="hover:bg-purple-50/30 transition" style="border-bottom:1px solid #f8fafc;">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <p class="text-sm font-bold text-gray-800">{{ $req->requester->full_name ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $req->requester->unit->name ?? '' }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-700">{{ Str::limit($req->purpose, 35) }}</td>
                        <td class="px-5 py-3.5 text-sm">
                            @if($req->preferredVehicle)
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full"
                                style="background:#faf5ff; color:#6d28d9; border:1px solid #ddd6fe;">
                                🚗 {{ $req->preferredVehicle->brand }} {{ $req->preferredVehicle->model }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400 italic">Tidak ada preferensi</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-600 whitespace-nowrap">
                            {{ $req->depart_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-right">
                            <a href="{{ route('admin.loan-requests.show', $req) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white rounded-xl transition-all duration-150"
                                style="background:linear-gradient(135deg,#6d28d9,#7c3aed); box-shadow:0 3px 10px rgba(109,40,217,0.3);"
                                onmouseover="this.style.boxShadow='0 5px 16px rgba(109,40,217,0.45)'; this.style.transform='translateY(-1px)';"
                                onmouseout="this.style.boxShadow='0 3px 10px rgba(109,40,217,0.3)'; this.style.transform='translateY(0)';">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                Tugaskan Kendaraan
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="sm:hidden bg-white divide-y" style="border-color:#f8fafc;">
            @foreach($needAssignment as $req)
            <div class="px-4 py-4">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $req->requester->full_name ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $req->requester->unit->name ?? '' }}</p>
                    </div>
                    <span class="text-xs text-purple-600 font-semibold flex-shrink-0">
                        {{ $req->depart_at?->format('d M') ?? '-' }}
                    </span>
                </div>
                @if($req->preferredVehicle)
                <p class="text-xs text-purple-700 font-semibold mb-2">
                    🚗 {{ $req->preferredVehicle->brand }} {{ $req->preferredVehicle->model }}
                </p>
                @endif
                <a href="{{ route('admin.loan-requests.show', $req) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-white rounded-xl w-full justify-center"
                    style="background:linear-gradient(135deg,#6d28d9,#7c3aed);">
                    Tugaskan Kendaraan →
                </a>
            </div>
            @endforeach
        </div>

    </div>
    @endif

    {{-- ===== PENGAJUAN MASUK (submitted) ===== --}}
    <div class="bg-white rounded-2xl overflow-hidden mb-6"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">

        <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color:#f0f4ff; background:#fafbff;">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                    style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-sm font-extrabold text-gray-700">Pengajuan Masuk Terbaru</h3>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full"
                    style="background:#fffbeb; color:#92400e; border:1px solid #fde68a;">submitted</span>
            </div>
            <a href="{{ route('admin.loan-requests.index') }}"
                class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-xl transition"
                style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;"
                onmouseover="this.style.background='#1d4ed8'; this.style.color='#fff'; this.style.borderColor='#1d4ed8';"
                onmouseout="this.style.background='#eff6ff'; this.style.color='#1d4ed8'; this.style.borderColor='#bfdbfe';">
                Lihat Semua
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        @if($pendingRequests->count() > 0)
        <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No.</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Pemohon / Unit</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Keperluan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tujuan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Berangkat</th>
                        <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingRequests as $req)
                    <tr class="hover:bg-blue-50/20 transition" style="border-bottom:1px solid #f8fafc;">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="text-xs font-mono font-bold text-gray-400">#{{ $req->id }}</span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <p class="text-sm font-bold text-gray-800">{{ $req->requester->full_name ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $req->requester->unit->name ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-700">{{ Str::limit($req->purpose, 40) }}</td>
                        <td class="px-5 py-3.5 text-sm text-gray-500">{{ Str::limit($req->destination, 30) }}</td>
                        <td class="px-5 py-3.5 text-sm text-gray-600 whitespace-nowrap">
                            {{ $req->depart_at?->format('d M Y') ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-right">
                            <a href="{{ route('admin.loan-requests.show', $req) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-xl transition-all"
                                style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;"
                                onmouseover="this.style.background='#1d4ed8'; this.style.color='#fff';"
                                onmouseout="this.style.background='#eff6ff'; this.style.color='#1d4ed8';">
                                Detail
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="sm:hidden divide-y" style="border-color:#f8fafc;">
            @foreach($pendingRequests as $req)
            <a href="{{ route('admin.loan-requests.show', $req) }}"
                class="flex items-start gap-3 px-4 py-4 hover:bg-blue-50/30 transition">
                <span class="text-xs font-mono font-bold text-gray-400 mt-0.5 flex-shrink-0">#{{ $req->id }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-800">{{ $req->requester->full_name ?? '-' }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $req->requester->unit->name ?? '-' }} · {{ $req->depart_at?->format('d M Y') ?? '-' }}</p>
                    <p class="text-xs text-gray-600 mt-1 truncate">{{ Str::limit($req->purpose, 45) }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-300 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            @endforeach
        </div>
        @else
        <div class="py-12 flex flex-col items-center justify-center text-center">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3"
                style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <p class="text-sm font-bold text-gray-400">Tidak ada pengajuan masuk</p>
        </div>
        @endif
    </div>

    {{-- ===== PEMINJAMAN AKTIF ===== --}}
    @if($activeLoans->count() > 0)
    <div class="bg-white rounded-2xl overflow-hidden"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #bfdbfe;">

        <div class="px-5 py-4 border-b flex items-center justify-between"
            style="border-color:#bfdbfe; background:linear-gradient(135deg,#eff6ff,#dbeafe);">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                    style="background:linear-gradient(135deg,#1d4ed8,#2563eb);">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-sm font-extrabold text-blue-900">Peminjaman Aktif</h3>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                    style="background:#1d4ed8; color:#fff;">
                    {{ $activeLoans->count() }} aktif
                </span>
            </div>
            <span class="text-xs font-bold px-3 py-1.5 rounded-full"
                style="background:#fff; color:#1d4ed8; border:1.5px solid #93c5fd;">in_use</span>
        </div>

        <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Pemohon</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tujuan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Rencana Kembali</th>
                        <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeLoans as $req)
                    @php $isOverdue = $req->expected_return_at && $req->expected_return_at->isPast(); @endphp
                    <tr class="hover:bg-blue-50/20 transition"
                        style="border-bottom:1px solid #f8fafc; {{ $isOverdue ? 'background:#fef9f9;' : '' }}">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <p class="text-sm font-bold text-gray-800">{{ $req->requester->full_name ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $req->requester->unit->name ?? '' }}</p>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($req->assignment?->assignedVehicle)
                            <p class="text-sm font-semibold text-gray-700">
                                {{ $req->assignment->assignedVehicle->brand }}
                                {{ $req->assignment->assignedVehicle->model }}
                            </p>
                            <p class="text-xs font-mono text-gray-400">{{ $req->assignment->assignedVehicle->plate_no }}</p>
                            @else
                            <span class="text-xs text-gray-300 italic">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-600">{{ Str::limit($req->destination, 30) }}</td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($req->expected_return_at)
                            <p class="text-sm font-semibold {{ $isOverdue ? 'text-red-600' : 'text-gray-700' }}">
                                {{ $req->expected_return_at->format('d M Y') }}
                            </p>
                            <p class="text-xs {{ $isOverdue ? 'text-red-500 font-bold animate-pulse' : 'text-gray-400' }}">
                                {{ $isOverdue ? '⚠️ TERLAMBAT' : $req->expected_return_at->format('H:i') . ' WIB' }}
                            </p>
                            @else
                            <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-right">
                            <a href="{{ route('admin.loan-requests.show', $req) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-xl transition-all"
                                style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;"
                                onmouseover="this.style.background='#1d4ed8'; this.style.color='#fff';"
                                onmouseout="this.style.background='#eff6ff'; this.style.color='#1d4ed8';">
                                Detail
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="sm:hidden divide-y" style="border-color:#f8fafc;">
            @foreach($activeLoans as $req)
            @php $isOverdue = $req->expected_return_at && $req->expected_return_at->isPast(); @endphp
            <a href="{{ route('admin.loan-requests.show', $req) }}"
                class="flex items-start gap-3 px-4 py-4 transition {{ $isOverdue ? 'bg-red-50/50' : 'hover:bg-blue-50/20' }}">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <p class="text-sm font-bold text-gray-800 truncate">{{ $req->requester->full_name ?? '-' }}</p>
                        @if($isOverdue)
                        <span class="text-xs font-bold text-red-600 flex-shrink-0">⚠️ Terlambat</span>
                        @endif
                    </div>
                    @if($req->assignment?->assignedVehicle)
                    <p class="text-xs text-blue-600 font-semibold">
                        🚗 {{ $req->assignment->assignedVehicle->brand }}
                        {{ $req->assignment->assignedVehicle->model }}
                        <span class="font-mono text-gray-400">· {{ $req->assignment->assignedVehicle->plate_no }}</span>
                    </p>
                    @endif
                    <p class="text-xs text-gray-400 mt-0.5">
                        Kembali: {{ $req->expected_return_at?->format('d M Y H:i') ?? '-' }}
                    </p>
                </div>
                <svg class="w-4 h-4 text-gray-300 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            @endforeach
        </div>

    </div>
    @endif

</x-app-layout>