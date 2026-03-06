<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIRADIN') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { box-sizing: border-box; }

        html, body {
            font-family: 'Inter', sans-serif;
            /* ✅ FIX SCROLL: html & body harus scrollable */
            min-height: 100%;
            overflow-y: auto;
        }

        /* ===== BACKGROUND ===== */
        .auth-bg {
            background: linear-gradient(135deg,
                #0a1628 0%,
                #0d2347 40%,
                #0f3460 70%,
                #1a4b8c 100%);
            background-attachment: fixed; /* bg tetap saat scroll */
        }

        /* ===== STATIC ORBS (bukan fixed, ikut scroll jika perlu) ===== */
        .orb-1 {
            position: fixed; top: -150px; right: -150px;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(0,82,163,0.25) 0%, transparent 70%);
            animation: floatOrb 8s ease-in-out infinite;
            pointer-events: none; z-index: 0;
        }
        .orb-2 {
            position: fixed; bottom: -100px; left: -100px;
            width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(0,164,255,0.15) 0%, transparent 70%);
            animation: floatOrb 10s ease-in-out infinite reverse;
            pointer-events: none; z-index: 0;
        }
        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%       { transform: translate(30px, -30px) scale(1.08); }
        }

        /* ===== DOT GRID ===== */
        .dot-grid {
            position: fixed; inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none; z-index: 0;
        }

        /* ===== GLASS CARD ===== */
        .glass-card {
            background: rgba(255,255,255,0.07);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.12);
            box-shadow:
                0 32px 80px rgba(0,0,0,0.5),
                0 0 0 1px rgba(255,255,255,0.05) inset,
                0 1px 0 rgba(255,255,255,0.10) inset;
        }

        /* ===== LOGO GLOW ===== */
        .logo-glow {
            filter: drop-shadow(0 0 30px rgba(0,164,255,0.5))
                    drop-shadow(0 0 60px rgba(0,82,163,0.3));
            animation: logoPulse 4s ease-in-out infinite;
        }
        @keyframes logoPulse {
            0%,100% { filter: drop-shadow(0 0 30px rgba(0,164,255,0.5)) drop-shadow(0 0 60px rgba(0,82,163,0.3)); }
            50%      { filter: drop-shadow(0 0 40px rgba(0,164,255,0.7)) drop-shadow(0 0 80px rgba(0,82,163,0.45)); }
        }

        /* ===== TITLE GRADIENT ===== */
        .title-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #93c5fd 50%, #60a5fa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ===== BADGE PILL ===== */
        .badge-pill {
            background: linear-gradient(135deg, rgba(0,164,255,0.2), rgba(0,82,163,0.3));
            border: 1px solid rgba(0,164,255,0.3);
            backdrop-filter: blur(8px);
        }

        /* ===== PARTICLES ===== */
        .particle {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        /* ===== SLIDE-UP ANIMATION ===== */
        .slide-up         { animation: slideUp 0.6s cubic-bezier(0.16,1,0.3,1) both; }
        .slide-up-delay   { animation: slideUp 0.6s cubic-bezier(0.16,1,0.3,1) 0.15s both; }
        .slide-up-delay-2 { animation: slideUp 0.6s cubic-bezier(0.16,1,0.3,1) 0.25s both; }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== SEPARATOR ===== */
        .separator-line {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        }

        /* ===== INPUT OVERRIDES ===== */
        .auth-input {
            display: block;
            width: 100%;
            padding: 0.75rem 0.75rem 0.75rem 2.75rem;
            background: rgba(255,255,255,0.90);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 0.5rem;
            color: #1f2937;
            font-size: 0.875rem;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .auth-input:focus {
            border-color: #fff;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.25);
        }
        .auth-input::placeholder { color: #9ca3af; }
    </style>
</head>

{{--
    ✅ KUNCI SCROLL:
    - html & body: min-height:100% + overflow-y:auto  (di CSS atas)
    - wrapper utama pakai min-h-screen + py-8  BUKAN h-screen
    - background pakai background-attachment:fixed agar tidak bergeser saat scroll
--}}
<body class="antialiased auth-bg min-h-screen">

    {{-- Layer dekorasi (fixed, tidak ikut scroll) --}}
    <div class="dot-grid" aria-hidden="true"></div>
    <div class="orb-1"    aria-hidden="true"></div>
    <div class="orb-2"    aria-hidden="true"></div>

    {{-- Partikel --}}
    <div aria-hidden="true">
        <div class="particle w-1.5 h-1.5 bg-blue-400 opacity-40"
            style="top:15%; left:8%;  animation:floatOrb 7s ease-in-out infinite;"></div>
        <div class="particle w-1   h-1   bg-blue-300 opacity-30"
            style="top:65%; left:92%; animation:floatOrb 9s ease-in-out infinite reverse;"></div>
        <div class="particle w-2   h-2   bg-sky-400  opacity-20"
            style="top:80%; left:15%; animation:floatOrb 11s ease-in-out infinite;"></div>
        <div class="particle w-1.5 h-1.5 bg-indigo-400 opacity-25"
            style="top:30%; left:85%; animation:floatOrb 6s ease-in-out infinite reverse;"></div>
    </div>

    {{-- ✅ WRAPPER UTAMA: min-h-screen + py-8 → bisa scroll --}}
    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen px-4 py-10 sm:py-12">

        {{-- ===== HERO / LOGO ===== --}}
        <div class="w-full max-w-md text-center mb-8 slide-up">

            {{-- Logo --}}
            <a href="/" class="inline-block mb-5">
                <div class="relative inline-block">
                    <div class="absolute inset-0 rounded-full"
                        style="background:radial-gradient(circle, rgba(0,164,255,0.3) 0%, transparent 70%);
                               transform:scale(1.6);
                               animation:logoPulse 4s ease-in-out infinite;">
                    </div>
                    <img src="{{ asset('images/swa-logo.png') }}"
                         alt="SWA Logo"
                         class="relative w-20 h-20 sm:w-24 sm:h-24 logo-glow object-contain"
                         onerror="this.outerHTML='<div class=\'relative w-20 h-20 sm:w-24 sm:h-24 rounded-2xl flex items-center justify-center logo-glow\' style=\'background:rgba(0,82,163,0.5);border:2px solid rgba(0,164,255,0.4)\'><span class=\'text-white font-extrabold text-xl\'>SWG</span></div>'">
                </div>
            </a>

            {{-- Title --}}
            <h1 class="title-gradient text-2xl sm:text-3xl font-extrabold leading-tight mb-2 tracking-tight">
                Sistem Reservasi<br class="sm:hidden"> Kendaraan Dinas
            </h1>

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full badge-pill mt-2">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                <span class="text-sky-300 text-xs font-bold tracking-widest uppercase">SIRADIN</span>
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
            </div>

        </div>

        {{-- ===== GLASS CARD ===== --}}
        <div class="w-full max-w-md glass-card rounded-3xl overflow-hidden slide-up-delay">

            {{-- Top accent line --}}
            <div class="h-0.5 w-full"
                style="background:linear-gradient(90deg, transparent, rgba(0,164,255,0.8), rgba(99,179,237,0.6), transparent);">
            </div>

            {{-- Slot content (login form, dll) --}}
            <div class="px-6 sm:px-8 py-8">
                {{ $slot }}
            </div>

            {{-- Separator --}}
            <div class="separator-line mx-6 mb-4"></div>

            {{-- Footer card --}}
            <div class="px-6 sm:px-8 pb-6 text-center">
                <p class="text-white/30 text-xs">
                    Sistem aman &amp; terenkripsi
                    <span class="mx-1.5 opacity-50">·</span>
                    PT. Swabina Gatra
                </p>
            </div>

        </div>

        {{-- Copyright --}}
        <div class="mt-8 text-center slide-up-delay-2">
            <p class="text-white/25 text-xs">
                &copy; {{ date('Y') }} PT. Swabina Gatra. All rights reserved.
            </p>
        </div>

    </div>

</body>
</html>