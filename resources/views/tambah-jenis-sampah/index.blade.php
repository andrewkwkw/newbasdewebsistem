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
                <h1>Jenis Sampah Besi</h1>
                <div class="section-header-button">
                    <a href="{{ route('sampah-besi.create') }}" class="btn btn-primary">Tambah Data</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Jenis Sampah</div>
                    <div class="breadcrumb-item">All Besi</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-0">
                            <div class="card-body">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="{{ route('sampah-besi.index') }}">All <span
                                                class="badge badge-white"></span></a>
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

                                <div class="float-left">
                                    <form method="GET" action="{{ route('sampah-besi.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari Berat Besi..."
                                                name="berat">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="clearfix mb-3"></div>

                                <div class="table-responsive">
                                    <table class="table-striped table">
                                        <tr>

                                            <th>No.</th>
                                            <th>Berat </th>
                                            <th>Nominal</th>
                                            <th>Aksi</th>
                                        </tr>
                                 
                                            <tr>
                                                <td>
                                                   
                                                </td>
                                                <td>
                                                </td>
                                                <td></td>
                                                <td>
                                                    <form action=""
                                                        method="POST">
                                                        <a type="button" class="btn btn-primary" href=""><i class="fa-regular fa-pen-to-square"></i></a>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                                    </form>
                                                </td>

                                            </tr>
                                    

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
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-data.js') }}"></script>
@endpush
