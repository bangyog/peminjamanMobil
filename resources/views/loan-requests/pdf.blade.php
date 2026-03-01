<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Formulir Pengajuan Kendaraan Dinas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            margin: 22mm 20mm 20mm 25mm;
        }

        body {
            font-family: Calibri, Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
        }

        /* ===== COMPANY HEADER ===== */
        .company-header {
            width: 100%;
            border-bottom: 2.5px solid #000;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }
        .company-header table { width: 100%; }
        .logo-cell { width: 75px; vertical-align: middle; }
        .logo-circle {
            width: 65px; height: 65px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border: 2px solid #1e3a8a;
            text-align: center; line-height: 65px;
        }
        .logo-text { color: #fff; font-size: 17pt; font-weight: bold; letter-spacing: 1px; }
        .company-info-cell { vertical-align: middle; padding-left: 12px; }
        .company-name { font-size: 13pt; font-weight: bold; margin-bottom: 2px; }
        .company-address { font-size: 9.5pt; color: #333; }
        .header-date-cell { vertical-align: top; text-align: right; font-size: 10pt; width: 160px; }

        /* ===== TITLE ===== */
        .title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 0.5px;
            margin: 14px 0 16px 0;
        }

        /* ===== SECTION: DIMINTA OLEH / MENGETAHUI / PARAF ===== */
        .approval-header {
            width: 100%;
            margin-bottom: 14px;
        }
        .approval-header table { width: 100%; }
        .approval-header td {
            width: 33.33%;
            text-align: center;
            font-weight: bold;
            font-size: 10.5pt;
            vertical-align: top;
            padding: 0 4px;
        }

        /* ===== FORM FIELDS ===== */
        .form-section { margin-bottom: 14px; }
        .form-row {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }
        .form-label {
            display: table-cell;
            width: 190px;
            font-size: 10.5pt;
            vertical-align: bottom;
            padding-right: 6px;
        }
        .form-sep {
            display: table-cell;
            width: 12px;
            text-align: center;
            vertical-align: bottom;
            font-size: 10.5pt;
        }
        .form-value {
            display: table-cell;
            border-bottom: 1px solid #000;
            font-size: 10.5pt;
            vertical-align: bottom;
            padding-bottom: 2px;
            padding-left: 4px;
        }

        /* ===== MAIN TABLE ===== */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 10.5pt;
        }
        .main-table td, .main-table th {
            border: 1.5px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }
        .main-table .col-header {
            text-align: center;
            font-weight: bold;
            padding: 7px 8px;
            background-color: #f5f5f5;
        }
        .main-table .section-label {
            text-align: center;
            font-weight: bold;
            padding: 5px 8px;
            background-color: #ebebeb;
        }
        .main-table .content-cell {
            min-height: 80px;
            vertical-align: top;
            line-height: 1.6;
        }
        .main-table .sig-cell {
            text-align: center;
            vertical-align: middle;
            min-height: 80px;
        }
        .sig-img {
            max-width: 110px;
            max-height: 65px;
            display: block;
            margin: 0 auto;
        }
        .sig-name {
            font-size: 9pt;
            text-align: center;
            margin-top: 4px;
            border-top: 1px solid #000;
            padding-top: 3px;
        }

        /* ===== PIMPINAN KENDARAAN ===== */
        .pimpinan-section {
            margin-bottom: 20px;
        }
        .pimpinan-title {
            font-weight: bold;
            font-size: 10.5pt;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        .pimpinan-line {
            font-size: 10.5pt;
            line-height: 2.0;
        }
        .pimpinan-line .dotted {
            display: inline-block;
            border-bottom: 1px dotted #000;
            min-width: 220px;
            padding-bottom: 1px;
        }
        .pimpinan-sig {
            text-align: right;
            margin-top: 50px;
            margin-right: 30px;
        }
        .pimpinan-sig .sig-line {
            display: inline-block;
            border-bottom: 1.5px solid #000;
            width: 180px;
            text-align: center;
            margin-bottom: 4px;
        }
        .pimpinan-sig .sig-title {
            font-weight: bold;
            font-size: 10.5pt;
        }

        /* ===== CATATAN ===== */
        .notes-section { font-size: 9.5pt; color: #333; margin-top: 16px; }
        .notes-title { font-weight: bold; margin-bottom: 4px; }
        .notes-list { padding-left: 16px; }
        .notes-list li { margin-bottom: 2px; }

        /* ===== UTILITY ===== */
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .w-50 { width: 50%; }
        .w-60 { width: 60%; }
        .w-20 { width: 20%; }
    </style>
</head>
<body>

@php
    // ── Approval kepala/akuntansi
    $kepalaApproval = $loanRequest->approvals
        ->where('approval_level', 'kepala')
        ->where('decision', 'approved')
        ->first();

    // ── Approval GA
    $gaApproval = $loanRequest->approvals
        ->where('approval_level', 'ga')
        ->where('decision', 'approved')
        ->first();

    // ── Vehicle assigned
    $vehicle = $loanRequest->assignment->assignedVehicle ?? null;

    // ── Driver name: assigned_driver_name → fallback requester name
    $driverName = $loanRequest->assignment->assigned_driver_name
                  ?? $loanRequest->requester->full_name
                  ?? '-';

    // ── Helper: signature → absolute path untuk DomPDF
    $sigPath = fn($path) => $path
        ? storage_path('app/public/' . $path)
        : null;

    // ── Kendaraan diminta text
    $kendaraanText = $vehicle
        ? $vehicle->brand . ' ' . $vehicle->model . ' — ' . $vehicle->plate_no
        : ($loanRequest->preferredVehicle
            ? $loanRequest->preferredVehicle->brand . ' ' . $loanRequest->preferredVehicle->model
            : ($loanRequest->requested_vehicle_text ?? '-'));

    // ── Anggaran awal (nullable — mungkin belum ada di DB lama)
    $anggaranAwal = $loanRequest->anggaran_awal ?? null;

    // ── Kota + tanggal
    $kota = $loanRequest->request_city ?? 'Gresik';
@endphp

{{-- ===== COMPANY HEADER ===== --}}
<div class="company-header">
    <table>
        <tr>
            {{-- Logo --}}
            <td class="logo-cell">
                <div class="logo-circle">
                    <span class="logo-text">SWG</span>
                </div>
            </td>
            {{-- Company Info --}}
            <td class="company-info-cell">
                <div class="company-name">PT. Swabina Gatra</div>
                <div class="company-address">Jl. R.A Kartini 21 A Gresik</div>
            </td>
            {{-- Tanggal --}}
            <td class="header-date-cell">
                {{ $kota }},
                {{ $loanRequest->created_at->translatedFormat('d F Y') }}
            </td>
        </tr>
    </table>
</div>

{{-- ===== TITLE ===== --}}
<div class="title">PERMINTAAN PEMINJAMAN KENDARAAN DINAS</div>

{{-- ===== HEADER APPROVAL (Diminta Oleh / Mengetahui / Paraf GA) ===== --}}
<div class="approval-header">
    <table>
        <tr>
            <td>Diminta Oleh</td>
            <td>Mengetahui Atasan Divisi</td>
            <td>Paraf Foreman<br>Pelayanan Umum</td>
        </tr>
    </table>
</div>

{{-- ===== FORM FIELDS ===== --}}
<div class="form-section">
    <div class="form-row">
        <span class="form-label">Nama Pemakai</span>
        <span class="form-sep">:</span>
        <span class="form-value">{{ $loanRequest->requester->full_name ?? '-' }}</span>
    </div>
    <div class="form-row">
        <span class="form-label">Unit / Divisi</span>
        <span class="form-sep">:</span>
        <span class="form-value">{{ $loanRequest->unit->name ?? '-' }}</span>
    </div>
    <div class="form-row">
        <span class="form-label">Keperluan</span>
        <span class="form-sep">:</span>
        <span class="form-value">{{ $loanRequest->purpose ?? '-' }}</span>
    </div>
    <div class="form-row">
        <span class="form-label">Tujuan</span>
        <span class="form-sep">:</span>
        <span class="form-value">{{ $loanRequest->destination ?? '-' }}</span>
    </div>
    <div class="form-row">
        <span class="form-label">Kendaraan yang Diminta</span>
        <span class="form-sep">:</span>
        <span class="form-value">{{ $kendaraanText }}</span>
    </div>
    @if($loanRequest->notes)
    <div class="form-row">
        <span class="form-label">Catatan / Lain-lain</span>
        <span class="form-sep">:</span>
        <span class="form-value">{{ $loanRequest->notes }}</span>
    </div>
    @endif
</div>

{{-- ===== MAIN TABLE: BERANGKAT & KEMBALI ===== --}}
<table class="main-table">
    {{-- Header kolom --}}
    <tr>
        <td class="col-header w-60">DIISI PEMAKAI</td>
        <td class="col-header w-20">Paraf Pemakai</td>
        <td class="col-header w-20">Paraf Foreman<br>Pelayanan Umum</td>
    </tr>

    {{-- ── BERANGKAT label ── --}}
    <tr>
        <td colspan="3" class="section-label">Berangkat</td>
    </tr>

    {{-- ── BERANGKAT content ── --}}
    <tr>
        <td class="content-cell">
            <strong>Siap di</strong> &nbsp;:&nbsp; {{ $loanRequest->siap_di ?? '-' }}<br>
            @if($anggaranAwal)
            <strong>Anggaran Awal</strong> &nbsp;:&nbsp;
                Rp {{ number_format((float)$anggaranAwal, 0, ',', '.') }}<br>
            @endif
            <strong>Jam &amp; Tanggal</strong> &nbsp;:&nbsp;
            @if($loanRequest->depart_at)
                {{ $loanRequest->depart_at->format('H:i') }} WIB,
                {{ $loanRequest->depart_at->translatedFormat('d F Y') }}
            @else
                -
            @endif
        </td>

        {{-- Paraf Pemakai (user signature) --}}
        <td class="sig-cell">
            @if($sigPath($loanRequest->requester_signature))
            <img src="{{ $sigPath($loanRequest->requester_signature) }}"
                 class="sig-img" alt="TTD Pemakai">
            @endif
            <div class="sig-name">{{ $loanRequest->requester->full_name ?? '-' }}</div>
        </td>

        {{-- Paraf GA (berangkat) --}}
        <td class="sig-cell">
            @if($gaApproval && $sigPath($gaApproval->approver_signature))
            <img src="{{ $sigPath($gaApproval->approver_signature) }}"
                 class="sig-img" alt="TTD GA">
            @endif
            @if($gaApproval)
            <div class="sig-name">{{ $gaApproval->approver->full_name ?? '-' }}</div>
            @endif
        </td>
    </tr>

    {{-- ── KEMBALI label ── --}}
    <tr>
        <td colspan="3" class="section-label">Kembali</td>
    </tr>

    {{-- ── KEMBALI content ── --}}
    <tr>
        <td class="content-cell">
            <strong>Kembali di</strong> &nbsp;:&nbsp; {{ $loanRequest->kembali_di ?? '-' }}<br>
            <strong>Jam &amp; Tanggal</strong> &nbsp;:&nbsp;
            @if($loanRequest->expected_return_at)
                {{ $loanRequest->expected_return_at->format('H:i') }} WIB,
                {{ $loanRequest->expected_return_at->translatedFormat('d F Y') }}
            @else
                -
            @endif
        </td>

        {{-- Paraf Pemakai (kembali) — kosong untuk TTD saat kembali --}}
        <td class="sig-cell"></td>

        {{-- Paraf GA (kembali) — kosong untuk TTD saat kembali --}}
        <td class="sig-cell"></td>
    </tr>
</table>

{{-- ===== PIMPINAN KENDARAAN ===== --}}
<div class="pimpinan-section">
    <div class="pimpinan-title">OLEH PIMPINAN KENDARAAN</div>

    <div class="pimpinan-line">
        Diperintahkan kepada Sdr.&nbsp;
        <span class="dotted">{{ $driverName }}</span>
    </div>
    <div class="pimpinan-line">
        Untuk melayani sesuai dengan permintaan tersebut diatas
    </div>
    <div class="pimpinan-line">
        Dengan kendaraan&nbsp;
        <span class="dotted">
            @if($vehicle)
                {{ $vehicle->brand }} {{ $vehicle->model }} — {{ $vehicle->plate_no }}
            @else
                -
            @endif
        </span>
    </div>

    {{-- TTD GA di kanan --}}
    <div class="pimpinan-sig">
        @if($gaApproval && $sigPath($gaApproval->approver_signature))
        <div>
            <img src="{{ $sigPath($gaApproval->approver_signature) }}"
                 style="max-width:110px; max-height:65px;"
                 alt="TTD Foreman">
        </div>
        @else
        <div style="height:55px;"></div>
        @endif
        <div class="sig-line"></div>
        <div class="sig-title">Foreman Pelayanan Umum</div>
        @if($gaApproval)
        <div style="font-size:9.5pt; margin-top:2px;">
            {{ $gaApproval->approver->full_name ?? '' }}
        </div>
        @endif
    </div>
</div>

{{-- ===== MENGETAHUI ATASAN — signature block ===== --}}
{{-- Ditampilkan sebagai TTD kepala/akuntansi yang sudah approve --}}
@if($kepalaApproval)
<table style="width:100%; margin-top: -60px; margin-bottom: 16px;">
    <tr>
        {{-- Kiri: Diminta Oleh (user) --}}
        <td style="width:33%; text-align:center; vertical-align:bottom; padding:0 6px;">
            @if($sigPath($loanRequest->requester_signature))
            <img src="{{ $sigPath($loanRequest->requester_signature) }}"
                 style="max-width:100px; max-height:60px; display:block; margin:0 auto;"
                 alt="TTD User">
            @else
            <div style="height:55px;"></div>
            @endif
            <div style="border-top:1.5px solid #000; padding-top:3px; font-size:9.5pt;">
                {{ $loanRequest->requester->full_name ?? '-' }}<br>
                <span style="font-size:8.5pt; color:#555;">
                    {{ $loanRequest->unit->name ?? '' }}
                </span>
            </div>
        </td>

        {{-- Tengah: Mengetahui Atasan (kepala/akuntansi) --}}
        <td style="width:33%; text-align:center; vertical-align:bottom; padding:0 6px;">
            @if($sigPath($kepalaApproval->approver_signature))
            <img src="{{ $sigPath($kepalaApproval->approver_signature) }}"
                 style="max-width:100px; max-height:60px; display:block; margin:0 auto;"
                 alt="TTD Kepala">
            @else
            <div style="height:55px;"></div>
            @endif
            <div style="border-top:1.5px solid #000; padding-top:3px; font-size:9.5pt;">
                {{ $kepalaApproval->approver->full_name ?? '-' }}<br>
                <span style="font-size:8.5pt; color:#555;">
                    Disetujui {{ $kepalaApproval->decided_at->translatedFormat('d M Y') }}
                </span>
            </div>
        </td>

        {{-- Kanan: Foreman GA --}}
        <td style="width:33%; text-align:center; vertical-align:bottom; padding:0 6px;">
            @if($gaApproval && $sigPath($gaApproval->approver_signature))
            <img src="{{ $sigPath($gaApproval->approver_signature) }}"
                 style="max-width:100px; max-height:60px; display:block; margin:0 auto;"
                 alt="TTD GA">
            @else
            <div style="height:55px;"></div>
            @endif
            <div style="border-top:1.5px solid #000; padding-top:3px; font-size:9.5pt;">
                @if($gaApproval)
                    {{ $gaApproval->approver->full_name ?? '-' }}<br>
                    <span style="font-size:8.5pt; color:#555;">
                        Disetujui {{ $gaApproval->decided_at->translatedFormat('d M Y') }}
                    </span>
                @else
                    &nbsp;<br>
                    <span style="font-size:8.5pt; color:#aaa;">Belum diproses</span>
                @endif
            </div>
        </td>
    </tr>
</table>
@endif

{{-- ===== CATATAN ===== --}}
<div class="notes-section">
    <div class="notes-title">Catatan :</div>
    <ul class="notes-list">
        <li>Permintaan supaya diajukan sehari sebelum perjalanan.</li>
        <li>Pool kendaraan yang ada dan terbatas.</li>
        <li>Dokumen ini dicetak secara digital melalui sistem peminjaman kendaraan.</li>
    </ul>
</div>

</body>
</html>