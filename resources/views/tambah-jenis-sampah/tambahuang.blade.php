@extends('admin.layouts.app')

@section('title', 'Lihat Keuangan')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Keuangan Pengguna</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('users_show', $user->id) }}">Lihat Data</a></div>
                    <div class="breadcrumb-item">Keuangan</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Data Keuangan untuk {{ $user->username }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th class="text-center">Tanggal</th>
                                                <th class="text-center">Jumlah Uang</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($keuangan as $item)
                                                <tr>
                                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                                    <td class="align-middle text-center">{{ $item->created_at->format('d-m-Y') }}</td>
                                                    <td class="align-middle text-center">{{ number_format($item->uang) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">Tidak ada data keuangan.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $keuangan->links() }}
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
    <!-- JS Libraries -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-data.js') }}"></script>
@endpush
