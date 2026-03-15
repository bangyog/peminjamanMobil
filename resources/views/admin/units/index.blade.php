<x-app-layout>
    <x-slot name="header">Manajemen Unit</x-slot>

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

                <h3 class="text-lg font-extrabold text-gray-800 mb-1">Hapus Unit?</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Unit <span class="font-bold text-red-600" x-text="deleteName"></span> akan dihapus permanen beserta semua data terkait.
                </p>

                <div class="flex gap-3">
                    <button @click="showDelete = false"
                        class="flex-1 py-2.5 text-sm font-bold rounded-xl transition"
                        style="background:#f1f5f9; color:#64748b;"
                        onmouseover="this.style.background='#e2e8f0';"
                        onmouseout="this.style.background='#f1f5f9';">
                        Batal
                    </button>
                    <form :action="'{{ url('admin/units') }}/' + deleteId" method="POST" class="flex-1">
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
            <h1 class="text-xl font-extrabold text-gray-800 leading-tight">Manajemen Unit</h1>
            <p class="text-sm text-gray-400 mt-0.5">Kelola seluruh unit / departemen perusahaan</p>
        </div>
        <a href="{{ route('admin.units.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white rounded-xl transition-all flex-shrink-0"
            style="background:linear-gradient(135deg,#0052A3,#0066CC); box-shadow:0 4px 14px rgba(0,82,163,0.35);"
            onmouseover="this.style.boxShadow='0 6px 20px rgba(0,82,163,0.5)'; this.style.transform='translateY(-1px)';"
            onmouseout="this.style.boxShadow='0 4px 14px rgba(0,82,163,0.35)'; this.style.transform='translateY(0)';">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Unit
        </a>
    </div>

    {{-- ========================= STATS ========================= --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        {{-- Total Unit --}}
        <div class="rounded-2xl p-5 relative overflow-hidden"
            style="background:linear-gradient(135deg,#1e1b4b,#3730a3); box-shadow:0 4px 20px rgba(55,48,163,0.25);">
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10" style="background:#fff;"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-indigo-300 uppercase tracking-wider mb-1">Total Unit</p>
                <p class="text-3xl font-extrabold text-white">{{ $stats['total'] }}</p>
                <p class="text-xs text-indigo-300 mt-1">Semua departemen</p>
            </div>
        </div>

        {{-- Aktif --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #bbf7d0;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Aktif</p>
                    <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $stats['active'] }}</p>
                    <p class="text-xs text-green-500 font-semibold mt-1">Beroperasi</p>
                </div>
                <div class="p-3 rounded-xl" style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>



        {{-- Punya Kepala --}}
        <div class="bg-white rounded-2xl p-5 hover:shadow-md transition"
            style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #bfdbfe;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Punya Kepala</p>
                    <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $stats['with_kepala'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Ada kepala dept.</p>
                </div>
                <div class="p-3 rounded-xl" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- ========================= FILTER ========================= --}}
    <div class="bg-white rounded-2xl p-5 mb-6"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">

        <form method="GET" action="{{ route('admin.units.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

                {{-- Search --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                        Cari Unit
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Nama unit atau departemen..."
                            class="w-full pl-9 pr-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            style="border-color:#e2e8f0; background:#fafbff;">
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
                    <a href="{{ route('admin.units.index') }}"
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
                    Total: <span class="font-bold text-gray-600">{{ $stats['total'] }}</span> unit
                </p>
            </div>

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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="text-sm font-extrabold text-gray-700">Data Unit</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                    style="background:#eff6ff; color:#1d4ed8;">
                    {{ $units->total() }} unit
                </span>
            </div>
        </div>

        {{-- ===== DESKTOP TABLE ===== --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Unit</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kepala Dept.</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Total User</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Pengajuan</th>
                        <!-- <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th> -->
                        <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    @php
                        $isActive   = $unit->is_active;
                        $hasAdminGA = !$unit->kepalaDepartemen && $unit->users()->where('role','admin_ga')->exists();
                        $avatarColors = [
                            ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                            ['bg' => '#dcfce7', 'text' => '#15803d'],
                            ['bg' => '#ede9fe', 'text' => '#6d28d9'],
                            ['bg' => '#fce7f3', 'text' => '#be185d'],
                            ['bg' => '#ffedd5', 'text' => '#c2410c'],
                            ['bg' => '#f0fdf4', 'text' => '#166534'],
                        ];
                        $ac = $avatarColors[crc32($unit->name) % count($avatarColors)];
                        $initials = strtoupper(substr($unit->name, 0, 2));
                    @endphp
                    <tr class="hover:bg-indigo-50/20 transition {{ !$isActive ? 'opacity-60' : '' }}"
                        style="border-bottom:1px solid #f8fafc;">

                        {{-- Nama Unit --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-extrabold flex-shrink-0"
                                    style="background:{{ $ac['bg'] }}; color:{{ $ac['text'] }};">
                                    {{ $initials }}
                                </div>
                                <p class="text-sm font-bold text-gray-800">{{ $unit->name }}</p>
                            </div>
                        </td>

                        {{-- Kepala Departemen --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($unit->kepalaDepartemen)
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"
                                    style="background:#dbeafe; color:#1d4ed8;">
                                    {{ strtoupper(substr($unit->kepalaDepartemen->full_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $unit->kepalaDepartemen->full_name }}</p>
                                    <p class="text-xs text-gray-400 truncate max-w-[140px]">{{ $unit->kepalaDepartemen->email }}</p>
                                </div>
                            </div>
                            @elseif($hasAdminGA)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full"
                                style="background:#faf5ff; color:#5b21b6; border:1px solid #ddd6fe;">
                                🛡️ Unit Admin GA
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full"
                                style="background:#fef2f2; color:#991b1b; border:1px solid #fecaca;">
                                ⚠️ Belum ada kepala
                            </span>
                            @endif
                        </td>

                        {{-- Total User --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-extrabold"
                                    style="background:#eff6ff; color:#1d4ed8;">
                                    {{ $unit->users_count ?? 0 }}
                                </span>
                                <span class="text-xs text-gray-400">orang</span>
                            </div>
                        </td>

                        {{-- Pengajuan --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-extrabold"
                                    style="background:#f5f3ff; color:#5b21b6;">
                                    {{ $unit->loan_requests_count ?? 0 }}
                                </span>
                                <span class="text-xs text-gray-400">pengajuan</span>
                            </div>
                        </td>

                        {{-- Status --}}
                        <!-- <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($isActive)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full"
                                style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full"
                                style="background:#fef2f2; color:#991b1b; border:1px solid #fecaca;">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Tidak Aktif
                            </span>
                            @endif
                        </td> -->

                        {{-- Aksi --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">



                                {{-- Edit --}}
                                <a href="{{ route('admin.units.edit', $unit) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-xl transition-all"
                                    style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;"
                                    onmouseover="this.style.background='#1d4ed8'; this.style.color='#fff'; this.style.borderColor='#1d4ed8';"
                                    onmouseout="this.style.background='#eff6ff'; this.style.color='#1d4ed8'; this.style.borderColor='#bfdbfe';"
                                    title="Edit Unit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>

                                {{-- Hapus --}}
                                <button
                                    @click="openDelete({{ $unit->id }}, '{{ addslashes($unit->name) }}')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-xl transition-all"
                                    style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;"
                                    onmouseover="this.style.background='#dc2626'; this.style.color='#fff'; this.style.borderColor='#dc2626';"
                                    onmouseout="this.style.background='#fef2f2'; this.style.color='#dc2626'; this.style.borderColor='#fecaca';"
                                    title="Hapus Unit">
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
                        <td colspan="6" class="px-6 py-16">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
                                    style="background:linear-gradient(135deg,#f0f4ff,#e0e7ff);">
                                    <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-500 text-base">Tidak ada data unit</p>
                                <p class="text-sm text-gray-400 mt-1">Tambah unit baru dengan tombol di atas</p>
                                <a href="{{ route('admin.units.create') }}"
                                    class="inline-flex items-center gap-2 mt-4 px-4 py-2.5 text-sm font-bold text-white rounded-xl"
                                    style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Unit
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
            @forelse($units as $unit)
            @php
                $isActive   = $unit->is_active;
                $hasAdminGA = !$unit->kepalaDepartemen && $unit->users()->where('role','admin_ga')->exists();
                $avatarColors = [
                    ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                    ['bg' => '#dcfce7', 'text' => '#15803d'],
                    ['bg' => '#ede9fe', 'text' => '#6d28d9'],
                    ['bg' => '#fce7f3', 'text' => '#be185d'],
                    ['bg' => '#ffedd5', 'text' => '#c2410c'],
                    ['bg' => '#f0fdf4', 'text' => '#166534'],
                ];
                $ac = $avatarColors[crc32($unit->name) % count($avatarColors)];
                $initials = strtoupper(substr($unit->name, 0, 2));
            @endphp
            <div class="px-4 py-4 {{ !$isActive ? 'opacity-60' : '' }}">
                <div class="flex items-start gap-3">

                    {{-- Avatar --}}
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xs font-extrabold flex-shrink-0"
                        style="background:{{ $ac['bg'] }}; color:{{ $ac['text'] }};">
                        {{ $initials }}
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <p class="text-sm font-bold text-gray-800 truncate">{{ $unit->name }}</p>
                            @if($isActive)
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 text-xs font-bold rounded-full flex-shrink-0"
                                style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;">
                                ✓ Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 text-xs font-bold rounded-full flex-shrink-0"
                                style="background:#fef2f2; color:#991b1b; border:1px solid #fecaca;">
                                ✗ Nonaktif
                            </span>
                            @endif
                        </div>

                        {{-- Kepala --}}
                        <div class="mb-2">
                            @if($unit->kepalaDepartemen)
                            <p class="text-xs text-gray-600">
                                👤 <span class="font-semibold">{{ $unit->kepalaDepartemen->full_name }}</span>
                            </p>
                            @elseif($hasAdminGA)
                            <p class="text-xs font-semibold" style="color:#5b21b6;">🛡️ Unit Admin GA</p>
                            @else
                            <p class="text-xs font-semibold text-red-500">⚠️ Belum ada kepala</p>
                            @endif
                        </div>

                        {{-- Stats chips --}}
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs px-2 py-0.5 rounded-lg font-semibold"
                                style="background:#eff6ff; color:#1d4ed8;">
                                👥 {{ $unit->users_count ?? 0 }} orang
                            </span>
                            <span class="text-xs px-2 py-0.5 rounded-lg font-semibold"
                                style="background:#f5f3ff; color:#5b21b6;">
                                📋 {{ $unit->loan_requests_count ?? 0 }} pengajuan
                            </span>
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.units.show', $unit) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1 py-2 text-xs font-bold rounded-xl"
                                style="background:#f8fafc; color:#475569; border:1px solid #e2e8f0;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                            <a href="{{ route('admin.units.edit', $unit) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1 py-2 text-xs font-bold rounded-xl"
                                style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <button
                                @click="openDelete({{ $unit->id }}, '{{ addslashes($unit->name) }}')"
                                class="flex-1 inline-flex items-center justify-center gap-1 py-2 text-xs font-bold rounded-xl"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <p class="font-bold text-gray-400">Tidak ada data unit</p>
            </div>
            @endforelse
        </div>

        {{-- ===== PAGINATION ===== --}}
        @if($units->hasPages())
        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
            style="border-top:1px solid #f0f4ff; background:#fafbff;">
            <p class="text-xs text-gray-400">
                Menampilkan
                <span class="font-bold text-gray-600">{{ $units->firstItem() }}–{{ $units->lastItem() }}</span>
                dari <span class="font-bold text-gray-600">{{ $units->total() }}</span> unit
            </p>
            <div>{{ $units->appends(request()->query())->links() }}</div>
        </div>
        @endif

    </div>

    </div>{{-- END x-data wrapper --}}

</x-app-layout>