<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📋 Data Pengembalian Kendaraan
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total Return</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_returns'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Seluruh periode</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Hari Ini</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['today'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ now()->format('d M Y') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Bulan Ini</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $stats['this_month'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Belum Diproses</p>
                    <p class="text-3xl font-bold {{ $stats['unprocessed'] > 0 ? 'text-amber-500' : 'text-green-600' }} mt-1">
                        {{ $stats['unprocessed'] }}
                    </p>
                    <p class="text-xs {{ $stats['unprocessed'] > 0 ? 'text-amber-500' : 'text-gray-400' }} mt-1">
                        {{ $stats['unprocessed'] > 0 ? '⚡ Perlu tindakan' : 'Semua beres' }}
                    </p>
                </div>
            </div>

            {{-- Filter --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.returns.index') }}"
                      class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Cari Peminjam</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Nama peminjam..."
                               class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="min-w-[160px]">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Status Proses</label>
                        <select name="status"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua</option>
                            <option value="unprocessed" {{ request('status') == 'unprocessed' ? 'selected' : '' }}>Belum Diproses</option>
                            <option value="processed"   {{ request('status') == 'processed'   ? 'selected' : '' }}>Sudah Diproses</option>
                        </select>
                    </div>
                    <div class="min-w-[150px]">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="min-w-[150px]">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition">
                            Filter
                        </button>
                        @if(request()->hasAny(['search','status','date_from','date_to']))
                            <a href="{{ route('admin.returns.index') }}"
                               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-200">
                    <p class="text-sm text-gray-500">Rekap seluruh data pengembalian kendaraan</p>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Peminjam</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kendaraan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tgl Kembali</th>
                                {{-- ✅ Ganti "Total Biaya" → "Kondisi Kendaraan" --}}
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kondisi Kendaraan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Diterima Oleh</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($returns as $return)
                                @php
                                    $vehicle       = $return->loanRequest?->assignment?->assignedVehicle;
                                    $isUnprocessed = is_null($return->received_by);
                                    $requester     = $return->loanRequest?->requester;
                                    $nameParts     = explode(' ', $requester?->full_name ?? '');
                                    $initials      = strtoupper(substr($nameParts[0] ?? '', 0, 1) . substr($nameParts[1] ?? '', 0, 1));

                                    // ✅ Badge kondisi
                                    $conditionMap = [
                                        'good'              => ['label' => 'Baik',              'class' => 'bg-green-100 text-green-700'],
                                        'minor_damage'      => ['label' => 'Kerusakan Ringan',  'class' => 'bg-yellow-100 text-yellow-700'],
                                        'major_damage'      => ['label' => 'Kerusakan Berat',   'class' => 'bg-red-100 text-red-700'],
                                        'needs_maintenance' => ['label' => 'Perlu Servis',      'class' => 'bg-orange-100 text-orange-700'],
                                    ];
                                    $conditionCfg = $conditionMap[$return->vehicle_condition] ?? ['label' => '-', 'class' => 'bg-gray-100 text-gray-500'];
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    {{-- Peminjam --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center flex-shrink-0">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $requester?->full_name ?? '-' }}</p>
                                                <p class="text-xs text-gray-400">{{ $return->loanRequest?->unit?->name ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- Kendaraan --}}
                                    <td class="px-4 py-3">
                                        @if($vehicle)
                                            <p class="font-medium text-gray-800">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                                            <p class="text-xs text-gray-400">{{ $vehicle->plate_no }}</p>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    {{-- Tgl Kembali --}}
                                    <td class="px-4 py-3">
                                        @if($return->returned_at)
                                            <p class="font-medium text-gray-800">
                                                {{ \Carbon\Carbon::parse($return->returned_at)->format('d M Y') }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ \Carbon\Carbon::parse($return->returned_at)->format('H:i') }} WIB
                                            </p>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    {{-- ✅ Kondisi Kendaraan --}}
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $conditionCfg['class'] }}">
                                            {{ $conditionCfg['label'] }}
                                        </span>
                                    </td>
                                    {{-- Diterima Oleh --}}
                                    <td class="px-4 py-3">
                                        @if($return->receivedBy)
                                            <p class="text-gray-800 font-medium">{{ $return->receivedBy->full_name }}</p>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Belum ada</span>
                                        @endif
                                    </td>
                                    {{-- Status --}}
                                    <td class="px-4 py-3">
                                        @if($isUnprocessed)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                                ⏳ Belum Diproses
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                ✅ Sudah Diproses
                                            </span>
                                        @endif
                                    </td>
                                    {{-- Aksi --}}
                                    <td class="px-4 py-3 text-center">
                                        @if($isUnprocessed && auth()->user()->isAdminGA())
                                            <a href="{{ route('admin.returns.show', $return) }}"
                                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md transition">
                                                Proses
                                            </a>
                                        @else
                                            <a href="{{ route('admin.returns.show', $return) }}"
                                               class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-md transition">
                                                Detail
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="font-medium">Tidak ada data pengembalian</p>
                                        <p class="text-xs mt-1">Data muncul setelah peminjam melakukan pengembalian</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @forelse($returns as $return)
                        @php
                            $vehicle       = $return->loanRequest?->assignment?->assignedVehicle;
                            $isUnprocessed = is_null($return->received_by);
                            $requester     = $return->loanRequest?->requester;
                            $conditionMap  = [
                                'good'              => ['label' => 'Baik',             'class' => 'bg-green-100 text-green-700'],
                                'minor_damage'      => ['label' => 'Kerusakan Ringan', 'class' => 'bg-yellow-100 text-yellow-700'],
                                'major_damage'      => ['label' => 'Kerusakan Berat',  'class' => 'bg-red-100 text-red-700'],
                                'needs_maintenance' => ['label' => 'Perlu Servis',     'class' => 'bg-orange-100 text-orange-700'],
                            ];
                            $conditionCfg = $conditionMap[$return->vehicle_condition] ?? ['label' => '-', 'class' => 'bg-gray-100 text-gray-500'];
                        @endphp
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $requester?->full_name ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $return->loanRequest?->unit?->name ?? '-' }}</p>
                                </div>
                                @if($isUnprocessed)
                                    <span class="text-xs font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">Belum</span>
                                @else
                                    <span class="text-xs font-semibold bg-green-100 text-green-700 px-2 py-0.5 rounded-full">✓ Proses</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">
                                {{ $return->loanRequest?->unit?->name ?? '-' }} ·
                                @if($vehicle) {{ $vehicle->brand }} {{ $vehicle->model }} · {{ $vehicle->plate_no }} @endif
                            </p>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $conditionCfg['class'] }}">
                                    {{ $conditionCfg['label'] }}
                                </span>
                                <a href="{{ route('admin.returns.show', $return) }}"
                                   class="text-xs text-blue-600 font-medium hover:underline">
                                    {{ $isUnprocessed ? 'Proses →' : 'Detail →' }}
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-gray-400">
                            <p>Tidak ada data pengembalian</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($returns->hasPages())
                    <div class="px-5 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>
                                Menampilkan {{ $returns->firstItem() }}–{{ $returns->lastItem() }}
                                dari {{ $returns->total() }} data
                            </span>
                            {{ $returns->links() }}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

