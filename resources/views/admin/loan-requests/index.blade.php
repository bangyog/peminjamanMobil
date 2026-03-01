<x-app-layout>
    <x-slot name="header">Kelola Pengajuan</x-slot>

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
            <h1 class="text-xl font-extrabold text-gray-800 leading-tight">Kelola Pengajuan Peminjaman</h1>
            <p class="text-sm text-gray-400 mt-0.5">Semua pengajuan kendaraan dari seluruh unit</p>
        </div>
        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl transition flex-shrink-0"
            style="background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0;"
            onmouseover="this.style.background='#e2e8f0';"
            onmouseout="this.style.background='#f1f5f9';">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>


    {{-- ========================= FILTER & SEARCH ========================= --}}
    <div class="bg-white rounded-2xl p-5 mb-5"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">

        <form method="GET" action="{{ route('admin.loan-requests.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

                {{-- Search --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                        Cari Pengajuan
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama peminjam..."
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
                    <a href="{{ route('admin.loan-requests.index') }}"
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
                    Total: <span class="font-bold text-gray-600">{{ $stats['all'] }}</span> pengajuan
                </p>
            </div>

        </form>
    </div>


    {{-- ========================= STATUS TABS ========================= --}}
    @php
        $statusTabs = [
            ['key' => null,              'label' => 'Semua',            'stat' => 'all',              'active_bg' => '#1e1b4b', 'active_text' => '#fff'],
            ['key' => 'submitted',       'label' => 'Submitted',        'stat' => 'submitted',        'active_bg' => '#d97706', 'active_text' => '#fff'],
            ['key' => 'approved_kepala', 'label' => 'Approved Kepala',  'stat' => 'approved_kepala',  'active_bg' => '#0052A3', 'active_text' => '#fff'],
            ['key' => 'approved_ga',     'label' => 'Approved GA',      'stat' => 'approved_ga',      'active_bg' => '#4338ca', 'active_text' => '#fff'],
            ['key' => 'assigned',        'label' => 'Assigned',         'stat' => 'assigned',         'active_bg' => '#6d28d9', 'active_text' => '#fff'],
            ['key' => 'in_use',          'label' => 'In Use',           'stat' => 'in_use',           'active_bg' => '#15803d', 'active_text' => '#fff'],
            ['key' => 'returned',        'label' => 'Returned',         'stat' => 'returned',         'active_bg' => '#475569', 'active_text' => '#fff'],
            ['key' => 'rejected',        'label' => 'Rejected',         'stat' => 'rejected',         'active_bg' => '#b91c1c', 'active_text' => '#fff'],
        ];
    @endphp

    <div class="flex flex-wrap gap-2 mb-5">
        @foreach($statusTabs as $tab)
        @php
            $isActive = request('status') === $tab['key'];
            $count    = $stats[$tab['stat']] ?? 0;
        @endphp
        <a href="{{ route('admin.loan-requests.index', $tab['key'] ? ['status' => $tab['key']] + request()->except('status') : request()->except('status')) }}"
            class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold rounded-xl transition-all duration-150"
            style="{{ $isActive
                ? 'background:' . $tab['active_bg'] . '; color:' . $tab['active_text'] . '; box-shadow:0 3px 10px rgba(0,0,0,0.2);'
                : 'background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0;' }}"
            @if(!$isActive)
            onmouseover="this.style.background='#e2e8f0';"
            onmouseout="this.style.background='#f1f5f9';"
            @endif
        >
            {{ $tab['label'] }}
            <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 text-xs font-extrabold rounded-full"
                style="{{ $isActive
                    ? 'background:rgba(255,255,255,0.25); color:#fff;'
                    : 'background:#e2e8f0; color:#475569;' }}">
                {{ $count }}
            </span>
        </a>
        @endforeach
    </div>


    {{-- ========================= TABEL / CARDS ========================= --}}
    <div class="bg-white rounded-2xl overflow-hidden"
        style="box-shadow:0 4px 24px rgba(0,0,0,0.06); border:1px solid #f0f4ff;">

        {{-- Table header bar --}}
        <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color:#f0f4ff; background:#fafbff;">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                    style="background:linear-gradient(135deg,#1e1b4b,#3730a3);">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="text-sm font-extrabold text-gray-700">Data Pengajuan</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                    style="background:#eff6ff; color:#1d4ed8;">
                    {{ $loanRequests->total() }} record
                </span>
            </div>
        </div>

        {{-- ===== DESKTOP TABLE ===== --}}
        @php
            $statusConfig = [
                'submitted'       => ['label' => 'Submitted',         'style' => 'background:#fffbeb; color:#92400e; border:1px solid #fde68a;'],
                'approved_kepala' => ['label' => 'Approved Kepala',   'style' => 'background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe;'],
                'approved_ga'     => ['label' => 'Approved GA',       'style' => 'background:#eef2ff; color:#3730a3; border:1px solid #c7d2fe;'],
                'assigned'        => ['label' => 'Assigned',          'style' => 'background:#faf5ff; color:#5b21b6; border:1px solid #ddd6fe;'],
                'in_use'          => ['label' => 'In Use',            'style' => 'background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;'],
                'returned'        => ['label' => 'Returned',          'style' => 'background:#f8fafc; color:#475569; border:1px solid #e2e8f0;'],
                'rejected'        => ['label' => 'Rejected',          'style' => 'background:#fef2f2; color:#991b1b; border:1px solid #fecaca;'],
            ];
        @endphp

        <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No.</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Peminjam</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Unit</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Keperluan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loanRequests as $loanRequest)
                    @php
                        $badge    = $statusConfig[$loanRequest->status] ?? ['label' => ucfirst($loanRequest->status), 'style' => 'background:#f8fafc; color:#475569; border:1px solid #e2e8f0;'];
                        $initials = strtoupper(substr($loanRequest->requester->full_name ?? 'U', 0, 2));
                        $avatarColors = [
                            ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                            ['bg' => '#dcfce7', 'text' => '#15803d'],
                            ['bg' => '#ede9fe', 'text' => '#6d28d9'],
                            ['bg' => '#fce7f3', 'text' => '#be185d'],
                            ['bg' => '#ffedd5', 'text' => '#c2410c'],
                        ];
                        $ac = $avatarColors[crc32($loanRequest->requester->full_name ?? '') % count($avatarColors)];
                    @endphp
                    <tr class="hover:bg-indigo-50/20 transition" style="border-bottom:1px solid #f8fafc;">

                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="text-xs font-mono font-bold text-gray-400">#{{ $loanRequest->id }}</span>
                        </td>

                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-extrabold flex-shrink-0"
                                    style="background:{{ $ac['bg'] }}; color:{{ $ac['text'] }};">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">
                                        {{ $loanRequest->requester->full_name ?? 'Unknown' }}
                                    </p>
                                    <p class="text-xs text-gray-400 truncate max-w-[140px]">
                                        {{ $loanRequest->requester->email ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-3.5 text-sm text-gray-600 whitespace-nowrap">
                            {{ $loanRequest->unit->name ?? '-' }}
                        </td>

                        <td class="px-5 py-3.5">
                            <p class="text-sm text-gray-700 max-w-[200px] truncate" title="{{ $loanRequest->purpose ?? '-' }}">
                                {{ Str::limit($loanRequest->purpose ?? '-', 45) }}
                            </p>
                        </td>

                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <p class="text-sm text-gray-700">{{ $loanRequest->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $loanRequest->created_at->format('H:i') }} WIB</p>
                        </td>

                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full"
                                style="{{ $badge['style'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </td>

                        <td class="px-5 py-3.5 whitespace-nowrap text-right">
                            <a href="{{ route('admin.loan-requests.show', $loanRequest->id) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-xl transition-all"
                                style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;"
                                onmouseover="this.style.background='#1d4ed8'; this.style.color='#fff'; this.style.borderColor='#1d4ed8';"
                                onmouseout="this.style.background='#eff6ff'; this.style.color='#1d4ed8'; this.style.borderColor='#bfdbfe';">
                                Detail
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
                                    style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                                    <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-500 text-base">Tidak ada pengajuan ditemukan</p>
                                <p class="text-sm text-gray-400 mt-1">Coba ubah filter atau kata kunci pencarian</p>
                                @if(request('search') || request('status'))
                                <a href="{{ route('admin.loan-requests.index') }}"
                                    class="inline-flex items-center gap-2 mt-4 px-4 py-2 text-sm font-semibold rounded-xl"
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

        {{-- ===== MOBILE CARDS ===== --}}
        <div class="sm:hidden divide-y" style="border-color:#f8fafc;">
            @forelse($loanRequests as $loanRequest)
            @php
                $badge    = $statusConfig[$loanRequest->status] ?? ['label' => ucfirst($loanRequest->status), 'style' => 'background:#f8fafc; color:#475569; border:1px solid #e2e8f0;'];
                $initials = strtoupper(substr($loanRequest->requester->full_name ?? 'U', 0, 2));
                $avatarColors = [
                    ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                    ['bg' => '#dcfce7', 'text' => '#15803d'],
                    ['bg' => '#ede9fe', 'text' => '#6d28d9'],
                    ['bg' => '#fce7f3', 'text' => '#be185d'],
                    ['bg' => '#ffedd5', 'text' => '#c2410c'],
                ];
                $ac = $avatarColors[crc32($loanRequest->requester->full_name ?? '') % count($avatarColors)];
            @endphp
            <a href="{{ route('admin.loan-requests.show', $loanRequest->id) }}"
                class="flex items-start gap-3 px-4 py-4 hover:bg-indigo-50/20 transition">

                {{-- Avatar --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xs font-extrabold flex-shrink-0 mt-0.5"
                    style="background:{{ $ac['bg'] }}; color:{{ $ac['text'] }};">
                    {{ $initials }}
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <p class="text-sm font-bold text-gray-800 truncate">
                            {{ $loanRequest->requester->full_name ?? 'Unknown' }}
                        </p>
                        <span class="text-xs font-mono text-gray-400 flex-shrink-0">#{{ $loanRequest->id }}</span>
                    </div>
                    <p class="text-xs text-gray-500 truncate mb-2">
                        {{ $loanRequest->unit->name ?? '-' }} · {{ $loanRequest->created_at->format('d M Y') }}
                    </p>
                    <p class="text-xs text-gray-600 truncate mb-2">
                        {{ Str::limit($loanRequest->purpose ?? '-', 55) }}
                    </p>
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full"
                        style="{{ $badge['style'] }}">
                        {{ $badge['label'] }}
                    </span>
                </div>

                {{-- Chevron --}}
                <svg class="w-4 h-4 text-gray-300 flex-shrink-0 mt-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @empty
            <div class="px-4 py-14 flex flex-col items-center justify-center text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                    style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                    <svg class="w-7 h-7 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="font-bold text-gray-400">Tidak ada pengajuan ditemukan</p>
            </div>
            @endforelse
        </div>

        {{-- ===== PAGINATION ===== --}}
        @if($loanRequests->hasPages())
        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
            style="border-top:1px solid #f0f4ff; background:#fafbff;">
            <p class="text-xs text-gray-400">
                Menampilkan
                <span class="font-bold text-gray-600">{{ $loanRequests->firstItem() }}–{{ $loanRequests->lastItem() }}</span>
                dari <span class="font-bold text-gray-600">{{ $loanRequests->total() }}</span> pengajuan
            </p>
            <div>{{ $loanRequests->appends(request()->query())->links() }}</div>
        </div>
        @endif

    </div>

</x-app-layout>