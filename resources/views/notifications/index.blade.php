<x-app-layout>
    <x-slot name="header">Semua Notifikasi</x-slot>

    {{-- ===== PAGE HERO BAR ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-gray-800 leading-tight">Semua Notifikasi</h1>
            <p class="text-sm text-gray-400 mt-0.5">Riwayat notifikasi dan pemberitahuan sistem</p>
        </div>


    </div>

    {{-- ===== NOTIFICATION LIST ===== --}}
    @php
        $typeConfig = [
            'success' => [
                'icon_bg'   => 'background:linear-gradient(135deg,#dcfce7,#bbf7d0);',
                'icon_text' => 'color:#15803d;',
                'dot'       => '#22c55e',
                'unread_bg' => 'background:#f0fdf4;',
                'svg_path'  => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            'danger'  => [
                'icon_bg'   => 'background:linear-gradient(135deg,#fee2e2,#fecaca);',
                'icon_text' => 'color:#dc2626;',
                'dot'       => '#ef4444',
                'unread_bg' => 'background:#fef2f2;',
                'svg_path'  => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            'warning' => [
                'icon_bg'   => 'background:linear-gradient(135deg,#fef9c3,#fef08a);',
                'icon_text' => 'color:#d97706;',
                'dot'       => '#f59e0b',
                'unread_bg' => 'background:#fffbeb;',
                'svg_path'  => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            ],
            'info'    => [
                'icon_bg'   => 'background:linear-gradient(135deg,#dbeafe,#bfdbfe);',
                'icon_text' => 'color:#1d4ed8;',
                'dot'       => '#3b82f6',
                'unread_bg' => 'background:#eff6ff;',
                'svg_path'  => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
        ];
        $defaultConfig = [
            'icon_bg'   => 'background:linear-gradient(135deg,#f1f5f9,#e2e8f0);',
            'icon_text' => 'color:#64748b;',
            'dot'       => '#94a3b8',
            'unread_bg' => 'background:#f8fafc;',
            'svg_path'  => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
        ];
    @endphp

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-3xl overflow-hidden"
            style="box-shadow:0 8px 40px rgba(0,0,0,0.08); border:1px solid #f0f4ff;">

            {{-- Header bar --}}
            <div class="px-5 py-4 border-b flex items-center justify-between"
                style="border-color:#f0f4ff; background:#fafbff;">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                        style="background:linear-gradient(135deg,#1e1b4b,#3730a3);">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <span class="text-sm font-extrabold text-gray-700">Notifikasi</span>
                    @php $unreadCount = $notifications->where('read_at', null)->count(); @endphp
                    @if($unreadCount > 0)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-bold rounded-full"
                        style="background:#eff6ff; color:#1d4ed8;">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse inline-block"></span>
                        {{ $unreadCount }} belum dibaca
                    </span>
                    @else
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                        style="background:#f0fdf4; color:#15803d;">
                        Semua sudah dibaca
                    </span>
                    @endif
                </div>
                <span class="text-xs text-gray-400">{{ $notifications->total() }} total</span>
            </div>

            {{-- Notification items --}}
            @forelse($notifications as $notif)
            @php
                $data    = $notif->data;
                $type    = $data['type'] ?? 'info';
                $cfg     = $typeConfig[$type] ?? $defaultConfig;
                $isUnread = is_null($notif->read_at);
            @endphp

            <a href="{{ $data['url'] ?? '#' }}"
                class="flex items-start gap-4 px-5 py-4 transition-all group relative"
                style="border-bottom:1px solid #f8fafc; {{ $isUnread ? $cfg['unread_bg'] : '' }}"
                onmouseover="this.style.filter='brightness(0.97)';"
                onmouseout="this.style.filter='brightness(1)';">

                {{-- Unread left bar --}}
                @if($isUnread)
                <div class="absolute left-0 top-0 bottom-0 w-0.5 rounded-r-full"
                    style="background:{{ $cfg['dot'] }};"></div>
                @endif

                {{-- Icon --}}
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 mt-0.5 transition-transform group-hover:scale-105"
                    style="{{ $cfg['icon_bg'] }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="{{ $cfg['icon_text'] }}">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg['svg_path'] }}"/>
                    </svg>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-sm font-bold {{ $isUnread ? 'text-gray-900' : 'text-gray-600' }} leading-snug">
                            {{ $data['title'] ?? 'Notifikasi' }}
                        </p>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($isUnread)
                            <span class="w-2 h-2 rounded-full flex-shrink-0 mt-1"
                                style="background:{{ $cfg['dot'] }};"></span>
                            @endif
                            <span class="text-xs text-gray-400 whitespace-nowrap">
                                {{ $notif->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <p class="text-sm {{ $isUnread ? 'text-gray-600' : 'text-gray-400' }} mt-0.5 leading-relaxed">
                        {{ $data['message'] ?? '' }}
                    </p>

                    @if(!empty($data['reason']))
                    <div class="mt-2 flex items-start gap-1.5 px-3 py-2 rounded-xl"
                        style="background:#fef2f2; border:1px solid #fecaca;">
                        <svg class="w-3.5 h-3.5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-xs text-red-600 italic leading-relaxed">{{ $data['reason'] }}</p>
                    </div>
                    @endif
                </div>

                {{-- Chevron --}}
                <svg class="w-4 h-4 text-gray-300 flex-shrink-0 mt-3 transition-transform group-hover:translate-x-0.5"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>

            </a>

            @empty
            <div class="py-20 flex flex-col items-center justify-center text-center px-4">
                <div class="w-20 h-20 rounded-3xl flex items-center justify-center mb-5"
                    style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                    <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <p class="text-base font-extrabold text-gray-400">Belum ada notifikasi</p>
                <p class="text-sm text-gray-300 mt-1">Notifikasi akan muncul saat ada aktivitas terbaru</p>
            </div>
            @endforelse

        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
        <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <p class="text-xs text-gray-400">
                Menampilkan
                <span class="font-bold text-gray-600">{{ $notifications->firstItem() }}–{{ $notifications->lastItem() }}</span>
                dari <span class="font-bold text-gray-600">{{ $notifications->total() }}</span> notifikasi
            </p>
            <div>{{ $notifications->links() }}</div>
        </div>
        @endif

    </div>

</x-app-layout>