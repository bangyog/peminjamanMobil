<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Formulir Pengajuan Kendaraan Dinas</title>
    <style>
        @page {
            margin: 25mm 25mm 20mm 30mm;
        }
        
        body {
            font-family: 'Calibri', Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.15;
            color: #000;
        }
        
        /* Company Header */
        .company-header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        
        .logo-section {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }
        
        .logo-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #1e3a8a;
        }
        
        .logo-text {
            color: white;
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .company-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 15px;
        }
        
        .company-name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .company-address {
            font-size: 10pt;
            color: #333;
        }
        
        .header-right-top {
            display: table-cell;
            text-align: right;
            vertical-align: top;
            width: 150px;
        }
        
        /* Title */
        .title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0 25px 0;
        }
        
        /* Two Column Row */
        .two-col-row {
            width: 100%;
            margin-bottom: 25px;
        }
        
        .two-col-row table {
            width: 100%;
        }
        
        .two-col-row td {
            width: 50%;
            font-weight: bold;
            padding: 0;
        }
        
        /* Form Section */
        .form-section {
            margin-bottom: 25px;
        }
        
        .form-line {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }
        
        .form-line .label {
            display: table-cell;
            width: 200px;
            padding-right: 10px;
        }
        
        .form-line .separator {
            display: table-cell;
            width: 10px;
            text-align: center;
        }
        
        .form-line .underline {
            display: table-cell;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }
        
        /* Main Table */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        
        .main-table td {
            border: 1px solid #000;
            padding: 5px 10px;
            vertical-align: top;
        }
        
        .main-table .header-row td {
            text-align: center;
            font-weight: bold;
            padding: 8px;
        }
        
        .main-table .section-row td {
            font-weight: bold;
            text-align: center;
            padding: 5px;
        }
        
        .main-table .content-cell {
            height: 100px;
            vertical-align: top;
        }
        
        .main-table .signature-cell {
            text-align: center;
            vertical-align: middle;
        }
        
        .signature-img {
            max-width: 100px;
            max-height: 60px;
        }
        
        /* Assignment Section */
        .assignment-section {
            margin-bottom: 100px;
        }
        
        .assignment-title {
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .assignment-line {
            margin-bottom: 10px;
            line-height: 1.8;
        }
        
        .dotted-underline {
            display: inline-block;
            border-bottom: 1px dotted #000;
            min-width: 250px;
            text-align: center;
        }
        
        .signature-area {
            text-align: right;
            margin-top: 80px;
            margin-right: 50px;
        }
        
        .signature-line {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 200px;
            text-align: center;
            margin-bottom: 5px;
        }
        
        /* Notes Section */
        .notes-section {
            margin-top: 30px;
        }
        
        .notes-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Company Header -->
    <div class="company-header">
        <!-- Logo -->
        <div class="logo-section">
            <div class="logo-circle">
                <div class="logo-text">SWG</div>
            </div>
        </div>
        
        <!-- Company Info -->
        <div class="company-info">
            <div class="company-name">PT. Swabina Gatra</div>
            <div class="company-address">Jl. R.A Kartini 21 A Gresik</div>
        </div>
        
        <!-- Header Right -->
        <div class="header-right-top">
            Gresik/Tuban,<br>
            {{ $loanRequest->created_at->format('d F Y') }}
        </div>
    </div>

    <!-- Title -->
    <div class="title">
        PERMINTAAN PEMINJAMAN KENDARAAN DINAS
    </div>

    <!-- Two Column: Diminta Oleh & Mengetahui Atasan -->
    <div class="two-col-row">
        <table>
            <tr>
                <td>Diminta Oleh:</td>
                <td>Mengetahui Atasan:</td>
            </tr>
        </table>
    </div>

    <!-- Form Section -->
    <div class="form-section">
        <div class="form-line">
            <span class="label">Nama Pemakai</span>
            <span class="separator">:</span>
            <span class="underline">{{ $loanRequest->requester->name }}</span>
        </div>

        <div class="form-line">
            <span class="label">Keperluan</span>
            <span class="separator">:</span>
            <span class="underline">{{ $loanRequest->purpose }}</span>
        </div>

        <div class="form-line">
            <span class="label">Tujuan</span>
            <span class="separator">:</span>
            <span class="underline">{{ $loanRequest->destination }}</span>
        </div>

        <div class="form-line">
            <span class="label">Kendaraan yang Diminta</span>
            <span class="separator">:</span>
            <span class="underline">
                @if($loanRequest->preferredVehicle)
                    {{ $loanRequest->preferredVehicle->brand }} {{ $loanRequest->preferredVehicle->model }} ({{ $loanRequest->preferredVehicle->license_plate }})
                @endif
            </span>
        </div>

        <div class="form-line">
            <span class="label">Lain-lain</span>
            <span class="separator">:</span>
            <span class="underline">
                Jumlah Penumpang: {{ $loanRequest->passenger_count }} orang
                @if($loanRequest->notes) | {{ $loanRequest->notes }} @endif
            </span>
        </div>
    </div>

    <!-- Main Table -->
    <table class="main-table">
        <!-- Header Row -->
        <tr class="header-row">
            <td width="60%">DIISI PEMAKAI</td>
            <td width="20%">Paraf Pemakai</td>
            <td width="20%">Paraf Foreman<br>Pelayanan Umum</td>
        </tr>
        
        <!-- Berangkat Section Header -->
        <tr class="section-row">
            <td colspan="3">Berangkat</td>
        </tr>
        
        <!-- Berangkat Content -->
        <tr>
            <td class="content-cell">
                Siap di :<br>
                {{ $loanRequest->siap_di }}<br><br>
                Jam & Tanggal :<br>
                {{ $loanRequest->depart_at->format('H:i') }} WIB, {{ $loanRequest->depart_at->format('d F Y') }}
            </td>
            <td class="content-cell signature-cell">
                @if($loanRequest->requester_signature)
                    <img src="{{ storage_path('app/public/' . $loanRequest->requester_signature) }}" 
                         class="signature-img" alt="Signature">
                @endif
            </td>
            <td class="content-cell signature-cell">
                @if($loanRequest->approvals && $loanRequest->approvals->where('status', 'approved')->first())
                    ✓
                @endif
            </td>
        </tr>
        
        <!-- Kembali Section Header -->
        <tr class="section-row">
            <td colspan="3">Kembali</td>
        </tr>
        
        <!-- Kembali Content -->
        <tr>
            <td class="content-cell">
                Siap di :<br>
                {{ $loanRequest->kembali_di }}<br><br>
                Jam & Tanggal :<br>
                {{ $loanRequest->expected_return_at->format('H:i') }} WIB, {{ $loanRequest->expected_return_at->format('d F Y') }}
            </td>
            <td class="content-cell signature-cell"></td>
            <td class="content-cell signature-cell"></td>
        </tr>
    </table>

    <!-- Assignment Section -->
    <div class="assignment-section">
        <div class="assignment-title">OLEH PIMPINAN KENDARAAN</div>
        
        <div class="assignment-line">
            Diperintahkan kepada Sdr 
            <span class="dotted-underline">
                @if($loanRequest->assignment && $loanRequest->assignment->driver)
                    {{ $loanRequest->assignment->driver->name }}
                @endif
            </span>
        </div>
        
        <div class="assignment-line">
            Untuk melayani sesuai dengan permintaan tersebut diatas
        </div>
        
        <div class="assignment-line">
            Dengan kendaraan 
            <span class="dotted-underline">
                @if($loanRequest->assignment && $loanRequest->assignment->vehicle)
                    {{ $loanRequest->assignment->vehicle->brand }} {{ $loanRequest->assignment->vehicle->model }} ({{ $loanRequest->assignment->vehicle->license_plate }})
                @endif
            </span>
        </div>
        
        <div class="signature-area">
            <div class="signature-line"></div><br>
            <strong>Foreman Pelayanan Umum</strong>
        </div>
    </div>

    <!-- Notes Section -->
    <div class="notes-section">
        <div class="notes-title">Catatan:</div>
        <div>
            Permintaan supaya diajukan sehari sebelum dan diterima bagian kendaraan Pool kendaraan yang ada dan terbatas.
        </div>
    </div>
</body>
</html>
