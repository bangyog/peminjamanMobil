<x-app-layout>
    <x-slot name="header">Monitoring Pengeluaran</x-slot>

    {{-- ===== PAGE HERO BAR ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-gray-800 leading-tight">Monitoring Pengeluaran Kendaraan</h1>
            <p class="text-sm text-gray-400 mt-0.5">Rekap seluruh biaya perjalanan kendaraan</p>
        </div>
        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full flex-shrink-0"
            style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block animate-pulse"></span>
            {{ now()->translatedFormat('l, d F Y') }}
        </span>
    </div>


    {{-- ========================= STATS ========================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        {{-- Total Pengeluaran (all-time) --}}
        <div class="rounded-2xl p-5 relative overflow-hidden"
            style="background:linear-gradient(135deg,#1e1b4b,#3730a3); box-shadow:0 4px 20px rgba(55,48,163,0.25);">
            <div class="absolute -right-5 -top-5 w-28 h-28 rounded-full opacity-10" style="background:#fff;"></div>
            <div class="absolute -right-2 -bottom-7 w-20 h-20 rounded-full opacity-10" style="background:#a5b4fc;"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-2">
                    <div class="p-2 rounded-lg" style="background:rgba(255,255,255,0.15);">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-indigo-300 uppercase tracking-wider">Total Pengeluaran</p>
                </div>
                <p class="text-3xl font-extrabold text-white">
                    Rp {{ number_format($stats['total_expenses'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-indigo-300 mt-1">Seluruh periode</p>
            </div>
        </div>

        {{-- Bulan Ini --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #dcfce7;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Bulan Ini</p>
                    <p class="text-2xl font-extrabold text-green-600 mt-1">
                        Rp {{ number_format($stats['total_this_month'], 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="p-3 rounded-xl" style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Record --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #ede9fe;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Total Record</p>
                    <p class="text-2xl font-extrabold text-purple-600 mt-1">
                        {{ number_format($stats['total_records'], 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Entri pengeluaran</p>
                </div>
                <div class="p-3 rounded-xl" style="background:linear-gradient(135deg,#ede9fe,#ddd6fe);">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>


    {{-- ========================= FILTER & EXPORT ========================= --}}
    <div class="bg-white rounded-2xl p-5 mb-6"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">

        <form method="GET" action="{{ route('admin.monitoring.expenses') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

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
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
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
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full pl-9 pr-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            style="border-color:#e2e8f0; background:#fafbff;">
                    </div>
                </div>

                {{-- Jenis Pengeluaran --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                        Jenis Pengeluaran
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <select name="expense_type"
                            class="w-full pl-9 pr-8 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition appearance-none"
                            style="border-color:#e2e8f0; background:#fafbff;">
                            <option value="">Semua Jenis</option>
                            <option value="fuel"    {{ request('expense_type') === 'fuel'    ? 'selected' : '' }}>⛽ BBM / Bensin</option>
                            <option value="toll"    {{ request('expense_type') === 'toll'    ? 'selected' : '' }}>🛣️ Tol</option>
                            <option value="parking" {{ request('expense_type') === 'parking' ? 'selected' : '' }}>🅿️ Parkir</option>
                            <option value="repair"  {{ request('expense_type') === 'repair'  ? 'selected' : '' }}>🔧 Servis / Perbaikan</option>
                            <option value="other"   {{ request('expense_type') === 'other'   ? 'selected' : '' }}>📦 Lainnya</option>
                        </select>
                    </div>
                </div>

                {{-- Filter Button --}}
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

            {{-- Active filter tags + Reset + Export --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4"
                style="border-top:1px dashed #e2e8f0;">

                <div class="flex items-center gap-2 flex-wrap">
                    @if(request('start_date') || request('end_date') || request('expense_type'))
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        @if(request('start_date'))
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#eff6ff; color:#1d4ed8;">
                            📅 Dari {{ request('start_date') }}
                        </span>
                        @endif
                        @if(request('end_date'))
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#eff6ff; color:#1d4ed8;">
                            📅 S/d {{ request('end_date') }}
                        </span>
                        @endif
                        @if(request('expense_type'))
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#faf5ff; color:#6d28d9;">
                            🏷️ {{ ['fuel'=>'BBM','toll'=>'Tol','parking'=>'Parkir','repair'=>'Servis','other'=>'Lainnya'][request('expense_type')] ?? request('expense_type') }}
                        </span>
                        @endif
                        <a href="{{ route('admin.monitoring.expenses') }}"
                            class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full transition"
                            style="background:#f1f5f9; color:#64748b;">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reset
                        </a>
                    @else
                        <span class="text-xs text-gray-400 italic">Menampilkan semua data</span>
                    @endif
                </div>

                {{-- Export --}}
                <a href="{{ route('admin.monitoring.expenses.export', request()->query()) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white rounded-xl transition-all duration-150 flex-shrink-0"
                    style="background:linear-gradient(135deg,#15803d,#16a34a); box-shadow:0 4px 14px rgba(21,128,61,0.25);"
                    onmouseover="this.style.boxShadow='0 6px 20px rgba(21,128,61,0.4)'; this.style.transform='translateY(-1px)';"
                    onmouseout="this.style.boxShadow='0 4px 14px rgba(21,128,61,0.25)'; this.style.transform='translateY(0)';">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel / CSV
                </a>

            </div>
        </form>
    </div>


    {{-- ========================= TABEL ========================= --}}
    @php
        $typeConfig = [
            'fuel'    => ['label' => 'BBM',              'icon' => '⛽', 'style' => 'background:#fffbeb; color:#92400e; border:1px solid #fde68a;'],
            'toll'    => ['label' => 'Tol',               'icon' => '🛣️', 'style' => 'background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe;'],
            'parking' => ['label' => 'Parkir',            'icon' => '🅿️', 'style' => 'background:#faf5ff; color:#5b21b6; border:1px solid #ddd6fe;'],
            'repair'  => ['label' => 'Servis',            'icon' => '🔧', 'style' => 'background:#fef2f2; color:#991b1b; border:1px solid #fecaca;'],
            'other'   => ['label' => 'Lainnya',           'icon' => '📦', 'style' => 'background:#f8fafc; color:#475569; border:1px solid #e2e8f0;'],
        ];
    @endphp

    <div class="bg-white rounded-2xl overflow-hidden"
        style="box-shadow:0 4px 24px rgba(0,0,0,0.06); border:1px solid #f0f4ff;">

        {{-- Table header bar --}}
        <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color:#f0f4ff; background:#fafbff;">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                    style="background:linear-gradient(135deg,#1e1b4b,#3730a3);">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-extrabold text-gray-700">Data Pengeluaran</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                    style="background:#eff6ff; color:#1d4ed8;">
                    {{ $expenses->total() }} record
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Peminjam</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Jenis</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Keterangan</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    @php
                        $loanRequest = $expense->return->loanRequest ?? null;
                        $requester   = $loanRequest->requester ?? null;
                        $vehicle     = $loanRequest->assignment->assignedVehicle ?? null;
                        $cfg         = $typeConfig[$expense->type] ?? ['label' => ucfirst($expense->type), 'icon' => '📦', 'style' => 'background:#f8fafc; color:#475569; border:1px solid #e2e8f0;'];
                    @endphp
                    <tr class="hover:bg-indigo-50/20 transition" style="border-bottom:1px solid #f8fafc;">

                        {{-- Tanggal --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <p class="text-sm font-semibold text-gray-700">
                                {{ $expense->created_at->format('d M Y') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $expense->created_at->format('H:i') }} WIB
                            </p>
                        </td>

                        {{-- Peminjam --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @php
                                $initials = strtoupper(substr($requester->full_name ?? 'U', 0, 2));
                                $avatarColors = [
                                    ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                                    ['bg' => '#dcfce7', 'text' => '#15803d'],
                                    ['bg' => '#ede9fe', 'text' => '#6d28d9'],
                                    ['bg' => '#fce7f3', 'text' => '#be185d'],
                                    ['bg' => '#ffedd5', 'text' => '#c2410c'],
                                ];
                                $ac = $avatarColors[crc32($requester->full_name ?? '') % count($avatarColors)];
                            @endphp
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-extrabold flex-shrink-0"
                                    style="background:{{ $ac['bg'] }}; color:{{ $ac['text'] }};">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $requester->full_name ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $requester->unit->name ?? '-' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Kendaraan --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($vehicle)
                            <p class="text-sm font-semibold text-gray-700">
                                {{ $vehicle->brand }} {{ $vehicle->model }}
                            </p>
                            <p class="text-xs font-mono text-gray-400 mt-0.5">{{ $vehicle->plate_no }}</p>
                            @else
                            <span class="text-xs text-gray-300 italic">—</span>
                            @endif
                        </td>

                        {{-- Jenis --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full"
                                style="{{ $cfg['style'] }}">
                                {{ $cfg['icon'] }} {{ $cfg['label'] }}
                            </span>
                        </td>

                        {{-- Jumlah --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="text-sm font-extrabold text-indigo-700">
                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- Keterangan --}}
                        <td class="px-5 py-3.5 max-w-[180px]">
                            @if($expense->description)
                            <p class="text-sm text-gray-600 truncate" title="{{ $expense->description }}">
                                {{ $expense->description }}
                            </p>
                            @else
                            <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>

                        {{-- Bukti --}}
                        <td class="px-5 py-3.5 whitespace-nowrap text-center">
                            @if($expense->receipt_url)
                            <a href="{{ Storage::url($expense->receipt_url) }}" target="_blank"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-xl transition-all duration-150"
                                style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;"
                                onmouseover="this.style.background='#1d4ed8'; this.style.color='#fff'; this.style.borderColor='#1d4ed8';"
                                onmouseout="this.style.background='#eff6ff'; this.style.color='#1d4ed8'; this.style.borderColor='#bfdbfe';">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat
                            </a>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1.5 text-xs font-semibold rounded-xl"
                                style="background:#f8fafc; color:#cbd5e1; border:1px solid #e2e8f0;">
                                —
                            </span>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-500 text-base">Tidak ada data pengeluaran</p>
                                <p class="text-sm text-gray-400 mt-1">Coba ubah atau reset filter di atas</p>
                                @if(request('start_date') || request('end_date') || request('expense_type'))
                                <a href="{{ route('admin.monitoring.expenses') }}"
                                    class="inline-flex items-center gap-2 mt-4 px-4 py-2 text-sm font-semibold rounded-xl transition"
                                    style="background:#f1f5f9; color:#475569;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Reset Filter
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
        @if($expenses->hasPages())
        <div class="px-5 py-4 flex items-center justify-between"
            style="border-top:1px solid #f0f4ff; background:#fafbff;">
            <p class="text-xs text-gray-400">
                Menampilkan
                <span class="font-semibold text-gray-600">{{ $expenses->firstItem() }}–{{ $expenses->lastItem() }}</span>
                dari <span class="font-semibold text-gray-600">{{ $expenses->total() }}</span> record
            </p>
            <div>{{ $expenses->links() }}</div>
        </div>
        @endif

    </div>

</x-app-layout>