@extends('admin.layouts.app')
@section('title', 'Input Data Sampah')

@push('style')
    {{-- CSS Library --}}
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
<div class="main-content">
    <section class="section">

        {{-- HEADERS --}}
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('jenis_sampah.index') }}" class="btn btn-icon">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Halaman Input Data Sampah</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('jenis_sampah.index') }}">Jenis Sampah</a></div>
                <div class="breadcrumb-item">Input Data</div>
            </div>
        </div>

        <div class="section-body">
            {{-- ALERT SUCCESS/ERROR (Opsional, jika ada session) --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        
                        {{-- =============================
                             FORM INPUT DATA
                        ============================== --}}
                        <div class="card-header">
                            <h4>Form Setor Sampah</h4>
                        </div>
                        
                        <div class="card-body">
                            {{-- FIX 1: Tambahkan ID pada form --}}
                            <form method="POST" action="{{ route('makedata') }}" id="formTransaksi">
                                @csrf

                                {{-- 1. PILIH USER --}}
                                <div class="form-group">
                                    <label>Pilih User <span class="text-danger">*</span></label>
                                    {{-- FIX 2: Hapus 'multiple', tapi biarkan name array user_ids[] agar controller tetap valid --}}
                                    <select name="user_ids[]" class="form-control select2" required>
                                        <option value="" disabled selected>-- Cari User Penerima --</option>
                                        @foreach($user as $u)
                                            <option value="{{ $u->id }}">{{ $u->username }} - {{ $u->fullname }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Pilih satu user yang menyetor sampah.</small>
                                </div>

                                {{-- 2. PILIH JENIS SAMPAH --}}
                                <div class="form-group">
                                    <label for="jenis_sampah_id">Pilih Jenis Sampah <span class="text-danger">*</span></label>
                                    <select name="jenis_sampah_id" id="jenis_sampah_id" class="form-control selectric" required>
                                        <option value="" disabled selected>-- Pilih Sampah --</option>
                                        @foreach ($jenisSampah as $sampah)
                                            <option value="{{ $sampah->id }}" data-harga="{{ $sampah->harga_per_kg }}">
                                                {{ $sampah->nama_sampah }} (Rp {{ number_format($sampah->harga_per_kg, 0, ',', '.') }}/Kg)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- 3. INPUT BERAT --}}
                                <div class="form-group">
                                    <label for="jml_sampah_perkg">Berat Sampah (Kg) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="jml_sampah_perkg" id="jml_sampah_perkg" class="form-control" placeholder="0" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text">Kg</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 4. HASIL NOMINAL (READONLY) --}}
                                <div class="form-group">
                                    <label>Total Nominal (User Terima 96%)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Rp</div>
                                        </div>
                                        <input type="text" id="nominal" class="form-control font-weight-bold text-success" readonly placeholder="0">
                                    </div>
                                    <small class="form-text text-muted">Nominal otomatis muncul saat jenis sampah dan berat diisi.</small>
                                </div>

                                {{-- TOMBOL SUBMIT --}}
                                <div class="form-group text-right">
                                    {{-- FIX 3: Tambahkan ID btnSubmit --}}
                                    <button type="submit" id="btnSubmit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Simpan Transaksi
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- =============================
                 CARD TRANSAKSI TERAKHIR
            ============================== --}}
            @if(isset($latestTransaction))
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h4>Transaksi Terakhir User Ini</h4>
                            <div class="card-header-action">
                                <button data-toggle="modal" data-target="#editTransaksiModal" class="btn btn-warning btn-icon icon-left">
                                    <i class="fas fa-edit"></i> Edit Transaksi Ini
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Jenis Sampah:</strong><br> 
                                    {{ $latestTransaction->jenisSampah->nama_sampah }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Berat:</strong><br> 
                                    {{ $latestTransaction->berat }} Kg
                                </div>
                                <div class="col-md-4">
                                    <strong>Total Uang Diterima:</strong><br> 
                                    <span class="text-success font-weight-bold">
                                        Rp {{ number_format($latestTransaction->amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>
</div>

{{-- =============================
     MODAL EDIT TRANSAKSI
============================= --}}
@if(isset($latestTransaction))
<div class="modal fade" id="editTransaksiModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark">Edit Transaksi Terakhir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('transaksi.update', $latestTransaction->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <div class="form-group">
                        <label>Jenis Sampah</label>
                        <select name="jenis_sampah_id" class="form-control selectric">
                            @foreach ($jenisSampah as $js)
                                <option value="{{ $js->id }}" {{ $latestTransaction->jenis_sampah_id == $js->id ? 'selected' : '' }}>
                                    {{ $js->nama_sampah }} (Rp {{ number_format($js->harga_per_kg, 0, ',', '.') }}/Kg)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Berat Sampah (Kg)</label>
                        <input type="number" step="0.01" name="berat" class="form-control" value="{{ $latestTransaction->berat }}" required>
                    </div>
                </div>

                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

{{-- =============================
     JAVASCRIPT SECTION (FIXED)
============================= --}}
@section('scripts')
    {{-- Load Library --}}
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            console.log("Script Loaded. Protection Active.");

            // 1. FUNGSI HITUNG TOTAL
            function hitungTotal() {
                // Ambil harga dari data-attribute
                let selectedOption = $('#jenis_sampah_id option:selected');
                let rawHarga = selectedOption.data('harga'); 
                
                // Ambil Berat
                let rawBerat = $('#jml_sampah_perkg').val();

                // Parsing ke angka float
                let cleanHarga = String(rawHarga).replace(/[^0-9.]/g, ''); 
                let hargaPerKg = parseFloat(cleanHarga);
                let berat = parseFloat(rawBerat);

                // Hitung jika valid
                if (!isNaN(hargaPerKg) && !isNaN(berat) && berat > 0) {
                    // Rumus: (Harga x Berat) x 96%
                    let total = (hargaPerKg * berat) * 0.96;

                    // Format Rupiah
                    let hasilFormat = new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(total);

                    // Set value
                    $('#nominal').val(hasilFormat);
                } else {
                    $('#nominal').val('');
                }
            }

            // Trigger Hitung
            $('#jenis_sampah_id').on('change selectric:change', hitungTotal);
            $('#jml_sampah_perkg').on('input keyup change', hitungTotal);
            
            // Jalankan sekali saat load
            hitungTotal();

            // ==========================================
            // FIX PENTING: MENCEGAH DOUBLE SUBMIT
            // ==========================================
            $('#formTransaksi').on('submit', function(e) {
                // Cek validitas form HTML5 (required, type number, dll)
                if (this.checkValidity()) {
                    let btn = $('#btnSubmit');
                    
                    // 1. Ubah teks tombol jadi Loading
                    btn.html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
                    
                    // 2. Disable tombol supaya tidak bisa diklik lagi
                    btn.prop('disabled', true);
                    
                    // 3. Form lanjut dikirim ke server...
                    return true;
                } else {
                    // Jika tidak valid, biarkan browser menampilkan pesan error
                    // Jangan disable tombol
                }
            });
        });
    </script>
@endsection