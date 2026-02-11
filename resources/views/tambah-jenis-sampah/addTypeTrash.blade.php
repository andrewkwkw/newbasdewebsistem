@extends('admin.layouts.app')

@section('title', 'Posts')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Jenis Sampah</h1>
                <div class="section-header-button">
                    <a href="{{ route('create_jenis') }}" class="btn btn-primary">Tambah Jenis</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Jenis Sampah</div>
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.users.index') }}">Pengguna</a>
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Data Sampah Yang Tersedia</h4>
                            </div>
                            <div class="card-body">
                                <div class="clearfix mb-3"></div>

                                <div class="table-responsive">
                                    <table class="table-striped table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th class="text-center">Jenis Sampah Yang Tersedia</th>
                                                <th class="text-center">Action</th>
                                                <th class="text-center">Tambah Data Pengguna</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jenisSampah as $index => $sampah)
                                            <tr>
                                                <td class="align-middle text-center">{{ $index + 1 }}</td>
                                                <td class="align-middle text-center">{{ $sampah->nama_sampah }}</td>
                                                <td class="align-middle text-center">
                                                    <form action="{{ route('jenis_sampah.destroy', $sampah->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete('{{ $sampah->nama_sampah }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                    <a href="{{ route('jenis-sampah.edit', $sampah->id) }}" class="btn btn-primary btn-sm">Edit Sampah</a>
                                                    </td>
                                                <td class="align-middle text-center">
                                                    <a href="{{ route('viewcreate') }}" class="btn btn-primary btn-sm">Tambah Data</a>
                                                </td>
                                            </tr>
                                            
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    <nav>
                                        <ul class="pagination">
                                        </ul>
                                    </nav>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('js/page/features-data.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('js/page/features-data.js') }}"></script>
    <script>
        function confirmDelete(namaSampah) {
            return confirm(`Yakin ingin menghapus jenis sampah "${namaSampah}"? Data ini tidak bisa dikembalikan.`);
        }
    </script>
@endpush
