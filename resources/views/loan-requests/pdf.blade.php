<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pengajuan Kendaraan Dinas</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 18mm 18mm 16mm 18mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #000;
            line-height: 1.45;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        /* =========================
           HEADER
        ========================== */

        .header {
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 22px;
        }

        .header td {
            vertical-align: middle;
        }

        .logo-wrapper {
            width: 90px;
        }

        .logo {
            width: 72px;
            height: auto;
        }

        .logo-placeholder {
            width: 72px;
            height: 72px;
            border: 1px solid #999;
            background: #f2f2f2;
            text-align: center;
            line-height: 72px;
            font-size: 10px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
            letter-spacing: 0.3px;
        }

        .company-address {
            font-size: 12px;
        }

        .header-date {
            width: 220px;
            text-align: right;
            font-size: 12px;
        }

        /* =========================
           TITLE
        ========================== */

        .document-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 24px;
            letter-spacing: 0.5px;
        }

        /* =========================
           SIGNATURE TOP
        ========================== */

        .approval-table {
            margin-bottom: 24px;
        }

        .approval-box {
            width: 50%;
            text-align: center;
            padding: 0 15px;
        }

        .approval-title {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .signature-space {
            height: 85px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .signature-img {
            max-width: 130px;
            max-height: 70px;
            object-fit: contain;
        }

        .signature-name {
            margin-top: 8px;
            font-weight: bold;
            text-decoration: underline;
        }

        .signature-info {
            margin-top: 3px;
            font-size: 11px;
        }

        /* =========================
           INFORMATION SECTION
        ========================== */

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .info-wrapper {
            border: 1px solid #000;
            padding: 12px 14px;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 4px 0;
        }

        .label {
            width: 220px;
            font-weight: bold;
        }

        .separator {
            width: 15px;
            text-align: center;
        }

        .value {
            word-break: break-word;
        }

        /* =========================
           MAIN GRID
        ========================== */

        .main-grid {
            margin-bottom: 22px;
        }

        .main-grid th {
            border: 1px solid #000;
            background: #e9e9e9;
            padding: 10px 8px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }

        .main-grid td {
            border: 1px solid #000;
        }

        .content-cell {
            padding: 0;
        }

        .inner-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inner-title {
            background: #f3f3f3;
            border-bottom: 1px solid #bbb;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            text-transform: uppercase;
        }

        .inner-content {
            padding: 10px 12px;
            min-height: 85px;
        }

        .inner-content div {
            margin-bottom: 6px;
        }

        .signature-cell {
            text-align: center;
            vertical-align: middle;
            padding: 12px;
        }

        .signature-cell .signature-space {
            height: 90px;
        }

        .signature-date {
            margin-top: 4px;
            font-size: 10px;
        }

        /* =========================
           VEHICLE COMMAND
        ========================== */

        .instruction-box {
            border: 1px solid #000;
            padding: 14px;
            margin-bottom: 22px;
        }

        .instruction-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 12px;
            text-decoration: underline;
        }

        .instruction-table td {
            padding: 5px 0;
        }

        /* =========================
           FINAL SIGNATURE
        ========================== */

        .final-signature {
            width: 280px;
            margin-left: auto;
            text-align: center;
        }

        .final-signature .signature-space {
            height: 90px;
        }

        /* =========================
           FOOTER NOTE
        ========================== */

        .footer-note {
            margin-top: 28px;
            border-top: 1px dashed #000;
            padding-top: 8px;
            font-size: 11px;
        }

        /* =========================
           PRINT FIX
        ========================== */

        tr,
        td,
        th {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

@php
    \Carbon\Carbon::setLocale('id');

    $kepalaApproval = $loanRequest->approvals
        ->where('approval_level', 'kepala')
        ->where('decision', 'approved')
        ->first();

    $gaApproval = $loanRequest->approvals
        ->where('approval_level', 'ga')
        ->where('decision', 'approved')
        ->first();

    $vehicle = optional($loanRequest->assignment)->assignedVehicle
        ?? $loanRequest->preferredVehicle
        ?? null;

    $vehicleText = $vehicle
        ? $vehicle->brand . ' ' . $vehicle->model . ' - ' . $vehicle->plate_no
        : ($loanRequest->requested_vehicle_text ?? '-');

    $driverName = optional($loanRequest->assignment)->assigned_driver_name
        ?? optional($loanRequest->requester)->full_name
        ?? '-';

    $kepalaName = $kepalaApproval
        ? optional($kepalaApproval->approver)->full_name ?? '-'
        : '-';

    $gaName = $gaApproval
        ? optional($gaApproval->approver)->full_name ?? 'Admin GA'
        : 'Admin GA';

    $anggaranAwal = data_get($loanRequest, 'anggaran_awal');

    $kota = $loanRequest->request_city ?? 'Surabaya';

    $sig = function ($path) {
        if (!$path) return null;

        $abs = storage_path('app/public/' . $path);

        return file_exists($abs)
            ? 'file://' . $abs
            : null;
    };

    $logoCandidates = [
        public_path('images/swa-logo.png'),
        public_path('img/swa-logo.png'),
        public_path('images/logo.png'),
    ];

    $logoSrc = null;

    foreach ($logoCandidates as $candidate) {
        if (file_exists($candidate)) {
            $logoSrc = 'file://' . $candidate;
            break;
        }
    }

    $userSig = $sig($loanRequest->requester_signature);

    $kepalaSig = $kepalaApproval
        ? $sig($kepalaApproval->approver_signature)
        : null;

    $gaSig = $gaApproval
        ? $sig($gaApproval->approver_signature)
        : null;

    $bulanId = [
        '',
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    $tglAju = \Carbon\Carbon::parse($loanRequest->created_at);

    $tglSurat = $tglAju->day . ' ' .
        $bulanId[$tglAju->month] . ' ' .
        $tglAju->year;

    $formatDateTime = function ($date) use ($bulanId) {
        if (!$date) return '-';

        $d = \Carbon\Carbon::parse($date);

        return $d->format('H:i') .
            ' WIB, ' .
            $d->day . ' ' .
            $bulanId[$d->month] . ' ' .
            $d->year;
    };

    $formatDate = function ($date) use ($bulanId) {
        if (!$date) return '-';

        $d = \Carbon\Carbon::parse($date);

        return $d->day . ' ' .
            $bulanId[$d->month] . ' ' .
            $d->year;
    };
@endphp

<!-- =========================
     HEADER
========================= -->

<table class="header">
    <tr>

        <td class="logo-wrapper">

            @if($logoSrc)
                <img src="{{ asset('images/swa-logo.png') }}" class="logo" alt="Logo">
            @else
                <div class="logo-placeholder">LOGO</div>
            @endif

        </td>

        <td>

            <div class="company-name">
                PT. Swabina Gatra
            </div>

            <div class="company-address">
                Jl. R.A Kartini No. 21 A, Gresik
            </div>

        </td>

        <td class="header-date">
            {{ $kota }}, {{ $tglSurat }}
        </td>

    </tr>
</table>

<!-- =========================
     TITLE
========================= -->

<div class="document-title">
    Permintaan Peminjaman Kendaraan Dinas
</div>

<!-- =========================
     APPROVAL SECTION
========================= -->

<table class="approval-table">
    <tr>

        <td class="approval-box">

            <div class="approval-title">
                Diminta Oleh
            </div>

            <div class="signature-space">

                @if($userSig)
                    <img src="{{ $userSig }}" class="signature-img">
                @endif

            </div>

            <div class="signature-name">
                {{ $loanRequest->requester->full_name ?? '-' }}
            </div>

            <div class="signature-info">
                {{ optional($loanRequest->unit)->name ?? '-' }}
            </div>

        </td>

        <td class="approval-box">

            <div class="approval-title">
                Mengetahui Atasan Divisi
            </div>

            <div class="signature-space">

                @if($kepalaSig)
                    <img src="{{ $kepalaSig }}" class="signature-img">
                @endif

            </div>

            <div class="signature-name">
                {{ $kepalaName }}
            </div>

            <div class="signature-info">

                @if($kepalaApproval && $kepalaApproval->decided_at)
                    Disetujui {{ $formatDate($kepalaApproval->decided_at) }}
                @endif

            </div>

        </td>

    </tr>
</table>

<!-- =========================
     INFO SECTION
========================= -->

<div class="section-title">
    Informasi Pengajuan
</div>

<div class="info-wrapper">

    <table class="info-table">

        <tr>
            <td class="label">Nama Pemakai</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $loanRequest->requester->full_name ?? '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">Keperluan</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $loanRequest->purpose ?? '-' }}
            </td>
        </tr>

        @if($loanRequest->projek)
        <tr>
            <td class="label">Projek</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $loanRequest->projek }}
            </td>
        </tr>
        @endif

        <tr>
            <td class="label">Tujuan</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $loanRequest->destination ?? '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">Kendaraan yang Diminta</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $vehicleText }}
            </td>
        </tr>

        <tr>
            <td class="label">Catatan Tambahan</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $loanRequest->notes ?: '-' }}
            </td>
        </tr>

    </table>

</div>

<!-- =========================
     MAIN GRID
========================= -->

<table class="main-grid">

    <thead>

        <tr>

            <th style="width: 50%;">
                Diisi Pemakai
            </th>

            <th style="width: 25%;">
                Paraf Pemakai
            </th>

            <th style="width: 25%;">
                Paraf Foreman Pelayanan Umum
            </th>

        </tr>

    </thead>

    <tbody>

        <tr>

            <td class="content-cell">

                <table class="inner-table">

                    <tr>
                        <td class="inner-title">
                            Berangkat
                        </td>
                    </tr>

                    <tr>
                        <td class="inner-content">

                            <div>
                                <span class="bold">Siap di</span> :
                                {{ $loanRequest->siap_di ?? 'Kantor Pusat' }}
                            </div>

                            @if($anggaranAwal)
                            <div>
                                <span class="bold">Anggaran Awal</span> :
                                Rp {{ number_format((float) $anggaranAwal, 0, ',', '.') }}
                            </div>
                            @endif

                            <div>
                                <span class="bold">Jam & Tanggal</span> :
                                {{ $formatDateTime($loanRequest->depart_at) }}
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td class="inner-title">
                            Kembali
                        </td>
                    </tr>

                    <tr>
                        <td class="inner-content">

                            <div>
                                <span class="bold">Siap di</span> :
                                {{ $loanRequest->kembali_di ?? 'Kantor Pusat' }}
                            </div>

                            <div>
                                <span class="bold">Jam & Tanggal</span> :
                                {{ $formatDateTime($loanRequest->expected_return_at) }}
                            </div>

                        </td>
                    </tr>

                </table>

            </td>

            <td class="signature-cell">

                <div class="signature-space">

                    @if($userSig)
                        <img src="{{ $userSig }}" class="signature-img">
                    @endif

                </div>

                <div class="signature-name">
                    {{ $loanRequest->requester->full_name ?? '-' }}
                </div>

            </td>

            <td class="signature-cell">

                <div class="signature-space">

                    @if($gaSig)
                        <img src="{{ $gaSig }}" class="signature-img">
                    @endif

                </div>

                <div class="signature-name">
                    {{ $gaName }}
                </div>

                <div class="signature-date">

                    @if($gaApproval && $gaApproval->decided_at)
                        {{ $formatDate($gaApproval->decided_at) }}
                    @endif

                </div>

            </td>

        </tr>

    </tbody>

</table>

<!-- =========================
     VEHICLE COMMAND
========================= -->

<div class="instruction-box">

    <div class="instruction-title">
        Oleh Pimpinan Kendaraan
    </div>

    <table class="instruction-table">

        <tr>

            <td style="width: 240px;">
                Diperintahkan kepada Sdr.
            </td>

            <td style="width: 15px;">
                :
            </td>

            <td class="bold">
                {{ $driverName }}
            </td>

        </tr>

        <tr>

            <td colspan="3">
                Untuk melayani sesuai dengan permintaan tersebut di atas.
            </td>

        </tr>

        <tr>

            <td>
                Dengan kendaraan
            </td>

            <td>
                :
            </td>

            <td class="bold">
                {{ $vehicleText }}
            </td>

        </tr>

    </table>

</div>

<!-- =========================
     FINAL SIGNATURE
========================= -->

<div class="final-signature">

    <div>
        Foreman Pelayanan Umum
    </div>

    <div class="signature-space">

        @if($gaSig)
            <img src="{{ $gaSig }}" class="signature-img">
        @endif

    </div>

    <div class="signature-name">
        {{ $gaName }}
    </div>

</div>

<!-- =========================
     FOOTER
========================= -->

<div class="footer-note">

    <strong>Catatan:</strong>

    Permintaan kendaraan agar diajukan minimal satu hari sebelumnya dan
    diterima oleh bagian kendaraan. Ketersediaan kendaraan operasional
    menyesuaikan kondisi unit yang tersedia.

</div>

</body>
</html>