@extends('admin.layouts.app')

@section('title', 'Data Sampah Pengguna')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Sampah untuk Pengguna: {{ $user->username }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Data Sampah</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Semua Data Sampah</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-striped table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th class="text-center">Berat Sampah (Kg)</th>
                                                <th class="text-center">Nominal Per Gram</th>
                                                <th class="text-center">Nominal Total</th>
                                                <th class="text-center">Tanggal Input</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user->userData as $dataSampah)
                                                <tr>
                                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                                    <td class="align-middle text-center">{{ $dataSampah->jml_sampah_perkg }}</td>
                                                    <td class="align-middle text-center">{{ $dataSampah->nominal_pergram }}</td>
                                                    <td class="align-middle text-center">{{ $dataSampah->nominal }}</td>
                                                    <td class="align-middle text-center">{{ $dataSampah->created_at->format('d M Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    <!-- Jika ada pagination -->
                                    {{-- {{ $dataSampahs->links() }} --}}
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
