@extends('admin.layouts.app')

@section('title', 'Laporan Poin Bulanan')

@section('main')
<div class="main-content">
    <section class="section">

        <div class="section-header">
            <h1>Laporan Bulanan Poin Warga</h1>
        </div>
        
        {{-- FILTER BULAN & EXPORT --}}
        <form class="mb-4" method="GET" action="">
            <div class="form-row align-items-end">
                <div class="form-group col-md-3 mb-0">
                    <label>Pilih Bulan</label>
                    <input type="month" name="bulan" class="form-control" value="{{ $bulan }}">
                </div>
                <div class="form-group col-md-4 mb-0">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Tampilkan
                    </button>
                    
                    {{-- TOMBOL EXPORT PDF --}}
                    <a href="" class="btn btn-danger ml-2" target="_blank">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                </div>
            </div>
        </form>

        {{-- RINGKASAN --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-6 col-md-8">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5>Total Poin Bulan Ini</h5>
                        <h3 class="text-warning display-4 font-weight-bold">
                            {{ number_format($totalPoin ?? 0, 0, ',', '.') }} 
                            <span style="font-size: 0.5em">Poin</span>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- PER JENIS --}}
        <h2 class="section-title">Laporan Poin Per Jenis Sampah</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>Jenis Sampah</th>
                        <th class="text-right">Total Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($perJenis as $item)
                    <tr>
                        <td>{{ $item['nama_sampah'] }}</td>
                        <td class="text-right text-warning font-weight-bold">
                            {{ number_format($item['total_poin'], 0, ',', '.') }} Poin
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted">Tidak ada data bulan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PER USER --}}
        <h2 class="section-title mt-5">Laporan Poin Per User</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-warning">
                    <tr>
                        <th>User</th>
                        <th class="text-right">Total Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($perUser as $item)
                    <tr>
                        <td>{{ $item['user'] }}</td>
                        <td class="text-right text-warning font-weight-bold">
                            {{ number_format($item['total_poin'], 0, ',', '.') }} Poin
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted">Tidak ada data bulan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- DETAIL TRANSAKSI HARIAN (REKAP PER HARI) --}}
        <h2 class="section-title mt-5">Rekap Transaksi Harian</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-secondary">
                    <tr>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Jenis Sampah</th>
                        <th class="text-right">Total Poin Harian</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- LOGIKA GROUPING: Gabungkan data berdasarkan Tanggal & User --}}
                    @php
                        $groupedTransactions = $transaksi->groupBy(function($item) {
                            return $item->created_at->format('Y-m-d') . '-' . $item->user_id;
                        });
                    @endphp

                    @forelse ($groupedTransactions as $group)
                        @php
                            // Ambil data pertama dalam grup untuk Nama & Tanggal
                            $firstItem = $group->first();
                            
                            // Jumlahkan Poin dalam grup tersebut
                            // Kita ambil dari 'points' (TrashLog) atau hitung manual
                            $dailyPoints = $group->sum('points');
                            
                            // (Opsional) Jumlah botol
                            $dailyAmount = $group->sum('amount');
                        @endphp
                        <tr>
                            <td>{{ $firstItem->created_at->format('d-m-Y') }}</td>
                            <td>{{ $firstItem->user->fullname ?? $firstItem->user->name }}</td>
                            <td>
                                Botol Plastik 
                                <span class="text-muted text-small">({{ $dailyAmount }} Pcs)</span>
                            </td>
                            <td class="text-right text-warning font-weight-bold">
                                +{{ number_format($dailyPoints, 0, ',', '.') }} Poin
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada transaksi bulan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </section>
</div>
@endsection