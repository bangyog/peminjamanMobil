<x-app-layout>
    <x-slot name="header">Manajemen Kendaraan</x-slot>

    {{-- ========================= DELETE MODAL (Alpine.js) ========================= --}}
    <div x-data="{
            showDelete: false,
            deleteId: null,
            deleteName: '',
            openDelete(id, name) { this.deleteId = id; this.deleteName = name; this.showDelete = true; }
        }"
        @keydown.escape.window="showDelete = false">

        {{-- Overlay + Modal --}}
        <div x-show="showDelete"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background:rgba(0,0,0,0.45); backdrop-filter:blur(4px);"
            x-cloak>

            <div x-show="showDelete"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                @click.outside="showDelete = false"
                class="bg-white rounded-3xl p-6 w-full max-w-sm text-center"
                style="box-shadow:0 25px 60px rgba(0,0,0,0.2);">

                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4"
                    style="background:linear-gradient(135deg,#fef2f2,#fee2e2);">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>

                <h3 class="text-lg font-extrabold text-gray-800 mb-1">Hapus Kendaraan?</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Kendaraan <span class="font-bold text-red-600" x-text="deleteName"></span> akan dihapus permanen dan tidak bisa dikembalikan.
                </p>

                <div class="flex gap-3">
                    <button @click="showDelete = false"
                        class="flex-1 py-2.5 text-sm font-bold rounded-xl transition"
                        style="background:#f1f5f9; color:#64748b;"
                        onmouseover="this.style.background='#e2e8f0';"
                        onmouseout="this.style.background='#f1f5f9';">
                        Batal
                    </button>

                    {{-- Dynamic form submit --}}
                    <form :action="'{{ url('admin/vehicles') }}/' + deleteId" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full py-2.5 text-sm font-bold text-white rounded-xl transition"
                            style="background:linear-gradient(135deg,#dc2626,#ef4444); box-shadow:0 4px 12px rgba(220,38,38,0.35);"
                            onmouseover="this.style.boxShadow='0 6px 18px rgba(220,38,38,0.5)';"
                            onmouseout="this.style.boxShadow='0 4px 12px rgba(220,38,38,0.35)';">
                            Ya, Hapus
                        </button>
                    </form>
                </div>

            </div>
        </div>

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
            <h1 class="text-xl font-extrabold text-gray-800 leading-tight">Manajemen Kendaraan</h1>
            <p class="text-sm text-gray-400 mt-0.5">Kelola seluruh armada kendaraan perusahaan</p>
        </div>
        <a href="{{ route('admin.vehicles.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white rounded-xl transition-all flex-shrink-0"
            style="background:linear-gradient(135deg,#0052A3,#0066CC); box-shadow:0 4px 14px rgba(0,82,163,0.35);"
            onmouseover="this.style.boxShadow='0 6px 20px rgba(0,82,163,0.5)'; this.style.transform='translateY(-1px)';"
            onmouseout="this.style.boxShadow='0 4px 14px rgba(0,82,163,0.35)'; this.style.transform='translateY(0)';">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kendaraan
        </a>
    </div>

    {{-- ========================= STATS ========================= --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        {{-- Total --}}
        <div class="rounded-2xl p-5 relative overflow-hidden"
            style="background:linear-gradient(135deg,#1e1b4b,#3730a3); box-shadow:0 4px 20px rgba(55,48,163,0.25);">
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10" style="background:#fff;"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-indigo-300 uppercase tracking-wider mb-1">Total Armada</p>
                <p class="text-3xl font-extrabold text-white">{{ $stats['total'] }}</p>
                <p class="text-xs text-indigo-300 mt-1">Semua kendaraan</p>
            </div>
        </div>

        {{-- Tersedia --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #bbf7d0;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Tersedia</p>
                    <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $stats['available'] }}</p>
                    <p class="text-xs text-green-500 font-semibold mt-1">Siap dipinjam</p>
                </div>
                <div class="p-3 rounded-xl" style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Sedang Digunakan --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #bfdbfe;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Digunakan</p>
                    <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $stats['in_use'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Aktif dipakai</p>
                </div>
                <div class="p-3 rounded-xl" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Maintenance --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #fde68a;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Maintenance</p>
                    <p class="text-3xl font-extrabold text-amber-500 mt-1">{{ $stats['maintenance'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Dalam perbaikan</p>
                </div>
                <div class="p-3 rounded-xl" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);">
                    <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- ========================= FILTER ========================= --}}
    <div class="bg-white rounded-2xl p-5 mb-6"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">

        <form method="GET" action="{{ route('admin.vehicles.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

                {{-- Search --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                        Cari Kendaraan
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Merek, model, nomor polisi..."
                            class="w-full pl-9 pr-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            style="border-color:#e2e8f0; background:#fafbff;">
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                        Status
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <select name="status"
                            class="w-full pl-9 pr-8 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition appearance-none"
                            style="border-color:#e2e8f0; background:#fafbff;">
                            <option value="">Semua Status</option>
                            <option value="available"   {{ request('status') === 'available'   ? 'selected' : '' }}>✅ Tersedia</option>
                            <option value="in_use"      {{ request('status') === 'in_use'      ? 'selected' : '' }}>🚗 Sedang Digunakan</option>
                            <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>🔧 Maintenance</option>
                            <option value="retired"     {{ request('status') === 'retired'     ? 'selected' : '' }}>⛔ Tidak Aktif</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-between gap-3 flex-wrap pt-4" style="border-top:1px dashed #e2e8f0;">
                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white rounded-xl transition"
                        style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </button>
                    @if(request('search') || request('status'))
                    <a href="{{ route('admin.vehicles.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold rounded-xl transition"
                        style="background:#f1f5f9; color:#64748b;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset
                    </a>
                    @endif
                </div>
                <p class="text-xs text-gray-400">
                    Total: <span class="font-bold text-gray-600">{{ $stats['total'] }}</span> kendaraan
                </p>
            </div>

        </form>
    </div>

    {{-- ========================= TABEL ========================= --}}
    @php
        $statusConfig = [
            'available'   => ['label' => 'Tersedia',    'style' => 'background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;'],
            'in_use'      => ['label' => 'Digunakan',   'style' => 'background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;'],
            'maintenance' => ['label' => 'Maintenance', 'style' => 'background:#fffbeb; color:#92400e; border:1px solid #fde68a;'],
            'retired'     => ['label' => 'Tidak Aktif', 'style' => 'background:#f8fafc; color:#475569; border:1px solid #e2e8f0;'],
        ];
        $statusIcons = [
            'available'   => '✅',
            'in_use'      => '🚗',
            'maintenance' => '🔧',
            'retired'     => '⛔',
        ];
    @endphp

    <div class="bg-white rounded-2xl overflow-hidden"
        style="box-shadow:0 4px 24px rgba(0,0,0,0.06); border:1px solid #f0f4ff;">

        {{-- Table header bar --}}
        <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color:#f0f4ff; background:#fafbff;">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                    style="background:linear-gradient(135deg,#1e1b4b,#3730a3);">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-sm font-extrabold text-gray-700">Data Kendaraan</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                    style="background:#eff6ff; color:#1d4ed8;">
                    {{ $vehicles->total() }} unit
                </span>
            </div>
        </div>

        {{-- ===== DESKTOP TABLE ===== --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kode Unit</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nomor Polisi</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kapasitas</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Odometer</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                    @php
                        $badge = $statusConfig[$vehicle->status] ?? ['label' => ucfirst($vehicle->status), 'style' => 'background:#f8fafc; color:#475569; border:1px solid #e2e8f0;'];
                        $icon  = $statusIcons[$vehicle->status] ?? '🚗';
                        $isRetired = $vehicle->status === 'retired';
                    @endphp
                    <tr class="hover:bg-indigo-50/20 transition {{ $isRetired ? 'opacity-60' : '' }}"
                        style="border-bottom:1px solid #f8fafc;">

                        {{-- Kode Unit --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="text-xs font-mono font-bold px-2 py-1 rounded-lg"
                                style="background:#f0f4ff; color:#4338ca;">
                                {{ $vehicle->unit_code ?? '-' }}
                            </span>
                        </td>

                        {{-- Kendaraan --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base flex-shrink-0"
                                    style="background:#f0f4ff;">
                                    🚗
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $vehicle->brand }}</p>
                                    <p class="text-xs text-gray-400">{{ $vehicle->model }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Nomor Polisi --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-extrabold font-mono rounded-lg"
                                style="background:#1e1b4b; color:#e0e7ff; letter-spacing:0.05em;">
                                {{ $vehicle->plate_no }}
                            </span>
                        </td>

                        {{-- Kapasitas --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="flex items-center gap-1 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="font-semibold">{{ $vehicle->seat_capacity ?? '-' }}</span>
                                <span class="text-gray-400">orang</span>
                            </div>
                        </td>

                        {{-- Odometer --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <p class="text-sm font-semibold text-gray-700">
                                {{ number_format($vehicle->odometer_km) }} km
                            </p>
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full"
                                style="{{ $badge['style'] }}">
                                {{ $icon }} {{ $badge['label'] }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">

                                <!-- {{-- Detail --}}
                                <a href="{{ route('admin.vehicles.show', $vehicle) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-xl transition-all"
                                    style="background:#f8fafc; color:#475569; border:1px solid #e2e8f0;"
                                    onmouseover="this.style.background='#e2e8f0';"
                                    onmouseout="this.style.background='#f8fafc';"
                                    title="Lihat Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a> -->

                                {{-- Edit --}}
                                <a href="{{ route('admin.vehicles.edit', $vehicle) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-xl transition-all"
                                    style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;"
                                    onmouseover="this.style.background='#1d4ed8'; this.style.color='#fff'; this.style.borderColor='#1d4ed8';"
                                    onmouseout="this.style.background='#eff6ff'; this.style.color='#1d4ed8'; this.style.borderColor='#bfdbfe';"
                                    title="Edit Kendaraan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>

                                {{-- Hapus — trigger Alpine modal --}}
                                <button
                                    @click="openDelete({{ $vehicle->id }}, '{{ addslashes($vehicle->brand . ' ' . $vehicle->model) }} ({{ $vehicle->plate_no }})')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-xl transition-all"
                                    style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;"
                                    onmouseover="this.style.background='#dc2626'; this.style.color='#fff'; this.style.borderColor='#dc2626';"
                                    onmouseout="this.style.background='#fef2f2'; this.style.color='#dc2626'; this.style.borderColor='#fecaca';"
                                    title="Hapus Kendaraan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
                                    style="background:linear-gradient(135deg,#f0f4ff,#e0e7ff);">
                                    <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-500 text-base">Tidak ada data kendaraan</p>
                                <p class="text-sm text-gray-400 mt-1">Tambah kendaraan baru dengan tombol di atas</p>
                                <a href="{{ route('admin.vehicles.create') }}"
                                    class="inline-flex items-center gap-2 mt-4 px-4 py-2.5 text-sm font-bold text-white rounded-xl"
                                    style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Kendaraan
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== MOBILE CARDS ===== --}}
        <div class="sm:hidden divide-y" style="border-color:#f8fafc;">
            @forelse($vehicles as $vehicle)
            @php
                $badge    = $statusConfig[$vehicle->status] ?? ['label' => ucfirst($vehicle->status), 'style' => 'background:#f8fafc; color:#475569; border:1px solid #e2e8f0;'];
                $icon     = $statusIcons[$vehicle->status] ?? '🚗';
                $isRetired = $vehicle->status === 'retired';
            @endphp
            <div class="px-4 py-4 {{ $isRetired ? 'opacity-60' : '' }}">
                <div class="flex items-start gap-3">

                    {{-- Vehicle icon --}}
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-base flex-shrink-0"
                        style="background:#f0f4ff;">🚗</div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                                <p class="text-xs text-gray-400">{{ $vehicle->unit_code ?? '-' }}</p>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold rounded-full flex-shrink-0"
                                style="{{ $badge['style'] }}">
                                {{ $icon }} {{ $badge['label'] }}
                            </span>
                        </div>

                        {{-- Plate + info --}}
                        <div class="flex flex-wrap items-center gap-2 mt-2 mb-3">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-extrabold font-mono rounded-lg"
                                style="background:#1e1b4b; color:#e0e7ff;">
                                {{ $vehicle->plate_no }}
                            </span>
                            <span class="text-xs text-gray-500">
                                👥 {{ $vehicle->seat_capacity ?? '-' }} orang
                            </span>
                            <span class="text-xs text-gray-500">
                                🛣️ {{ number_format($vehicle->odometer_km) }} km
                            </span>
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.vehicles.show', $vehicle) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1 py-2 text-xs font-bold rounded-xl transition"
                                style="background:#f8fafc; color:#475569; border:1px solid #e2e8f0;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                            <a href="{{ route('admin.vehicles.edit', $vehicle) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1 py-2 text-xs font-bold rounded-xl transition"
                                style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <button
                                @click="openDelete({{ $vehicle->id }}, '{{ addslashes($vehicle->brand . ' ' . $vehicle->model) }} ({{ $vehicle->plate_no }})')"
                                class="flex-1 inline-flex items-center justify-center gap-1 py-2 text-xs font-bold rounded-xl transition"
                                style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            @empty
            <div class="px-4 py-14 flex flex-col items-center justify-center text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                    style="background:linear-gradient(135deg,#f0f4ff,#e0e7ff);">
                    <svg class="w-7 h-7 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <p class="font-bold text-gray-400">Tidak ada data kendaraan</p>
            </div>
            @endforelse
        </div>

        {{-- ===== PAGINATION ===== --}}
        @if($vehicles->hasPages())
        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
            style="border-top:1px solid #f0f4ff; background:#fafbff;">
            <p class="text-xs text-gray-400">
                Menampilkan
                <span class="font-bold text-gray-600">{{ $vehicles->firstItem() }}–{{ $vehicles->lastItem() }}</span>
                dari <span class="font-bold text-gray-600">{{ $vehicles->total() }}</span> kendaraan
            </p>
            <div>{{ $vehicles->appends(request()->query())->links() }}</div>
        </div>
        @endif

    </div>

    </div>{{-- END x-data wrapper --}}

</x-app-layout>