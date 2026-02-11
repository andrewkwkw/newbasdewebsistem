@extends('admin.layouts.app')

@section('title', 'Data Sampah')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Sampah Saat Ini</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Data Sampah</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-0">
                            <div class="card-body">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="{{ route('jenis_sampah') }}">Tambah Jenis Sampah +</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Semua Data</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th class="text-center">Username</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">No. Telp</th>
                                                <th class="text-center">Alamat</th>
                                                <th class="text-center">Info</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                                    <td class="align-middle text-center">{{ $user->username }}</td>
                                                    <td class="align-middle text-center">{{ $user->email }}</td>
                                                    <td class="align-middle text-center">{{ $user->no_telpon }}</td>
                                                    <td class="align-middle text-center">{{ $user->tempat }}</td>
                                                    <td class="align-middle text-center">
                                                        <a href="{{ route('users_show', $user->id) }}" class="btn btn-info mb-2 mt-2">Lihat Data</a>
                                                        <a href="{{ route('users_uangtambah', ['id' => $user->id]) }}" class="btn btn-info mb-2 mt-2">Uang Masuk</a>
                                                        <a href="{{ route('users_uangkeluar', ['id' => $user->id]) }}" class="btn btn-info mb-2 mt-2">Uang Keluar</a>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <a href="{{ route('users_tambah',['id' => $user->id]) }}" class="btn btn-warning mb-2 mt-2">Tambah Keuangan</a>
                                                        <a href="{{ route('users_tarik') }}" class="btn btn-warning mb-2 mt-2">Tarik Keuangan</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- <div class="float-right">
                                    <nav>
                                        {{ $user->links() }} <!-- Jika menggunakan paginasi -->
                                    </nav>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-data.js') }}"></script>
@endpush