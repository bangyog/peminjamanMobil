<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Kendaraan') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ===== BASE ===== */
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(0, 82, 163, 0.25); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0, 82, 163, 0.5); }

        /* ===== SIDEBAR ===== */
        .sidebar-gradient {
            background: linear-gradient(160deg, #0066CC 0%, #0047AB 45%, #003380 100%);
            box-shadow: 4px 0 28px rgba(0, 51, 130, 0.3);
        }

        /* ===== NAV LINK ===== */
        .nav-link {
            position: relative;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
        }
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.12);
            border-left-color: rgba(255, 255, 255, 0.45);
            padding-left: 1.35rem;
        }
        .nav-link.active {
            background: rgba(255, 255, 255, 0.18);
            border-left: 3px solid #ffffff;
            box-shadow: inset 0 0 20px rgba(255, 255, 255, 0.06);
        }
        .nav-icon {
            transition: transform 0.25s ease;
        }
        .nav-link:hover .nav-icon {
            transform: scale(1.1);
        }

        /* ===== NAV SECTION LABEL ===== */
        .nav-section-label {
            font-size: 0.65rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.38);
            padding: 0.85rem 1rem 0.35rem;
            font-weight: 600;
        }

        /* ===== STAT CARD ===== */
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 24px 32px -8px rgba(0, 0, 0, 0.12);
        }

        /* ===== HEADER ===== */
        .header-bar {
            background: #ffffff;
            border-bottom: 1px solid #eef2f7;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.045);
        }

        /* ===== HAMBURGER ===== */
        .hamburger-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            color: #64748b;
            transition: all 0.2s;
        }
        .hamburger-btn:hover {
            background: #f1f5f9;
            color: #0052A3;
        }

        /* ===== NOTIF BADGE PULSE ===== */
        @keyframes badge-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.5); }
            50%       { box-shadow: 0 0 0 4px rgba(239, 68, 68, 0); }
        }
        .badge-pulse { animation: badge-pulse 1.8s ease infinite; }

        /* ===== PAGE TITLE GRADIENT ===== */
        .page-title {
            background: linear-gradient(135deg, #1e3a5f, #0052A3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ===== ROLE BADGE ===== */
        .role-badge {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1d4ed8;
            font-size: 0.62rem;
            font-weight: 600;
            padding: 1px 8px;
            border-radius: 999px;
            letter-spacing: 0.02em;
        }

        /* ===== SIDEBAR USER CARD ===== */
        .sidebar-user-card {
            background: rgba(0, 0, 0, 0.15);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* ===== LOGOUT BUTTON ===== */
        .logout-btn {
            transition: all 0.2s;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.06);
        }
        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(252, 165, 165, 0.4);
        }

        /* ===== NOTIF DROPDOWN ===== */
        .notif-dropdown {
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 20px 48px rgba(0, 0, 0, 0.14), 0 4px 8px rgba(0, 0, 0, 0.06);
        }

        /* ===== FLASH MESSAGES ===== */
        .flash-msg {
            animation: slideInDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== TOAST ===== */
        .toast-item {
            animation: slideInRight 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ===== LOGO BOX ===== */
        .logo-box {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 6px;
        }

        /* ===== TIME PILL ===== */
        .time-pill {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 5px 12px;
        }

        /* ===== MOBILE BOTTOM NAV ===== */
        .bottom-nav-bar {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            box-shadow: 0 -4px 16px rgba(0,0,0,0.06);
        }
        .bottom-nav-item {
            transition: all 0.2s;
            color: #94a3b8;
        }
        .bottom-nav-item.active {
            color: #0052A3;
        }
        .bottom-nav-item:hover {
            color: #0052A3;
        }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased" style="background:#f4f7fb;" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">

        {{-- ===================== SIDEBAR DESKTOP ===================== --}}
        <aside class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 sidebar-gradient">

                {{-- Logo --}}
                <div class="flex items-center justify-center h-20 px-5 border-b border-white/10" style="background:rgba(0,0,0,0.12);">
                    <div class="logo-box flex-shrink-0">
                        <img src="{{ asset('images/swa-logo.png') }}" alt="SWA Logo" class="h-10 w-10 object-contain">
                    </div>
                    <div class="ml-3">
                        <h1 class="text-white font-bold text-base leading-tight tracking-wide">SIREKADIN</h1>
                        <p class="text-blue-200/75 text-xs font-medium tracking-wider">Sistem Kendaraan</p>
                    </div>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">
                    @auth
                    @php $user = auth()->user(); @endphp

                    <p class="nav-section-label">Menu Utama</p>

                    {{-- ✅ ADMIN GA --}}
                    @if($user->isAdminGA())
                    <a href="{{ route('dashboard') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.loan-requests.index') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.loan-requests.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Pengajuan Masuk
                    </a>
                    <a href="{{ route('admin.returns.index') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        Pengembalian
                    </a>
                    <a href="{{ route('admin.vehicles.index') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                        Kendaraan
                    </a>
                    <a href="{{ route('admin.units.index') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Units
                    </a>
                    <!-- <a href="{{ route('admin.monitoring.expenses') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.monitoring.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Monitoring Biaya
                    </a> -->

                    {{-- ✅ KEPALA DEPARTEMEN --}}
                    @elseif($user->isKepalaDepartemen())
                    <a href="{{ route('dashboard') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('approvals.kepala.index') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('approvals.kepala.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Approval Pengajuan
                    </a>
                    <a href="{{ route('loan-requests.create') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('loan-requests.create') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajukan Peminjaman
                    </a>
                    <a href="{{ route('loan-requests.index') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('loan-requests.index') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Peminjaman Saya
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Kelola User
                    </a>

                    {{-- ✅ ADMIN AKUNTANSI --}}
                    @elseif($user->isAdminAkuntansi())
                    <a href="{{ route('dashboard') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('approvals.akuntansi.index') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('approvals.akuntansi.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Approval Pengajuan
                    </a>
                    <!-- <a href="{{ route('admin.monitoring.expenses') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.monitoring.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Monitoring Biaya
                    </a> -->
                    <a href="{{ route('loan-requests.create') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('loan-requests.create') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajukan Peminjaman
                    </a>
                    <a href="{{ route('loan-requests.index') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('loan-requests.index') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Peminjaman Saya
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Kelola User
                    </a>

                    {{-- ✅ USER BIASA --}}
                    @else
                    <a href="{{ route('dashboard') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('loan-requests.create') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('loan-requests.create') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajukan Peminjaman
                    </a>
                    <a href="{{ route('loan-requests.index') }}"
                        class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('loan-requests.index') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Riwayat Pengajuan
                    </a>
                    @endif
                    @endauth
                </nav>

                {{-- User Info Desktop --}}
                <div class="sidebar-user-card p-4">
                    @auth
                    <div class="flex items-center mb-3">
                        <div class="relative flex-shrink-0">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold text-sm border-2 border-white/25 shadow-lg"
                                style="background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1));">
                                {{ substr(auth()->user()->full_name, 0, 1) }}
                            </div>
                            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 rounded-full border-2"
                                style="border-color:#0047AB;"></div>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate leading-tight">{{ auth()->user()->full_name }}</p>
                            <p class="text-xs truncate mt-0.5" style="color:rgba(186,230,253,0.65);">{{ auth()->user()->unit->name ?? '-' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="logout-btn w-full flex items-center justify-center px-3 py-2 text-xs text-white/75 rounded-lg gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                    @endauth
                </div>

            </div>
        </aside>

        {{-- ===================== SIDEBAR MOBILE OVERLAY ===================== --}}
        <div x-show="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 md:hidden"
            style="background:rgba(0,0,0,0.55); backdrop-filter:blur(3px);"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
        </div>

        {{-- ===================== SIDEBAR MOBILE ===================== --}}
        <aside x-show="sidebarOpen"
            @click.away="sidebarOpen = false"
            class="fixed inset-y-0 left-0 z-50 w-72 sidebar-gradient md:hidden"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            style="display:none;">

            <div class="flex flex-col h-full">
                {{-- Logo Mobile --}}
                <div class="flex items-center justify-between h-16 px-4 border-b border-white/10"
                    style="background:rgba(0,0,0,0.12);">
                    <div class="flex items-center">
                        <div class="logo-box flex-shrink-0 mr-3">
                            <img src="{{ asset('images/swa-logo.png') }}" alt="SWA Logo" class="h-8 w-8 object-contain">
                        </div>
                        <div>
                            <h1 class="text-white font-bold text-sm leading-tight">SIREKADIN</h1>
                            <p class="text-xs" style="color:rgba(186,230,253,0.75);">Sistem Kendaraan</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = false"
                        class="flex items-center justify-center w-8 h-8 rounded-lg text-white transition"
                        style="background:rgba(255,255,255,0.1);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Mobile Navigation --}}
                <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                    @auth
                    @php $user = auth()->user(); @endphp

                    <p class="nav-section-label">Menu Utama</p>

                    @if($user->isAdminGA())
                    <a href="{{ route('dashboard') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.loan-requests.index') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.loan-requests.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Pengajuan Masuk
                    </a>
                    <a href="{{ route('admin.returns.index') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        Pengembalian
                    </a>
                    <a href="{{ route('admin.vehicles.index') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                        Kendaraan
                    </a>
                    <a href="{{ route('admin.units.index') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Units
                    </a>
                    <!-- <a href="{{ route('admin.monitoring.expenses') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.monitoring.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Monitoring Biaya
                    </a> -->

                    @elseif($user->isKepalaDepartemen())
                    <a href="{{ route('dashboard') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('approvals.kepala.index') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('approvals.kepala.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Approval Pengajuan
                    </a>
                    <a href="{{ route('loan-requests.create') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('loan-requests.create') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ajukan Peminjaman
                    </a>
                    <a href="{{ route('loan-requests.index') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('loan-requests.index') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Peminjaman Saya
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Kelola User
                    </a>

                    @elseif($user->isAdminAkuntansi())
                    <a href="{{ route('dashboard') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('approvals.akuntansi.index') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('approvals.akuntansi.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Approval Pengajuan
                    </a>

                    <a href="{{ route('loan-requests.create') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('loan-requests.create') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ajukan Peminjaman
                    </a>
                    <a href="{{ route('loan-requests.index') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('loan-requests.index') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Peminjaman Saya
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Kelola User
                    </a>

                    @else
                    <a href="{{ route('dashboard') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('loan-requests.create') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('loan-requests.create') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ajukan Peminjaman
                    </a>
                    <a href="{{ route('loan-requests.index') }}" class="nav-link flex items-center px-4 py-2.5 text-white/90 rounded-xl text-sm font-medium {{ request()->routeIs('loan-requests.index') ? 'active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Riwayat Pengajuan
                    </a>
                    @endif
                    @endauth
                </nav>

                {{-- User Info Mobile --}}
                <div class="sidebar-user-card p-4">
                    @auth
                    <div class="flex items-center mb-3">
                        <div class="relative flex-shrink-0">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold text-sm border-2 border-white/25"
                                style="background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1));">
                                {{ substr(auth()->user()->full_name, 0, 1) }}
                            </div>
                            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 rounded-full border-2" style="border-color:#0047AB;"></div>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->full_name }}</p>
                            <p class="text-xs truncate mt-0.5" style="color:rgba(186,230,253,0.65);">{{ auth()->user()->unit->name ?? '-' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn w-full flex items-center justify-center px-3 py-2 text-xs text-white/75 rounded-lg gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </aside>

        {{-- ===================== MAIN CONTENT ===================== --}}
        <div class="flex flex-col flex-1 overflow-hidden">

            {{-- Header --}}
            <header class="header-bar z-10 flex-shrink-0">
                <div class="flex items-center justify-between px-4 py-3 sm:px-6">

                    {{-- Hamburger mobile --}}
                    <button @click="sidebarOpen = true" class="hamburger-btn md:hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex-1 md:flex md:items-center md:justify-between">

                        {{-- Page title --}}
                        <h2 class="page-title text-xl sm:text-2xl font-bold ml-3 md:ml-0">
                            {{ $header ?? 'Dashboard' }}
                        </h2>

                        {{-- Right side --}}
                        <div class="hidden md:flex items-center space-x-3">

                            {{-- Tanggal & Jam --}}
                            <div class="time-pill flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs font-medium text-slate-500">
                                    {{ now()->locale('id')->isoFormat('D MMM Y, HH:mm') }} WIB
                                </span>
                            </div>

                            @auth

                            {{-- ✅ Bell Notification --}}
                            <div x-data="notificationBell()" x-init="init()" class="relative">

                                {{-- Bell Button --}}
                                <button @click="toggle()"
                                    class="relative p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl focus:outline-none transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    {{-- Badge unread --}}
                                    <span x-show="unreadCount > 0"
                                        x-text="unreadCount > 99 ? '99+' : unreadCount"
                                        class="badge-pulse absolute -top-0.5 -right-0.5 bg-red-500 text-white
                                         text-xs font-bold rounded-full min-w-[18px] h-[18px]
                                         flex items-center justify-center px-1">
                                    </span>
                                </button>

                                {{-- Dropdown --}}
                                <div x-show="open"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    @click.away="open = false"
                                    class="notif-dropdown absolute right-0 mt-2 w-96 bg-white rounded-2xl z-50"
                                    style="display:none;">

                                    {{-- Header Dropdown --}}
                                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-bold text-gray-900 text-sm">Notifikasi</h3>
                                            <span x-show="unreadCount > 0"
                                                x-text="unreadCount"
                                                class="bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">
                                            </span>
                                        </div>
                                        <button @click="markAllAsRead()"
                                            x-show="unreadCount > 0"
                                            class="text-xs text-blue-600 hover:text-blue-800 font-medium transition">
                                            Tandai semua dibaca
                                        </button>
                                    </div>

                                    {{-- List Notifikasi --}}
                                    <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">

                                        <template x-if="notifications.length === 0">
                                            <div class="px-5 py-10 text-center">
                                                <div class="text-4xl mb-3">🔔</div>
                                                <p class="text-sm text-gray-500 font-medium">Belum ada notifikasi</p>
                                                <p class="text-xs text-gray-400 mt-1">Notifikasi akan muncul di sini</p>
                                            </div>
                                        </template>

                                        <template x-for="notif in notifications" :key="notif.id">
                                            <a :href="notif.data.url"
                                                @click.prevent="handleClick(notif)"
                                                class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition cursor-pointer"
                                                :class="{'bg-blue-50 hover:bg-blue-50': !notif.read_at}">

                                                {{-- Icon --}}
                                                <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center text-base"
                                                    :class="{
                                                        'bg-green-100':  notif.data.type === 'success',
                                                        'bg-red-100':    notif.data.type === 'danger',
                                                        'bg-yellow-100': notif.data.type === 'warning',
                                                        'bg-blue-100':   notif.data.type === 'info',
                                                    }">
                                                    <span x-text="getIcon(notif.data.type)"></span>
                                                </div>

                                                {{-- Content --}}
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 leading-tight"
                                                        x-text="notif.data.title"></p>
                                                    <p class="text-xs text-gray-600 mt-0.5 leading-relaxed"
                                                        x-text="notif.data.message"></p>
                                                    <p x-show="notif.data.reason"
                                                        class="text-xs text-red-600 mt-1 italic bg-red-50 px-2 py-1 rounded">
                                                        💬 <span x-text="notif.data.reason"></span>
                                                    </p>
                                                    <p class="text-xs text-gray-400 mt-1"
                                                        x-text="timeAgo(notif.created_at)"></p>
                                                </div>

                                                {{-- Unread dot --}}
                                                <div x-show="!notif.read_at"
                                                    class="flex-shrink-0 w-2.5 h-2.5 bg-blue-500 rounded-full mt-1.5">
                                                </div>
                                            </a>
                                        </template>
                                    </div>

                                    {{-- Footer Dropdown --}}
                                    <div class="px-5 py-3 border-t border-gray-100 text-center">
                                        <a href="{{ route('notifications.index') }}"
                                            class="text-xs text-blue-600 hover:text-blue-800 font-medium transition">
                                            Lihat semua notifikasi →
                                        </a>
                                    </div>
                                </div>
                            </div>
                            {{-- ✅ END Bell --}}

                            {{-- ✅ Avatar User --}}
                            <div class="flex items-center space-x-2.5 px-3 py-2 rounded-xl border border-slate-100"
                                style="background:#f8fafc;">
                                <div class="h-8 w-8 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                                    style="background: linear-gradient(135deg, #0066CC, #0047AB);">
                                    {{ substr(auth()->user()->full_name, 0, 1) }}
                                </div>
                                <div class="text-left">
                                    <p class="text-xs font-semibold text-gray-700 leading-tight">{{ auth()->user()->full_name }}</p>
                                    <span class="role-badge inline-block mt-0.5">
                                        {{ match(auth()->user()->role) {
                                            'admin_ga'          => 'Admin GA',
                                            'admin_akuntansi'   => 'Admin Akuntansi',
                                            'kepala_departemen' => 'Kepala Departemen',
                                            default             => 'User'
                                        } }}
                                    </span>
                                </div>
                            </div>

                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            {{-- ✅ Toast Container --}}
            <div id="toast-container"
                class="fixed top-4 right-4 z-50 space-y-2 pointer-events-none"
                style="min-width: 320px;">
            </div>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 pb-20 md:pb-8" style="background:#f4f7fb;">

                {{-- Flash Messages --}}
                @if(session('success'))
                <div class="flash-msg mb-5 p-4 rounded-xl flex items-center gap-3 text-sm font-medium"
                    style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534;">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="flash-msg mb-5 p-4 rounded-xl flex items-center gap-3 text-sm font-medium"
                    style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b;">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @if(session('warning'))
                <div class="flash-msg mb-5 p-4 rounded-xl flex items-center gap-3 text-sm font-medium"
                    style="background:#fffbeb; border:1px solid #fde68a; color:#92400e;">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>
                    <span>{{ session('warning') }}</span>
                </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @auth
    <script>
        function notificationBell() {
            return {
                open: false,
                notifications: [],
                unreadCount: 0,

                init() {
                    this.fetchNotifications();
                    this.listenRealtime();
                },
                toggle() {
                    this.open = !this.open;
                },

                fetchNotifications() {
                    axios.get('/notifications/fetch')
                        .then(res => {
                            this.notifications = res.data.notifications;
                            this.unreadCount = res.data.unread_count;
                        }).catch(() => {});
                },

                listenRealtime() {
                    if (typeof window.Echo === 'undefined') return;
                    window.Echo.private(`App.Models.User.{{ auth()->id() }}`)
                        .notification((notification) => {
                            this.notifications.unshift({
                                id: notification.id ?? Date.now(),
                                data: notification,
                                read_at: null,
                                created_at: new Date().toISOString(),
                            });
                            this.unreadCount++;
                            this.showToast(notification);
                        });
                },

                handleClick(notif) {
                    if (!notif.read_at) {
                        notif.read_at = new Date().toISOString();
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                        axios.patch(`/notifications/${notif.id}/read`).catch(() => {});
                    }
                    window.location.href = notif.data.url;
                },

                markAllAsRead() {
                    this.notifications.forEach(n => n.read_at = new Date().toISOString());
                    this.unreadCount = 0;
                    axios.patch('/notifications/read-all').catch(() => {});
                },

                getIcon(type) {
                    const icons = {
                        success: '✅',
                        danger: '❌',
                        warning: '⚠️',
                        info: 'ℹ️'
                    };
                    return icons[type] ?? '🔔';
                },

                timeAgo(dateStr) {
                    const date = new Date(dateStr);
                    const now = new Date();
                    const diff = Math.floor((now - date) / 1000);
                    if (diff < 60) return 'Baru saja';
                    if (diff < 3600) return `${Math.floor(diff / 60)} menit lalu`;
                    if (diff < 86400) return `${Math.floor(diff / 3600)} jam lalu`;
                    return `${Math.floor(diff / 86400)} hari lalu`;
                },

                showToast(notification) {
                    const container = document.getElementById('toast-container');
                    if (!container) return;
                    const colors = {
                        success: '#22c55e',
                        danger:  '#ef4444',
                        warning: '#f59e0b',
                        info:    '#3b82f6'
                    };
                    const bgColors = {
                        success: '#f0fdf4',
                        danger:  '#fef2f2',
                        warning: '#fffbeb',
                        info:    '#eff6ff'
                    };
                    const color = colors[notification.type] ?? '#3b82f6';
                    const bg    = bgColors[notification.type] ?? '#eff6ff';

                    const toast = document.createElement('div');
                    toast.className = 'toast-item pointer-events-auto';
                    toast.style.cssText = `
                        background: ${bg};
                        border: 1px solid ${color}33;
                        border-left: 4px solid ${color};
                        border-radius: 12px;
                        padding: 14px 16px;
                        display: flex;
                        align-items: flex-start;
                        gap: 12px;
                        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
                        max-width: 360px;
                        cursor: pointer;
                    `;
                    toast.innerHTML = `
                        <div style="font-size:1.2rem;line-height:1;">${this.getIcon(notification.type)}</div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-size:0.8rem;font-weight:700;color:#1e293b;margin:0 0 2px;">${notification.title ?? ''}</p>
                            <p style="font-size:0.75rem;color:#475569;margin:0;">${notification.message ?? ''}</p>
                        </div>
                        <button onclick="this.closest('.toast-item').remove()"
                            style="color:#94a3b8;font-size:1rem;line-height:1;background:none;border:none;cursor:pointer;padding:0;">✕</button>
                    `;
                    if (notification.url) {
                        toast.addEventListener('click', (e) => {
                            if (e.target.tagName !== 'BUTTON') window.location.href = notification.url;
                        });
                    }
                    container.appendChild(toast);
                    setTimeout(() => {
                        toast.style.transition = 'opacity 0.4s, transform 0.4s';
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateX(30px)';
                        setTimeout(() => toast.remove(), 400);
                    }, 5000);
                }
            };
        }
    </script>
    @endauth

</body>
</html>