@extends('admin.layouts.app')
@section('title', 'Tambah Jenis Sampah')

@push('style')
    {{-- (CSS tambahan jika ada) --}}
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    {{-- Tombol ini mengarahkan ke halaman daftar jenis sampah --}}
                    <a href="{{ route('jenis_sampah.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Tambah Jenis Sampah</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('jenis_sampah.index') }}">Jenis Sampah</a></div>
                    <div class="breadcrumb-item">Tambah Jenis</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Form Tambah Jenis Sampah Baru</h4>
                            </div>
                            <div class="card-body">
                                {{-- Form ini akan mengirim data ke method 'store' di JenisSampahController --}}
                                <form method="POST" action="{{ route('jenis_sampah.store') }}">
                                    @csrf

                                    {{-- Input untuk Nama Jenis Sampah --}}
                                    <div class="form-group">
                                        <label>Nama Jenis Sampah</label>
                                        <input type="text" name="nama_sampah" 
                                               class="form-control @error('nama_sampah') is-invalid @enderror" 
                                               placeholder="Contoh: Plastik Botol" 
                                               value="{{ old('nama_sampah') }}" required>
                                        @error('nama_sampah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Input untuk Harga per Kilogram --}}
                                    <div class="form-group">
                                        <label>Harga per Kilogram (Kg)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Rp</div>
                                            </div>
                                            <input type="number" name="harga_per_kg" 
                                                   class="form-control @error('harga_per_kg') is-invalid @enderror" 
                                                   placeholder="Contoh: 6000" 
                                                   value="{{ old('harga_per_kg') }}" required>
                                        </div>
                                        @error('harga_per_kg')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Input untuk memilih Admin --}}
                                    <div class="form-group">
                                        <label>Pilih Admin</label>
                                        <select name="admin_id" class="form-control @error('admin_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Admin --</option>
                                            @foreach ($admins as $admin)
                                                <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                                    {{ $admin->fullname ?? $admin->username }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('admin_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <a href="{{ route('jenis_sampah.index') }}" class="btn btn-secondary">Batal</a>
                                    </div>
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
    {{-- (Script tambahan jika ada) --}}
@endpush
