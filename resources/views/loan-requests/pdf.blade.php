<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Formulir Pengajuan Kendaraan Dinas</title>
<style>
@page { margin: 20mm 20mm 18mm 25mm; }
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: Arial, sans-serif; font-size:13px; color:#000; line-height:1.5; }
</style>
</head>
<body>

@php
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

    $sig = function($path) {
        if (!$path) return null;
        $abs = storage_path('app/public/'.$path);
        return file_exists($abs) ? $abs : null;
    };

    $userSig   = $sig($loanRequest->requester_signature);
    $kepalaSig = $kepalaApproval ? $sig($kepalaApproval->approver_signature) : null;
    $gaSig     = $gaApproval    ? $sig($gaApproval->approver_signature)      : null;
@endphp

{{-- HEADER --}}
<table style="width:100%; border-collapse:collapse; border-bottom:2px solid #000; padding-bottom:8px; margin-bottom:10px;">
    <tr>
        <td style="width:70px; vertical-align:middle;">
            <div style="width:60px; height:60px; border-radius:50%; background:linear-gradient(135deg,#1e3a8a,#3b82f6); border:2px solid #1e3a8a; text-align:center; line-height:60px;">
                <span style="color:#fff; font-size:14px; font-weight:bold;">SWG</span>
            </div>
        </td>
        <td style="vertical-align:middle; padding-left:10px;">
            <div style="font-size:14px; font-weight:bold;">PT. Swabina Gatra</div>
            <div style="font-size:11px; color:#333;">Jl. R.A Kartini 21 A Gresik</div>
        </td>
        <td style="text-align:right; vertical-align:top; font-size:12px; width:160px;">
            {{ $kota }}, {{ \Carbon\Carbon::parse($loanRequest->created_at)->translatedFormat('d F Y') }}
        </td>
    </tr>
</table>

{{-- TITLE --}}
<p style="text-align:center; font-size:14px; font-weight:bold; margin:12px 0 14px;">
    PERMINTAAN PEMINJAMAN KENDARAAN DINAS
</p>

{{-- DIMINTA OLEH / MENGETAHUI ATASAN --}}
<table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
    <tr>
        <td style="width:50%; text-align:center; padding:0 10px 0 0;">
            <div style="font-size:13px; font-weight:bold; margin-bottom:6px;">Diminta Oleh</div>
            <div style="height:70px; text-align:center;">
                @if($userSig)
                    <img src="{{ $userSig }}" style="max-height:70px; width:auto; object-fit:contain;" alt="">
                @endif
            </div>
            <div style="border-top:1px solid #000; padding-top:4px; font-size:12px; font-weight:bold; margin-top:4px;">
                {{ $loanRequest->requester->full_name ?? '-' }}
            </div>
            <div style="font-size:11px; color:#555;">{{ $loanRequest->unit->name ?? '' }}</div>
        </td>
        <td style="width:50%; text-align:center; padding:0 0 0 10px; border-left:1px solid #ccc;">
            <div style="font-size:13px; font-weight:bold; margin-bottom:6px;">Mengetahui Atasan Divisi</div>
            <div style="height:70px; text-align:center;">
                @if($kepalaSig)
                    <img src="{{ $kepalaSig }}" style="max-height:70px; width:auto; object-fit:contain;" alt="">
                @endif
            </div>
            <div style="border-top:1px solid #000; padding-top:4px; font-size:12px; font-weight:bold; margin-top:4px;">
                {{ $kepalaName }}
            </div>
            @if($kepalaApproval)
            <div style="font-size:11px; color:#555;">
                Disetujui {{ \Carbon\Carbon::parse($kepalaApproval->decided_at)->translatedFormat('d M Y') }}
            </div>
            @endif
        </td>
    </tr>
</table>

{{-- FORM FIELDS --}}
<table style="width:100%; border-collapse:collapse; margin-bottom:12px;">
    <tr>
        <td style="width:185px; font-size:13px; padding:2px 0;">Nama Pemakai</td>
        <td style="width:10px; font-size:13px; padding:2px 0;">:</td>
        <td style="font-size:13px; padding:2px 0 2px 4px; border-bottom:1px solid #000;">{{ $loanRequest->requester->full_name ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-size:13px; padding:2px 0;">Keperluan</td>
        <td style="font-size:13px; padding:2px 0;">:</td>
        <td style="font-size:13px; padding:2px 0 2px 4px; border-bottom:1px solid #000;">{{ $loanRequest->purpose ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-size:13px; padding:2px 0;">Tujuan</td>
        <td style="font-size:13px; padding:2px 0;">:</td>
        <td style="font-size:13px; padding:2px 0 2px 4px; border-bottom:1px solid #000;">{{ $loanRequest->destination ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-size:13px; padding:2px 0;">Kendaraan yang Diminta</td>
        <td style="font-size:13px; padding:2px 0;">:</td>
        <td style="font-size:13px; padding:2px 0 2px 4px; border-bottom:1px solid #000;">{{ $vehicleText }}</td>
    </tr>
    <tr>
        <td style="font-size:13px; padding:2px 0;">Lain-lain</td>
        <td style="font-size:13px; padding:2px 0;">:</td>
        <td style="font-size:13px; padding:2px 0 2px 4px; border-bottom:1px solid #000;">{{ $loanRequest->notes ?: '-' }}</td>
    </tr>
</table>

{{-- MAIN TABLE --}}
<table style="width:100%; border-collapse:collapse; margin-bottom:16px; font-size:13px;">

    {{-- Header --}}
    <tr>
        <td style="width:62%; text-align:center; font-weight:bold; background:#ececec; border:1px solid #000; padding:6px 8px;">DIISI PEMAKAI</td>
        <td style="width:19%; text-align:center; font-weight:bold; background:#ececec; border:1px solid #000; padding:6px 4px;">Paraf Pemakai</td>
        <td style="width:19%; text-align:center; font-weight:bold; background:#ececec; border:1px solid #000; padding:6px 4px;">Paraf Foreman<br>Pelayanan Umum</td>
    </tr>

    {{-- Satu baris: kiri nested Berangkat+Kembali, kanan TTD 1 sel --}}
    <tr>
        {{-- Kiri: nested Berangkat + Kembali --}}
        <td style="border:1px solid #000; padding:0;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="text-align:center; font-weight:bold; background:#e0e0e0; border-bottom:1px solid #000; padding:4px 8px;">Berangkat</td>
                </tr>
                <tr>
                    <td style="padding:8px 10px; line-height:1.8;">
                        <span style="font-weight:bold;">Siap di</span> &nbsp;: {{ $loanRequest->siap_di ?? '-' }}<br>
                        @if($anggaranAwal)
                        <span style="font-weight:bold;">Anggaran Awal</span> &nbsp;: Rp {{ number_format((float)$anggaranAwal,0,',','.') }}<br>
                        @endif
                        <span style="font-weight:bold;">Jam &amp; Tanggal</span> &nbsp;:
                        @if($loanRequest->depart_at)
                            {{ \Carbon\Carbon::parse($loanRequest->depart_at)->format('H:i') }} WIB,
                            {{ \Carbon\Carbon::parse($loanRequest->depart_at)->translatedFormat('d F Y') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight:bold; background:#e0e0e0; border-top:1px solid #000; border-bottom:1px solid #000; padding:4px 8px;">Kembali</td>
                </tr>
                <tr>
                    <td style="padding:8px 10px; line-height:1.8;">
                        <span style="font-weight:bold;">Siap di</span> &nbsp;: {{ $loanRequest->kembali_di ?? '-' }}<br>
                        <span style="font-weight:bold;">Jam &amp; Tanggal</span> &nbsp;:
                        @if($loanRequest->expected_return_at)
                            {{ \Carbon\Carbon::parse($loanRequest->expected_return_at)->format('H:i') }} WIB,
                            {{ \Carbon\Carbon::parse($loanRequest->expected_return_at)->translatedFormat('d F Y') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
        </td>

        {{-- Kanan: Paraf Pemakai — 1 sel mencakup Berangkat+Kembali --}}
        <td style="border:1px solid #000; text-align:center; vertical-align:middle; padding:8px 4px;">
            @if($userSig)
                <img src="{{ $userSig }}" style="max-height:70px; width:auto; object-fit:contain; display:block; margin:0 auto 4px;" alt="">
            @else
                <div style="height:70px;"></div>
            @endif
            <div style="border-top:1px solid #000; padding-top:3px; font-size:11px;">
                {{ $loanRequest->requester->full_name ?? '-' }}
            </div>
        </td>

        {{-- Kanan: Paraf Foreman GA — 1 sel mencakup Berangkat+Kembali --}}
        <td style="border:1px solid #000; text-align:center; vertical-align:middle; padding:8px 4px;">
            @if($gaSig)
                <img src="{{ $gaSig }}" style="max-height:70px; width:auto; object-fit:contain; display:block; margin:0 auto 4px;" alt="">
            @else
                <div style="height:70px;"></div>
            @endif
            @if($gaApproval)
            <div style="border-top:1px solid #000; padding-top:3px; font-size:11px;">
                {{ $gaName }}
            </div>
            @endif
        </td>
    </tr>

</table>

{{-- OLEH PIMPINAN KENDARAAN --}}
<table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
    <tr>
        <td style="width:65%; vertical-align:top; padding-right:16px;">
            <p style="font-size:13px; font-weight:bold; margin-bottom:10px;">
                OLEH PIMPINAN KENDARAAN
            </p>

            <p style="font-size:13px; line-height:2.2;">
                Diperintahkan kepada Sdr.
                <span style="display:inline-block; min-width:180px;">
                    {{ $driverName }}
                </span>
            </p>

            <p style="font-size:13px; line-height:2.2;">
                Untuk melayani sesuai dengan permintaan tersebut diatas
            </p>

            <p style="font-size:13px; line-height:2.2;">
                Dengan kendaraan
                <span style="display:inline-block; min-width:180px;">
                    {{ $vehicleText }}
                </span>
            </p>
        </td>

        <td style="width:35%; text-align:center; vertical-align:bottom; padding-top:10px;">
            @if($gaSig)
                <img src="{{ $gaSig }}"
                     style="max-height:70px; width:auto; object-fit:contain; display:block; margin:0 auto 4px;"
                     alt="">
            @else
                <div style="height:70px;"></div>
            @endif

            <div style="border-top:1.5px solid #000; padding-top:4px; font-size:13px; font-weight:bold;">
                Foreman Pelayanan Umum
            </div>

            <div style="font-size:11px; color:#333;">
                {{ $gaName }}
            </div>
        </td>
    </tr>
</table>

{{-- CATATAN --}}
<p style="font-size:12px; margin-top:6px;">
    <strong>Catatan :</strong>
    Permintaan supaya diajukan sehari sebelum dan diterima bagian kendaraan.
    Pool kendaraan yang ada dan terbatas.
</p>

</body>
</html>