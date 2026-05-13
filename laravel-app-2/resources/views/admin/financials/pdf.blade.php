<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan ART-HUB</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        h2 { text-align: center; color: #4E342E; }
        .summary { margin-top: 30px; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Laporan Keuangan Sanggar Cahaya Gumilang</h2>
    <p>Tanggal Cetak: {{ now()->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Kode Booking</th>
                <th>Nama Event</th>
                <th>Pemasukan (Total Harga)</th>
                <th>Biaya Operasional (Realisasi)</th>
                <th>Laba Tetap (Fixed Profit)</th>
                <th>Status Profit</th>
            </tr>
        </thead>
        <tbody>
            @php $totalLaba = 0; @endphp
            @foreach($records as $rec)
            <tr>
                <td>{{ $rec->event->booking->booking_code }}</td>
                <td>{{ $rec->event->name }}</td>
                <td>Rp {{ number_format($rec->total_income, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($rec->realized_cost, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($rec->fixed_profit, 0, ',', '.') }}</td>
                <td>{{ $rec->profit_locked ? 'Terkunci' : 'Belum' }}</td>
            </tr>
            @if($rec->profit_locked)
                @php $totalLaba += $rec->fixed_profit; @endphp
            @endif
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p>Total Laba Bersih (Terkunci): Rp {{ number_format($totalLaba, 0, ',', '.') }}</p>
    </div>
</body>
</html>
