@extends('auth.layouts.auth')

@section('title', 'Kebijakan Privasi')

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Kebijakan Privasi</h4>
        </div>
        <div class="card-body">
            <p><strong>1. Pengumpulan Data</strong></p>
            <p>Sistem Bank Sampah PT Resik Prima Teknolojia mengumpulkan data berupa Nama, Email, Nomor Telepon, dan Riwayat Transaksi Penyetoran Sampah.</p>
            
            <p class="mt-3"><strong>2. Penggunaan Data</strong></p>
            <p>Data yang dikumpulkan semata-mata digunakan untuk keperluan pencatatan saldo, verifikasi setoran botol, dan operasional internal pengurus Bank Sampah Desa.</p>
            
            <p class="mt-3"><strong>3. Keamanan Data</strong></p>
            <p>Kami berkomitmen untuk menjaga keamanan data warga. Data pribadi Anda tidak akan pernah dijual atau dibagikan kepada pihak ketiga untuk kepentingan komersial (seperti pinjaman online, telemarketing, dll).</p>
            
            <div class="mt-4 text-center">
                <button onclick="history.back()" class="btn btn-primary">Kembali</button>
            </div>
        </div>
    </div>
@endsection
