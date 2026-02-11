@extends('admin.layouts.app')
@section('title', 'Edit Jenis Sampah')

@push('style')
    {{-- (CSS Anda tetap sama) --}}
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('jenis_sampah.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Edit Jenis Sampah</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('jenis_sampah.index') }}">Jenis Sampah</a></div>
                    <div class="breadcrumb-item active">Edit</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Form Edit Jenis Sampah</h4>
                            </div>
                            <div class="card-body">
                                {{-- Pastikan action route mengarah ke method update yang benar --}}
                                <form action="{{ route('jenis_sampah.update', $jenisSampah->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    {{-- Input Nama Sampah --}}
                                    <div class="form-group">
                                        <label for="nama_sampah">Nama Sampah</label>
                                        <input type="text" name="nama_sampah" class="form-control @error('nama_sampah') is-invalid @enderror" value="{{ old('nama_sampah', $jenisSampah->nama_sampah) }}" required>
                                        @error('nama_sampah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- =============================================== --}}
                                    {{--         KOLOM BARU DITAMBAHKAN DI SINI        --}}
                                    {{-- =============================================== --}}
                                    <div class="form-group">
                                        <label for="harga_per_kg">Harga per Kilogram (Kg)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Rp</div>
                                            </div>
                                            <input type="number" name="harga_per_kg" class="form-control @error('harga_per_kg') is-invalid @enderror" value="{{ old('harga_per_kg', $jenisSampah->harga_per_kg) }}" required>
                                        </div>
                                        @error('harga_per_kg')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">Perbarui</button>
                                    <a href="{{ route('jenis_sampah.index') }}" class="btn btn-secondary">Batal</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    {{-- (Script Anda tetap sama) --}}
@endpush