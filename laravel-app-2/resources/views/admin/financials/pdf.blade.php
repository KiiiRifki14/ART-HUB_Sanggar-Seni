<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan ART-HUB</title>
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 11px; 
            color: #1a1c1a;
            margin: 0;
            padding: 0;
        }
        .header {
            margin-bottom: 25px;
            border-bottom: 3px solid #705d00;
            padding-bottom: 15px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .header-table td {
            border: none;
            padding: 0;
        }
        .brand-title {
            font-size: 22px;
            font-weight: bold;
            color: #361f1a;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .brand-subtitle {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #705d00;
            margin-top: 5px;
            font-weight: bold;
        }
        .doc-meta {
            text-align: right;
            font-size: 10px;
            color: #827471;
            line-height: 1.5;
        }
        
        table.data-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        table.data-table th { 
            background-color: #361f1a; 
            color: #ffffff;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            padding: 10px 8px;
            border: 1px solid #361f1a;
        }
        table.data-table td { 
            border: 1px solid #e3e2e0; 
            padding: 9px 8px; 
            text-align: left; 
            vertical-align: middle;
        }
        table.data-table tbody tr:nth-child(even) {
            background-color: #faf9f6;
        }
        table.data-table tbody tr:hover {
            background-color: #f4f3f1;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        .badge {
            display: inline-block;
            padding: 3px 6px;
            font-size: 8px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .badge-info {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .badge-event-code {
            background-color: #efeeeb;
            color: #361f1a;
            border: 1px solid #d4c3bf;
        }

        .summary-container {
            margin-top: 30px;
            width: 100%;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
        .card {
            background-color: #ffffff;
            border: 1px solid #e3e2e0;
            border-radius: 8px;
            padding: 12px 15px;
            margin-right: 15px;
            text-align: center;
        }
        .card-primary {
            background: #361f1a;
            border-color: #361f1a;
            color: #ffffff;
        }
        .card-title {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #827471;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .card-primary .card-title {
            color: rgba(255,255,255,0.7);
        }
        .card-value {
            font-size: 16px;
            font-weight: bold;
            color: #361f1a;
            margin: 0;
        }
        .card-primary .card-value {
            color: #fcd400;
        }
        .card-subtitle {
            font-size: 8px;
            color: #827471;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <div class="brand-title">Sanggar Seni Cahaya Gumilang</div>
                    <div class="brand-subtitle">Laporan Keuangan per Event · ART-HUB</div>
                </td>
                <td class="doc-meta">
                    <strong>Tanggal Unduh:</strong> {{ now()->translatedFormat('d F Y') }}<br>
                    <strong>Waktu Unduh:</strong> {{ now()->format('H:i') }} WIB<br>
                    <strong>Diunduh Oleh:</strong> Pimpinan Sanggar
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 12%">Kode Event</th>
                <th style="width: 20%">Klien & Acara</th>
                <th style="width: 15%" class="text-right">Pendapatan</th>
                <th style="width: 15%" class="text-right">Honor Personel</th>
                <th style="width: 15%" class="text-right">Realisasi Ops</th>
                <th style="width: 15%" class="text-right">Laba Tetap (Fixed)</th>
                <th style="width: 8%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalRevenue = 0;
                $totalHonor = 0;
                $totalOps = 0;
                $totalProfit = 0;
                $totalLabaTerkunci = 0;
            @endphp
            @foreach($records as $rec)
            @php
                $totalRevenue += $rec->total_revenue;
                $totalHonor += $rec->total_personnel_honor;
                $totalOps += $rec->actual_operational_cost;
                $totalProfit += $rec->fixed_profit;
                if ($rec->profit_locked) {
                    $totalLabaTerkunci += $rec->fixed_profit;
                }
            @endphp
            <tr>
                <td>
                    <span class="badge badge-event-code">{{ $rec->event->event_code ?? '-' }}</span>
                </td>
                <td>
                    <div class="font-bold" style="color: #361f1a;">{{ str_replace('_', ' ', $rec->event->booking->event_type ?? '') }}</div>
                    <div style="font-size: 9px; color: #827471; margin-top: 2px;">Klien: {{ $rec->event->booking->client_name ?? '-' }}</div>
                </td>
                <td class="text-right font-bold">Rp {{ number_format($rec->total_revenue, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($rec->total_personnel_honor, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($rec->actual_operational_cost, 0, ',', '.') }}</td>
                <td class="text-right font-bold" style="color: #705d00;">Rp {{ number_format($rec->fixed_profit, 0, ',', '.') }}</td>
                <td class="text-center">
                    @php
                        $booking = $rec->event->booking ?? null;
                        $isLunas = $booking ? (!is_null($booking->full_paid_at) || $booking->status === 'paid_full') : false;
                    @endphp
                    @if($isLunas)
                        <span class="badge badge-success">Lunas</span>
                    @elseif($rec->profit_locked)
                        <span class="badge badge-info">Terkunci</span>
                    @else
                        <span class="badge badge-warning">Draft</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-container">
        <table class="summary-table">
            <tr>
                <td style="width: 25%">
                    <div class="card">
                        <div class="card-title">Total Pendapatan</div>
                        <div class="card-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        <div class="card-subtitle">Akumulasi Seluruh Nilai Kontrak</div>
                    </div>
                </td>
                <td style="width: 25%">
                    <div class="card">
                        <div class="card-title">Total Pengeluaran Ops</div>
                        <div class="card-value">Rp {{ number_format($totalOps, 0, ',', '.') }}</div>
                        <div class="card-subtitle">Honor Kru & Realisasi Lapangan</div>
                    </div>
                </td>
                <td style="width: 25%">
                    <div class="card">
                        <div class="card-title">Total Laba Tetap (All)</div>
                        <div class="card-value">Rp {{ number_format($totalProfit, 0, ',', '.') }}</div>
                        <div class="card-subtitle">Estimasi Total Laba Pimpinan</div>
                    </div>
                </td>
                <td style="width: 25%">
                    <div class="card card-primary" style="margin-right: 0;">
                        <div class="card-title">Laba Bersih Terkunci</div>
                        <div class="card-value">Rp {{ number_format($totalLabaTerkunci, 0, ',', '.') }}</div>
                        <div class="card-subtitle" style="color: rgba(255,255,255,0.6)">Berdasarkan Pembayaran Valid/DP</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
