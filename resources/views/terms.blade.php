@extends('auth.layouts.auth')

@section('title', 'Syarat & Ketentuan')

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Syarat & Ketentuan</h4>
        </div>
        <div class="card-body">
            <p><strong>1. Kriteria Setoran Sampah</strong></p>
            <p>Sistem Bank Sampah PT Resik Prima Teknolojia menerima berbagai jenis sampah yang telah ditetapkan oleh Pengurus (seperti Plastik, Besi, Kaca, Kertas, dll). Khusus untuk penyetoran melalui mesin Smart Dropbox IoT, sistem hanya menerima botol plastik sesuai kriteria pembacaan kamera AI. Segala bentuk manipulasi timbangan dilarang keras.</p>
            
            <p class="mt-3"><strong>2. Nilai Saldo & Transaksi</strong></p>
            <p>Nilai tukar/poin untuk setiap sampah botol ditentukan oleh Pengurus Bank Sampah Desa. Harga dapat berubah sewaktu-waktu menyesuaikan harga pasar dari pengepul tanpa pemberitahuan sebelumnya.</p>
            
            <p class="mt-3"><strong>3. Sanksi Penyalahgunaan</strong></p>
            <p>Pengguna yang terbukti dengan sengaja merusak mesin Smart Dropbox, mengelabui sensor AI, atau melakukan kecurangan lainnya akan dikenakan sanksi berupa pembekuan akun dan penarikan saldo sepihak.</p>
            
            <div class="mt-4 text-center">
                <button onclick="history.back()" class="btn btn-primary">Kembali</button>
            </div>
        </div>
    </div>
@endsection
