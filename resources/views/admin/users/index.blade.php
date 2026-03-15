<x-app-layout>
    <x-slot name="header">Manajemen Users</x-slot>

    {{-- ===== PAGE HERO BAR ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-gray-800 leading-tight">Manajemen Users</h1>
            <p class="text-sm text-gray-400 mt-0.5">Kelola seluruh akun pengguna sistem</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white rounded-xl shadow-lg transition flex-shrink-0"
            style="background: linear-gradient(135deg, #0052A3, #0066CC);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Tambah User
        </a>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <div class="bg-white rounded-2xl p-5 mb-6"
        style="box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #f0f4ff;">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

                {{-- Search --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Cari User</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Nama, Email, No. Telp..."
                            class="w-full pl-9 pr-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            style="border-color:#e2e8f0; background:#fafbff;">
                    </div>
                </div>

 

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Status</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <select name="status"
                            class="w-full pl-9 pr-8 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition appearance-none"
                            style="border-color:#e2e8f0; background:#fafbff;">
                            <option value="">Semua Status</option>
                            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>✅ Aktif</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>❌ Nonaktif</option>
                        </select>
                    </div>
                </div>

            </div>

            {{-- Filter Actions --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white rounded-xl transition"
                    style="background: linear-gradient(135deg, #0052A3, #0066CC);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Terapkan Filter
                </button>
                @if(request('search') || request('role') || request('unit_id') || request('status'))
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl transition"
                    style="background:#f1f5f9; color:#64748b;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset
                </a>
                @endif
            </div>

            {{-- Active filter tags --}}
            @if(request('search') || request('role') || request('unit_id') || request('status'))
            <div class="flex items-center gap-2 flex-wrap mt-3 pt-3" style="border-top:1px dashed #e2e8f0;">
                <span class="text-xs text-gray-400">Filter aktif:</span>
                @if(request('search'))
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#eff6ff; color:#1d4ed8;">
                    🔍 "{{ request('search') }}"
                </span>
                @endif
                @if(request('role'))
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#faf5ff; color:#6d28d9;">
                    👤 {{ request('role') }}
                </span>
                @endif
                @if(request('unit_id'))
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#f0fdf4; color:#15803d;">
                    🏢 Unit #{{ request('unit_id') }}
                </span>
                @endif
                @if(request('status'))
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full"
                    style="{{ request('status') === 'active' ? 'background:#f0fdf4; color:#15803d;' : 'background:#fef2f2; color:#dc2626;' }}">
                    {{ request('status') === 'active' ? '✅ Aktif' : '❌ Nonaktif' }}
                </span>
                @endif
            </div>
            @endif

        </form>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-bold text-gray-700">Daftar User</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                    style="background:#eff6ff; color:#1d4ed8;">
                    {{ $users->total() }} user
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr style="background:#fafbff; border-bottom:1px solid #f0f4ff;">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">User</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Unit</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kontak</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Role</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    @php
                        $initials = strtoupper(substr($user->full_name, 0, 2));
                        $avatarColors = [
                            ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                            ['bg' => '#dcfce7', 'text' => '#15803d'],
                            ['bg' => '#ede9fe', 'text' => '#6d28d9'],
                            ['bg' => '#fce7f3', 'text' => '#be185d'],
                            ['bg' => '#ffedd5', 'text' => '#c2410c'],
                            ['bg' => '#e0f2fe', 'text' => '#0369a1'],
                        ];
                        $avatarColor = $avatarColors[crc32($user->full_name) % count($avatarColors)];

$roleCfg = match($user->role) {
    'admin_ga'          => ['bg-indigo-50 text-indigo-700 border border-indigo-200',   '🏢 Admin GA'],
    'admin_hr'          => ['bg-purple-50 text-purple-700 border border-purple-200',   '🧑‍💼 Admin HR'],
    'kepala_departemen' => ['bg-blue-50 text-blue-700 border border-blue-200',         '🎖️ Kepala Dept'],
    'user'              => ['bg-slate-50 text-slate-600 border border-slate-200',      '👤 User'],
    default             => ['bg-gray-50 text-gray-500 border border-gray-200',         $user->role],
};
                    @endphp
                    <tr class="hover:bg-blue-50/30 transition-colors" style="border-bottom:1px solid #f8fafc;">

                        {{-- User --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-extrabold flex-shrink-0"
                                    style="background:{{ $avatarColor['bg'] }}; color:{{ $avatarColor['text'] }};">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $user->full_name }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $user->email }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- Unit --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($user->unit)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-lg"
                                style="background:#f0f9ff; color:#0369a1; border:1px solid #bae6fd;">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ $user->unit->name }}
                            </span>
                            @else
                            <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>

                        {{-- Kontak --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($user->phone)
                            <p class="text-sm text-gray-700 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $user->phone }}
                            </p>
                            @else
                            <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>

                        {{-- Role --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $roleCfg[0] }}">
                                {{ $roleCfg[1] }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($user->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full"
                                style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full"
                                style="background:#fef2f2; color:#dc2626; border:1px solid #fca5a5;">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span>
                                Nonaktif
                            </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-3.5 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1.5">

                                {{-- Edit --}}
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl transition-all duration-150"
                                    style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;"
                                    onmouseover="this.style.background='#1d4ed8'; this.style.color='#fff'; this.style.borderColor='#1d4ed8';"
                                    onmouseout="this.style.background='#eff6ff'; this.style.color='#1d4ed8'; this.style.borderColor='#bfdbfe';">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>

                                {{-- Hapus --}}
                                @if($user->id !== auth()->id())
                                <button
                                    type="button"
                                    @click="$dispatch('open-delete-modal', {
                                        name:   '{{ addslashes($user->full_name) }}',
                                        formId: 'delete-form-{{ $user->id }}'
                                    })"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl transition-all duration-150"
                                    style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;"
                                    onmouseover="this.style.background='#dc2626'; this.style.color='#fff'; this.style.borderColor='#dc2626';"
                                    onmouseout="this.style.background='#fef2f2'; this.style.color='#dc2626'; this.style.borderColor='#fecaca';">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                                @else
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl cursor-not-allowed"
                                    style="background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#3b82f6; border:1px solid #bfdbfe;"
                                    title="Tidak dapat menghapus akun Anda sendiri">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Anda
                                </div>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
                                    style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                                    <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-500 text-base">Tidak ada user ditemukan</p>
                                <p class="text-sm text-gray-400 mt-1 max-w-xs">
                                    @if(request('search') || request('role') || request('unit_id') || request('status'))
                                        Coba ubah kata kunci atau filter yang digunakan
                                    @else
                                        Belum ada user yang terdaftar di sistem
                                    @endif
                                </p>
                                @if(request('search') || request('role') || request('unit_id') || request('status'))
                                <a href="{{ route('admin.users.index') }}"
                                    class="inline-flex items-center gap-2 mt-4 px-4 py-2 text-sm font-semibold rounded-xl"
                                    style="background:#f1f5f9; color:#475569;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Reset Filter
                                </a>
                                @else
                                <a href="{{ route('admin.users.create') }}"
                                    class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 text-sm font-bold text-white rounded-xl"
                                    style="background:linear-gradient(135deg,#0052A3,#0066CC);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    Tambah User Pertama
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
        @if($users->hasPages())
        <div class="px-6 py-4 flex items-center justify-between"
            style="border-top:1px solid #f0f4ff; background:#fafbff;">
            <p class="text-xs text-gray-400">
                Menampilkan
                <span class="font-semibold text-gray-600">{{ $users->firstItem() }}–{{ $users->lastItem() }}</span>
                dari <span class="font-semibold text-gray-600">{{ $users->total() }}</span> user
            </p>
            <div>{{ $users->links() }}</div>
        </div>
        @endif

    </div>


    {{-- =============================================
         CUSTOM DELETE MODAL (Alpine.js)
         ============================================= --}}
    <div x-data="deleteUserModal()" x-on:open-delete-modal.window="open($event.detail)">

        {{-- Backdrop --}}
        <div
            x-show="isOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40"
            style="background:rgba(15,23,42,0.5); backdrop-filter:blur(3px);"
            x-cloak
        ></div>

        {{-- Modal --}}
        <div
            x-show="isOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-3"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            @keydown.escape.window="isOpen = false"
            x-cloak
        >
            <div class="w-full max-w-sm rounded-2xl overflow-hidden"
                style="background:#fff; box-shadow:0 25px 60px rgba(0,0,0,0.2);"
                @click.outside="isOpen = false">

                {{-- Accent bar --}}
                <div class="h-1.5" style="background:linear-gradient(90deg,#ef4444,#f97316,#ef4444); background-size:200%; animation:shimmer 2s linear infinite;"></div>

                <div class="p-7">

                    {{-- Icon --}}
                    <div class="flex justify-center mb-5">
                        <div class="relative">
                            <div class="w-20 h-20 rounded-2xl flex items-center justify-center"
                                style="background:linear-gradient(135deg,#fef2f2,#fee2e2);">
                                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                            {{-- Warning badge --}}
                            <div class="absolute -top-2 -right-2 w-7 h-7 rounded-full flex items-center justify-center"
                                style="background:linear-gradient(135deg,#dc2626,#ef4444); box-shadow:0 2px 8px rgba(220,38,38,0.4);">
                                <span class="text-white text-xs font-black">!</span>
                            </div>
                        </div>
                    </div>

                    {{-- Title & Body --}}
                    <h3 class="text-xl font-extrabold text-gray-800 text-center">Hapus User?</h3>

                    <div class="mt-3 mx-auto max-w-xs">
                        <div class="rounded-xl px-4 py-3 text-center" style="background:#fafafa; border:1px solid #f0f0f0;">
                            <p class="text-xs text-gray-400 mb-1">Akun yang akan dihapus</p>
                            <p class="font-extrabold text-gray-800 text-base" x-text="userName"></p>
                        </div>
                        <p class="text-sm text-gray-500 text-center mt-3 leading-relaxed">
                            Data user ini akan <span class="font-bold text-red-600">dihapus permanen</span> dan tidak dapat dipulihkan kembali.
                        </p>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-3 mt-6">

                        {{-- Batal --}}
                        <button
                            type="button"
                            @click="isOpen = false"
                            class="flex-1 py-3 text-sm font-bold rounded-xl transition-all duration-150"
                            style="background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0;"
                            onmouseover="this.style.background='#e2e8f0';"
                            onmouseout="this.style.background='#f1f5f9';">
                            Batal
                        </button>

                        {{-- Ya, Hapus --}}
                        <button
                            type="button"
                            @click="submitForm()"
                            x-ref="confirmBtn"
                            class="flex-1 py-3 text-sm font-bold text-white rounded-xl transition-all duration-150 flex items-center justify-center gap-2"
                            style="background:linear-gradient(135deg,#dc2626,#ef4444); box-shadow:0 4px 14px rgba(220,38,38,0.35);"
                            onmouseover="this.style.boxShadow='0 6px 20px rgba(220,38,38,0.5)'; this.style.transform='translateY(-1px)';"
                            onmouseout="this.style.boxShadow='0 4px 14px rgba(220,38,38,0.35)'; this.style.transform='translateY(0)';">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Ya, Hapus
                        </button>

                    </div>

                    {{-- Disclaimer --}}
                    <p class="text-xs text-gray-400 text-center mt-4 flex items-center justify-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tekan <kbd class="mx-1 px-1.5 py-0.5 rounded text-xs font-mono" style="background:#f1f5f9; border:1px solid #e2e8f0;">Esc</kbd> untuk membatalkan
                    </p>

                </div>
            </div>
        </div>
    </div>

    {{-- Hidden delete forms --}}
    @foreach($users as $user)
    @if($user->id !== auth()->id())
    <form id="delete-form-{{ $user->id }}"
        action="{{ route('admin.users.destroy', $user) }}"
        method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif
    @endforeach

    {{-- Alpine Component + Shimmer animation --}}
    <script>
        function deleteUserModal() {
            return {
                isOpen:  false,
                userName: '',
                formId:   '',
                open(detail) {
                    this.userName = detail.name;
                    this.formId   = detail.formId;
                    this.isOpen   = true;
                    // fokus ke tombol konfirmasi setelah animasi
                    setTimeout(() => this.$refs.confirmBtn?.focus(), 250);
                },
                submitForm() {
                    const form = document.getElementById(this.formId);
                    if (form) form.submit();
                }
            }
        }
    </script>

    <style>
        @keyframes shimmer {
            0%   { background-position: 200% center; }
            100% { background-position: -200% center; }
        }
        [x-cloak] { display: none !important; }
    </style>

</x-app-layout>
