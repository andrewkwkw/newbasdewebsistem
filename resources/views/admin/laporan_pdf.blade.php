<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bulanan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 2px; }
        
        .summary-box { border: 1px solid #000; padding: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bg-grey { background-color: #f9f9f9; }
        
        .section-title { font-size: 14px; font-weight: bold; margin-top: 15px; margin-bottom: 5px; text-transform: uppercase; }
        
        /* Utility untuk warna uang */
        .total-row { font-weight: bold; background-color: #e8e8e8; }
    </style>
</head>
<body>

    <div class="header">
        <h1>PT RESIK PRIMA TEKNOLOJIA</h1>
        <p>Laporan Keuangan & Setoran Sampah</p>
        <p>Periode: {{ \Carbon\Carbon::parse($bulan)->isoFormat('MMMM Y') }}</p>
    </div>

    {{-- RINGKASAN ATAS --}}
    <table style="border: none;">
        <tr style="border: none;">
            <td style="border: 1px solid #000; width: 33%; text-align: center; padding: 15px;">
                <strong>Total Sampah</strong><br>
                <span style="font-size: 16px;">{{ $totalKg }} Kg</span>
            </td>
            <td style="border: 1px solid #000; width: 33%; text-align: center; padding: 15px;">
                <strong>Total Uang (Kotor)</strong><br>
                <span style="font-size: 16px;">Rp {{ number_format($totalUangFull, 0, ',', '.') }}</span>
            </td>
            <td style="border: 1px solid #000; width: 33%; text-align: center; padding: 15px; background-color: #dff0d8;">
                <strong>Total Bersih (96%)</strong><br>
                <span style="font-size: 16px; color: green;">Rp {{ number_format($totalUangBersih, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    {{-- 1. TABEL PER JENIS --}}
    <div class="section-title">1. Ringkasan Per Jenis Sampah</div>
    <table>
        <thead>
            <tr>
                <th>Jenis Sampah</th>
                <th>Berat (Kg)</th>
                <th>Nominal Kotor</th>
                <th>Nominal Bersih (96%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perJenis as $item)
            <tr>
                <td>{{ $item['nama_sampah'] }}</td>
                <td class="text-center">{{ $item['total_kg'] }}</td>
                <td class="text-right">Rp {{ number_format($item['total_uang_full'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item['total_uang_bersih'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- 2. TABEL PER USER --}}
    <div class="section-title">2. Ringkasan Per Nasabah</div>
    <table>
        <thead>
            <tr>
                <th>Nama Nasabah</th>
                <th>Berat (Kg)</th>
                <th>Nominal Kotor</th>
                <th>Nominal Bersih (96%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perUser as $item)
            <tr>
                <td>{{ $item['user'] }}</td>
                <td class="text-center">{{ $item['total_kg'] }}</td>
                <td class="text-right">Rp {{ number_format($item['total_uang_full'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item['total_uang_bersih'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- 3. DETAIL TRANSAKSI --}}
    <div class="section-title">3. Rincian Transaksi Harian</div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nasabah</th>
                <th>Jenis</th>
                <th>Berat</th>
                <th>Bersih (96%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $t)
            @php
                $full = $t->berat * $t->jenisSampah->harga_per_kg;
                $bersih = $full * 0.96;
            @endphp
            <tr>
                <td>{{ $t->created_at->format('d/m/Y') }}</td>
                <td>{{ $t->user->fullname }}</td>
                <td>{{ $t->jenisSampah->nama_sampah }}</td>
                <td class="text-center">{{ $t->berat }}</td>
                <td class="text-right">Rp {{ number_format($bersih, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>
        <p>Oleh Admin</p>
    </div>

</body>
</html>