<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Formulir Pengajuan Kendaraan Dinas</title>
<style>
@page { margin: 20mm 20mm 18mm 25mm; }
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: Arial, sans-serif; font-size:12px; color:#000; line-height:1.5; }
table { border-collapse: collapse; }
.bold { font-weight: bold; }
.center { text-align: center; }
.label { width: 185px; }
.colon { width: 10px; }
</style>
</head>
<body>

@php
    \Carbon\Carbon::setLocale('id');

    $kepalaApproval = $loanRequest->approvals
        ->where('approval_level','kepala')->where('decision','approved')->first();
    $gaApproval = $loanRequest->approvals
        ->where('approval_level','ga')->where('decision','approved')->first();

    $vehicle = optional($loanRequest->assignment)->assignedVehicle
            ?? $loanRequest->preferredVehicle ?? null;
    $vehicleText = $vehicle
        ? $vehicle->brand.' '.$vehicle->model.' – '.$vehicle->plate_no
        : ($loanRequest->requested_vehicle_text ?? '-');

    $driverName = optional($loanRequest->assignment)->assigned_driver_name
               ?? optional($loanRequest->requester)->full_name ?? '-';

    $kepalaName = $kepalaApproval
        ? optional($kepalaApproval->approver)->full_name ?? '-' : '-';
    $gaName = $gaApproval
        ? optional($gaApproval->approver)->full_name ?? 'Foreman Pelayanan Umum'
        : 'Foreman Pelayanan Umum';

    $anggaranAwal = data_get($loanRequest, 'anggaran_awal');
    $kota = $loanRequest->request_city ?? 'Gresik';

    // ✅ Fix: gunakan file:// untuk DomPDF bisa load gambar
    $sig = function($path) {
        if (!$path) return null;
        $abs = storage_path('app/public/'.$path);
        return file_exists($abs) ? 'file://'.$abs : null;
    };

    $logoPath = public_path('images/swa-logo.png');
    $logoSrc  = file_exists($logoPath) ? 'file://'.$logoPath : null;

    $userSig   = $sig($loanRequest->requester_signature);
    $kepalaSig = $kepalaApproval ? $sig($kepalaApproval->approver_signature) : null;
    $gaSig     = $gaApproval    ? $sig($gaApproval->approver_signature)      : null;

    // ✅ Fix: format tanggal Indonesia manual
    $bulanId = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $tglBuat   = \Carbon\Carbon::parse($loanRequest->created_at);
    $tglIndo   = $tglBuat->day.' '.$bulanId[$tglBuat->month].' '.$tglBuat->year;

    $formatTgl = function($dt) use ($bulanId) {
        if (!$dt) return '-';
        $c = \Carbon\Carbon::parse($dt);
        return $c->format('H:i').' WIB, '.$c->day.' '.$bulanId[$c->month].' '.$c->year;
    };
    $formatTglShort = function($dt) use ($bulanId) {
        if (!$dt) return '-';
        $c = \Carbon\Carbon::parse($dt);
        return $c->day.' '.$bulanId[$c->month].' '.$c->year;
    };
@endphp

{{-- ===== HEADER ===== --}}
<table style="width:100%; margin-bottom:10px; padding-bottom:8px; border-bottom:2px solid #000;">
    <tr>
        {{-- Logo --}}
        <td style="width:70px; vertical-align:middle;">
            @if($logoSrc)
                <img src="{{ $logoSrc }}" style="width:55px; height:55px; object-fit:contain;" alt="Logo">
            @else
                {{-- ✅ Fix: solid color, bukan gradient --}}
                <div style="width:55px; height:55px; border-radius:28px; background:#1e3a8a; border:2px solid #1e3a8a; text-align:center; padding-top:14px;">
                    <span style="color:#fff; font-size:13px; font-weight:bold;">SWG</span>
                </div>
            @endif
        </td>
        <td style="vertical-align:middle; padding-left:10px;">
            <div style="font-size:14px; font-weight:bold;">PT. Swabina Gatra</div>
            <div style="font-size:11px; color:#444;">Jl. R.A Kartini 21 A Gresik</div>
        </td>
        <td style="text-align:right; vertical-align:top; font-size:12px; width:170px;">
            {{ $kota }}, {{ $tglIndo }}
        </td>
    </tr>
</table>

{{-- ===== TITLE ===== --}}
<p style="text-align:center; font-size:14px; font-weight:bold; margin:12px 0 14px; text-decoration:underline;">
    PERMINTAAN PEMINJAMAN KENDARAAN DINAS
</p>

{{-- ===== TANDA TANGAN ATAS: Diminta Oleh & Mengetahui Atasan ===== --}}
<table style="width:100%; margin-bottom:14px;">
    <tr>
        <td style="width:50%; text-align:center; padding:0 12px 0 0; vertical-align:top;">
            <div style="font-size:12px; font-weight:bold; margin-bottom:6px;">Diminta Oleh</div>
            <div style="height:100px; text-align:center; vertical-align:middle;">
                @if($userSig)
                    <img src="{{ $userSig }}" style="max-height:100px; max-width:140px;" alt="">
                @endif
            </div>
            <div style="border-top:1px solid #000; padding-top:3px; font-size:12px; font-weight:bold;">
                {{ $loanRequest->requester->full_name ?? '-' }}
            </div>
            <div style="font-size:11px; color:#555;">{{ $loanRequest->unit->name ?? '' }}</div>
        </td>
        <td style="width:50%; text-align:center; padding:0 0 0 12px; border-left:1px solid #ccc; vertical-align:top;">
            <div style="font-size:12px; font-weight:bold; margin-bottom:6px;">Mengetahui Atasan Divisi</div>
            <div style="height:100px; text-align:center; vertical-align:middle;">
                @if($kepalaSig)
                    <img src="{{ $kepalaSig }}" style="max-height:100px; max-width:140px;" alt="">
                @endif
            </div>
            <div style="border-top:1px solid #000; padding-top:3px; font-size:12px; font-weight:bold;">
                {{ $kepalaName }}
            </div>
            @if($kepalaApproval && $kepalaApproval->decided_at)
            <div style="font-size:11px; color:#555;">
                Disetujui {{ $formatTglShort($kepalaApproval->decided_at) }}
            </div>
            @endif
        </td>
    </tr>
</table>

{{-- ===== FORM FIELDS ===== --}}
<table style="width:100%; margin-bottom:14px;">
    <tr>
        <td class="label" style="font-size:12px; padding:3px 0;">Nama Pemakai</td>
        <td class="colon" style="font-size:12px; padding:3px 0;">:</td>
        <td style="font-size:12px; padding:3px 0 3px 6px; border-bottom:1px solid #000;">
            {{ $loanRequest->requester->full_name ?? '-' }}
        </td>
    </tr>
    <tr>
        <td style="font-size:12px; padding:3px 0;">Keperluan</td>
        <td style="font-size:12px; padding:3px 0;">:</td>
        <td style="font-size:12px; padding:3px 0 3px 6px; border-bottom:1px solid #000;">
            {{ $loanRequest->purpose ?? '-' }}
        </td>
    </tr>
    @if($loanRequest->projek)
    <tr>
        <td style="font-size:12px; padding:3px 0;">Projek</td>
        <td style="font-size:12px; padding:3px 0;">:</td>
        <td style="font-size:12px; padding:3px 0 3px 6px; border-bottom:1px solid #000;">
            {{ $loanRequest->projek }}
        </td>
    </tr>
    @endif
    <tr>
        <td style="font-size:12px; padding:3px 0;">Tujuan</td>
        <td style="font-size:12px; padding:3px 0;">:</td>
        <td style="font-size:12px; padding:3px 0 3px 6px; border-bottom:1px solid #000;">
            {{ $loanRequest->destination ?? '-' }}
        </td>
    </tr>
    <tr>
        <td style="font-size:12px; padding:3px 0;">Kendaraan yang Diminta</td>
        <td style="font-size:12px; padding:3px 0;">:</td>
        <td style="font-size:12px; padding:3px 0 3px 6px; border-bottom:1px solid #000;">
            {{ $vehicleText }}
        </td>
    </tr>
    <tr>
        <td style="font-size:12px; padding:3px 0;">Lain-lain / Catatan</td>
        <td style="font-size:12px; padding:3px 0;">:</td>
        <td style="font-size:12px; padding:3px 0 3px 6px; border-bottom:1px solid #000;">
            {{ $loanRequest->notes ?: '-' }}
        </td>
    </tr>
</table>

{{-- ===== TABEL UTAMA ===== --}}
<table style="width:100%; border-collapse:collapse; margin-bottom:16px; font-size:12px;">

    {{-- Header row --}}
    <tr>
        <td style="width:60%; text-align:center; font-weight:bold; background:#e0e0e0; border:1px solid #000; padding:5px 8px;">
            DIISI PEMAKAI
        </td>
        <td style="width:20%; text-align:center; font-weight:bold; background:#e0e0e0; border:1px solid #000; padding:5px 4px;">
            Paraf Pemakai
        </td>
        <td style="width:20%; text-align:center; font-weight:bold; background:#e0e0e0; border:1px solid #000; padding:5px 4px;">
            Paraf Foreman<br>Pelayanan Umum
        </td>
    </tr>

    {{-- Content row --}}
    <tr>
        {{-- Kiri: Berangkat + Kembali --}}
        <td style="border:1px solid #000; padding:0; vertical-align:top;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="text-align:center; font-weight:bold; background:#eeeeee; border-bottom:1px solid #000; padding:4px 8px;">
                        Berangkat
                    </td>
                </tr>
                <tr>
                    <td style="padding:8px 12px; line-height:2;">
                        <span style="font-weight:bold;">Siap di</span>&nbsp;: {{ $loanRequest->siap_di ?? '-' }}<br>
                        @if($anggaranAwal)
                        <span style="font-weight:bold;">Anggaran Awal</span>&nbsp;: Rp {{ number_format((float)$anggaranAwal, 0, ',', '.') }}<br>
                        @endif
                        <span style="font-weight:bold;">Jam &amp; Tanggal</span>&nbsp;: {{ $formatTgl($loanRequest->depart_at) }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:bold; background:#eeeeee; border-top:1px solid #000; border-bottom:1px solid #000; padding:4px 8px;">
                        Kembali
                    </td>
                </tr>
                <tr>
                    <td style="padding:8px 12px; line-height:2;">
                        <span style="font-weight:bold;">Siap di</span>&nbsp;: {{ $loanRequest->kembali_di ?? '-' }}<br>
                        <span style="font-weight:bold;">Jam &amp; Tanggal</span>&nbsp;: {{ $formatTgl($loanRequest->expected_return_at) }}
                    </td>
                </tr>
            </table>
        </td>

        {{-- Paraf Pemakai --}}
        <td style="border:1px solid #000; text-align:center; vertical-align:middle; padding:8px 4px;">
            @if($userSig)
                <img src="{{ $userSig }}" style="max-height:100px; max-width:140px; display:block; margin:0 auto 4px;" alt="">
            @else
                <div style="height:100px;"></div>
            @endif
            <div style="border-top:1px solid #000; padding-top:3px; font-size:11px; font-weight:bold;">
                {{ $loanRequest->requester->full_name ?? '-' }}
            </div>
        </td>

        {{-- Paraf Foreman GA --}}
        <td style="border:1px solid #000; text-align:center; vertical-align:middle; padding:8px 4px;">
            @if($gaSig)
                <img src="{{ $gaSig }}" style="max-height:100px; max-width:140px; display:block; margin:0 auto 4px;" alt="">
            @else
                <div style="height:100px;"></div>
            @endif
            @if($gaApproval)
            <div style="border-top:1px solid #000; padding-top:3px; font-size:11px; font-weight:bold;">
                {{ $gaName }}
            </div>
            @endif
        </td>
    </tr>
</table>

{{-- ===== OLEH PIMPINAN KENDARAAN ===== --}}
<table style="width:100%; margin-bottom:16px;">
    <tr>
        <td style="width:62%; vertical-align:top; padding-right:20px;">
            <p style="font-size:12px; font-weight:bold; margin-bottom:12px;">
                OLEH PIMPINAN KENDARAAN
            </p>
            <table style="width:100%;">
                <tr>
                    <td style="font-size:12px; white-space:nowrap; padding:3px 0;">Diperintahkan kepada Sdr.</td>
                    <td style="font-size:12px; padding:3px 0 3px 8px; width:100%;">
                        {{ $driverName }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size:12px; padding:6px 0;">
                        Untuk melayani sesuai dengan permintaan tersebut diatas
                    </td>
                </tr>
                <tr>
                    <td style="font-size:12px; white-space:nowrap; padding:3px 0;">Dengan kendaraan</td>
                    <td style="font-size:12px; padding:3px 0 3px 8px; width:100%;">
                        {{ $vehicleText }}
                    </td>
                </tr>
            </table>
        </td>
        <td style="width:38%; text-align:center; vertical-align:bottom; padding-top:6px;">
            @if($gaSig)
                <img src="{{ $gaSig }}" style="max-height:100px; max-width:140px; display:block; margin:0 auto 4px;" alt="">
            @else
                <div style="height:100px;"></div>
            @endif
            <div style="border-top:1.5px solid #000; padding-top:4px; font-size:12px; font-weight:bold;">
                Foreman Pelayanan Umum
            </div>
            <div style="font-size:11px; color:#333; margin-top:2px;">
                {{ $gaName }}
            </div>
            @if($gaApproval && $gaApproval->decided_at)
            <div style="font-size:10px; color:#666;">
                {{ $formatTglShort($gaApproval->decided_at) }}
            </div>
            @endif
        </td>
    </tr>
</table>

{{-- ===== CATATAN ===== --}}
<p style="font-size:11px; margin-top:8px; border-top:1px solid #ccc; padding-top:6px; color:#444;">
    <strong>Catatan :</strong>
    Permintaan supaya diajukan sehari sebelum dan diterima bagian kendaraan.
    Pool kendaraan yang ada dan terbatas.
</p>

</body>
</html>