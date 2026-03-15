<x-app-layout>
    <x-slot name="header">History Peminjaman</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-gray-800 leading-tight">History Seluruh Peminjaman</h1>
            <p class="text-sm text-gray-400 mt-0.5">Rekap peminjaman yang sudah selesai dikembalikan</p>
        </div>
        <!-- <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('admin.monitoring.export-pdf', request()->query()) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white rounded-xl transition-all flex-shrink-0"
                style="background:linear-gradient(135deg,#dc2626,#ef4444); box-shadow:0 4px 14px rgba(220,38,38,0.25);"
                onmouseover="this.style.boxShadow='0 6px 20px rgba(220,38,38,0.4)'; this.style.transform='translateY(-1px)';"
                onmouseout="this.style.boxShadow='0 4px 14px rgba(220,38,38,0.25)'; this.style.transform='translateY(0)';">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export PDF Semua
            </a>
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full"
                style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block animate-pulse"></span>
                {{ now()->translatedFormat('l, d F Y') }}
            </span>
        </div> -->
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @php
            $sCards = [
                ['label'=>'Total Selesai',      'value'=>$stats['total_all'],    'color'=>'text-green-600',  'bg'=>'#f0fdf4', 'border'=>'#bbf7d0',  'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label'=>'Sedang Dipinjam',    'value'=>$stats['in_use_all'],   'color'=>'text-purple-600', 'bg'=>'#faf5ff', 'border'=>'#e9d5ff',  'icon'=>'M13 10V3L4 14h7v7l9-11h-7z'],
                ['label'=>'Sudah Dikembalikan', 'value'=>$stats['returned_all'], 'color'=>'text-blue-600',   'bg'=>'#eff6ff', 'border'=>'#bfdbfe',  'icon'=>'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6'],
                ['label'=>'Ditolak',            'value'=>$stats['rejected_all'], 'color'=>'text-red-500',    'bg'=>'#fef2f2', 'border'=>'#fecaca',  'icon'=>'M6 18L18 6M6 6l12 12'],
            ];
        @endphp
        @foreach($sCards as $card)
        <div class="bg-white rounded-2xl p-5 relative overflow-hidden"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid {{ $card['border'] }}; background:{{ $card['bg'] }};">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:{{ $card['bg'] }}; filter:brightness(0.95);">
                <svg class="w-5 h-5 {{ $card['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                </svg>
            </div>
            <p class="text-3xl font-extrabold {{ $card['color'] }} leading-none">{{ $card['value'] }}</p>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mt-1.5">{{ $card['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- FILTER --}}
    <div class="bg-white rounded-2xl p-5 mb-6"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">
        <form method="GET" action="{{ route('admin.monitoring.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">

                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Cari Peminjam</label>
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

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Unit</label>
                    <select name="unit_id"
                        class="w-full px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition appearance-none"
                        style="border-color:#e2e8f0; background:#fafbff;">
                        <option value="">Semua Unit</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition appearance-none"
                        style="border-color:#e2e8f0; background:#fafbff;">
                        {{-- ✅ Default: hanya returned --}}
                        <option value="returned" {{ (!request()->hasAny(['status']) || request('status') === 'returned') ? 'selected' : '' }}>
                            ✔️ Sudah Dikembalikan
                        </option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                            ❌ Ditolak
                        </option>
                        <option value="" {{ request('status') === '' && request()->has('status') ? 'selected' : '' }}>
                            Semua Status
                        </option>
                    </select>
                </div>

                <div class="flex flex-col justify-end">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white rounded-xl transition"
                        style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Terapkan
                    </button>
                </div>
            </div>

            {{-- Tanggal + Reset --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 pt-4"
                style="border-top:1px dashed #e2e8f0;">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        style="border-color:#e2e8f0; background:#fafbff;">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        style="border-color:#e2e8f0; background:#fafbff;">
                </div>
                <div class="lg:col-span-3 flex items-end gap-2 flex-wrap">
                    @if(request()->hasAny(['search','unit_id','start_date','end_date']) || (request()->has('status') && request('status') !== 'returned'))
                    <a href="{{ route('admin.monitoring.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold rounded-xl transition"
                        style="background:#f1f5f9; color:#64748b;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset Filter
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- TABEL --}}
    @php
        $sCfg = [
            'returned' => ['bg-green-50 text-green-700 border border-green-200',  '✔️ Selesai'],
            'rejected' => ['bg-red-50 text-red-700 border border-red-200',        '❌ Ditolak'],
            'in_use'   => ['bg-purple-50 text-purple-700 border border-purple-200','🚗 Sedang Pakai'],
        ];
    @endphp

    <div class="bg-white rounded-2xl overflow-hidden"
        style="box-shadow:0 4px 24px rgba(0,0,0,0.06); border:1px solid #f0f4ff;">

        <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color:#f0f4ff; background:#fafbff;">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                    style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="text-sm font-extrabold text-gray-700">Data Peminjaman</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                    style="background:#eff6ff; color:#1d4ed8;">
                    {{ $histories->total() }} record
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">#</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Peminjam / Unit</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Keperluan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Berangkat</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Rencana Kembali</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Cetak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $req)
                    @php
                        $sc = $sCfg[$req->status] ?? ['bg-gray-50 text-gray-600 border border-gray-200', $req->status];
                    @endphp
                    <tr class="hover:bg-blue-50/20 transition" style="border-bottom:1px solid #f8fafc;">

                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="text-xs font-mono font-bold text-slate-300">#{{ $req->id }}</span>
                        </td>

                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <p class="text-sm font-bold text-gray-800">{{ $req->requester->full_name ?? '-' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $req->requester->unit->name ?? '-' }}</p>
                        </td>

                        <td class="px-5 py-3.5" style="max-width:180px;">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ Str::limit($req->purpose, 30) }}</p>
                            @if($req->destination)
                            <p class="text-xs text-gray-400 mt-0.5 truncate flex items-center gap-1">
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                {{ Str::limit($req->destination, 25) }}
                            </p>
                            @endif
                        </td>

                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($req->assignment?->assignedVehicle)
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $req->assignment->assignedVehicle->brand }}
                                    {{ $req->assignment->assignedVehicle->model }}
                                </p>
                                <span class="text-xs font-mono text-gray-400">
                                    {{ $req->assignment->assignedVehicle->plate_no }}
                                </span>
                            @else
                                <span class="text-xs text-gray-300 italic">—</span>
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
                            @if($req->expected_return_at)
                                <p class="text-sm font-semibold text-gray-800">{{ $req->expected_return_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $req->expected_return_at->format('H:i') }} WIB</p>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>

                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $sc[0] }}">
                                {{ $sc[1] }}
                            </span>
                        </td>

                        {{-- ✅ Tombol cetak per baris --}}
                        <td class="px-5 py-3.5 whitespace-nowrap text-center">
                            <a href="{{ route('loan-requests.pdf', $req) }}"
                                target="_blank"
                                title="Cetak formulir #{{ $req->id }}"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-xl transition"
                                style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;"
                                onmouseover="this.style.background='#dc2626'; this.style.color='#fff'; this.style.borderColor='#dc2626';"
                                onmouseout="this.style.background='#fef2f2'; this.style.color='#dc2626'; this.style.borderColor='#fecaca';">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Cetak
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
                                    style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
                                    <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-500 text-base">Tidak ada data peminjaman selesai</p>
                                <p class="text-sm text-gray-400 mt-1">Belum ada peminjaman yang dikembalikan</p>
                                @if(request()->hasAny(['search','unit_id','start_date','end_date']) || (request()->has('status') && request('status') !== 'returned'))
                                <a href="{{ route('admin.monitoring.index') }}"
                                    class="inline-flex items-center gap-2 mt-4 px-4 py-2 text-sm font-semibold rounded-xl"
                                    style="background:#f1f5f9; color:#475569;">
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

        @if($histories->hasPages())
        <div class="px-5 py-4 flex items-center justify-between"
            style="border-top:1px solid #f0f4ff; background:#fafbff;">
            <p class="text-xs text-gray-400">
                Menampilkan
                <span class="font-semibold text-gray-600">{{ $histories->firstItem() }}–{{ $histories->lastItem() }}</span>
                dari <span class="font-semibold text-gray-600">{{ $histories->total() }}</span> record
            </p>
            <div>{{ $histories->links() }}</div>
        </div>
        @endif

    </div>

</x-app-layout>