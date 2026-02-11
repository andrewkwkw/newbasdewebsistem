@extends('admin.layouts.app')

@section('title', 'Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Dashboard</h1>
                <form class="form-inline" action="{{ route('admin-search') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="query" placeholder="Search..." aria-label="Search" aria-describedby="button-search">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" id="button-search">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row text-center justify-content-center">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="far fa-solid fa-arrow-down"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Uang Masuk</h4>
                            </div>
                            <div class="card-body">
                                Rp. {{ $totalMasuk }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Saldo</h4>
                            </div>
                            <div class="card-body">
                                Rp. {{ $totalSaldo }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-solid fa-arrow-up"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Uang Keluar</h4>
                            </div>
                            <div class="card-body">
                                Rp. {{ $totalKeluar }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pengguna</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-striped table">
                                    <tr>
                                        <th>No.</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>No. Telp</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->no_telpon }}</td>
                                            <td>{{ $user->tempat }}</td>
                                            <td id="status-{{ $user->id }}">
                                                @if($user->approve)
                                                    Disetujui
                                                @else
                                                    Belum Disetujui
                                                @endif
                                            </td>
                                            <td>
                                                <input type="checkbox" class="toggle-approve" data-id="{{ $user->id }}" {{ $user->approve ? 'checked' : '' }}>                                              
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                            <div class="float-right">
                                {{-- {{ $users->links() }} --}}
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
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>

    <!-- Page Specific JS File -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggles = document.querySelectorAll('.toggle-approve');

            toggles.forEach(toggle => {
                toggle.addEventListener('change', function () {
                    const userId = this.getAttribute('data-id');
                    const isChecked = this.checked;

                    fetch(`/users/${userId}/toggle-approve`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const statusCell = document.getElementById(`status-${userId}`);
                            if (data.approve) {
                                statusCell.innerText = 'Disetujui';
                            } else {
                                statusCell.innerText = 'Belum Disetujui';
                            }
                        } else {
                            // Revert the toggle switch if there was an error
                            toggle.checked = !isChecked;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert the toggle switch if there was an error
                        toggle.checked = !isChecked;
                    });
                });
            });
        });
    </script>
@endpush
