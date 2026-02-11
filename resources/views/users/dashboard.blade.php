@extends('users.layouts.app')

@section('title', 'Dashboard')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    
    <style>
        /* Animasi Berdenyut untuk Tombol Trigger */
        .btn-pulse {
            animation: pulse-animation 2s infinite;
        }
        @keyframes pulse-animation {
            0% { box-shadow: 0 0 0 0 rgba(103, 119, 239, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(103, 119, 239, 0); }
            100% { box-shadow: 0 0 0 0 rgba(103, 119, 239, 0); }
        }

        /* Styling tambahan untuk tabel */
        .badge-point {
            font-size: 0.9em;
            padding: 5px 10px;
            border-radius: 20px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            
            {{-- 1. HEADER HALAMAN --}}
            <div class="section-header">
                <h1>Dashboard {{ auth()->user()->fullname ?? auth()->user()->name }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Smart Trash</div>
                </div>
            </div>

            {{-- 2. HERO CARD: TRIGGER ALAT (IoT) --}}
            <div class="row justify-content-center mb-4">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card card-hero">
                        <div class="card-header text-center" style="background: linear-gradient(135deg, #6777ef, #95a0f4);">
                            <div class="card-icon mb-3">
                                <i class="fas fa-camera fa-2x text-white"></i>
                            </div>
                            <h4 class="text-white">Smart Trash Controller</h4>
                            <div class="card-description text-white">
                                Pastikan Anda berada di depan alat, lalu tekan tombol di bawah.
                            </div>
                        </div>
                        <div class="card-body p-4 text-center bg-white">
                            <div id="trigger-area">
                                <button onclick="triggerDevice()" id="btn-trigger" class="btn btn-primary btn-lg btn-round btn-pulse px-5 py-3 font-weight-bold">
                                    <i class="fas fa-power-off mr-2"></i> AKTIFKAN ALAT
                                </button>

                                <div id="loading-text" class="mt-3 text-primary font-weight-bold" style="display: none;">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Menghubungkan ke Alat...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. STATISTIK (HANYA POIN) --}}
            <div class="row justify-content-center">
                {{-- Kartu Poin Smart Trash --}}
                <div class="col-lg-6 col-md-8 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Poin Smart Trash Anda</h4>
                            </div>
                            <div class="card-body">
                                {{ number_format($points ?? 0, 0, ',', '.') }} Poin
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. TABEL RIWAYAT POIN (IoT LOGS) --}}
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-history mr-2 text-primary"></i> Riwayat Penukaran Botol (Smart Trash)</h4>
                            <div class="card-header-action">
                                <a href="#" class="btn btn-primary">Lihat Semua</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center" width="10%">#</th>
                                            <th width="30%">Waktu Transaksi</th>
                                            <th width="40%">Aktivitas</th>
                                            <th class="text-center" width="20%">Poin Masuk</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($trashLogs as $log)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="font-weight-bold">{{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d F Y, H:i') }} WIB</div>
                                                <div class="text-small text-muted">
                                                    {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="media">
                                                    <div class="media-body">
                                                        <div class="media-title">Setor Botol Plastik</div>
                                                        <span class="text-muted text-small">Sumber: Smart Trash Device #01</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-success badge-point">
                                                    <i class="fas fa-plus-circle mr-1"></i> {{ $log->points }} Poin
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <div class="empty-state">
                                                    <div class="empty-state-icon bg-light">
                                                        <i class="fas fa-box-open fa-2x text-muted"></i>
                                                    </div>
                                                    <h5 class="mt-3 text-muted">Belum ada riwayat transaksi.</h5>
                                                    <p class="text-small text-muted">Ayo mulai gunakan Smart Trash untuk mendapatkan poin!</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection

@push('scripts')
    {{-- JS Libraries --}}
    <script src="{{ asset('library/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    {{-- SweetAlert CDN --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    {{-- SCRIPT TRIGGER DEVICE --}}
    <script>
        function triggerDevice() {
            // 1. Ubah UI jadi Loading
            $('#btn-trigger').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sedang Mengirim...');
            $('#loading-text').fadeIn();

            // 2. Kirim Request ke API Laravel
            const apiUrl = "{{ url('/api/trigger-device') }}";

            fetch(apiUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    user_id: "{{ auth()->user()->id }}"
                })
            })
            .then(async response => {
                const isJson = response.headers.get('content-type')?.includes('application/json');
                const data = isJson ? await response.json() : null;

                if (!response.ok) {
                    const errorMsg = (data && data.message) || response.statusText;
                    throw new Error(errorMsg);
                }
                return data;
            })
            .then(data => {
                // === SUKSES ===
                if(data.status === 'success') {
                    swal({
                        title: "Berhasil Terhubung!",
                        text: "Kamera alat AKTIF! Silakan scan sampah botol Anda sekarang.",
                        icon: "success",
                        button: "Siap!",
                        timer: 5000 
                    });
                }
            })
            .catch(error => {
                // === GAGAL ===
                console.error('Error Trigger:', error);
                
                let pesanError = "Terjadi kesalahan koneksi.";
                if(error.message.includes('sedang digunakan')) {
                    pesanError = "Alat sedang digunakan orang lain. Harap antri sebentar.";
                }

                swal({
                    title: "Gagal Mengaktifkan",
                    text: pesanError, 
                    icon: "warning", 
                    button: "Coba Lagi",
                });
            })
            .finally(() => {
                // 3. Kembalikan UI seperti semula
                setTimeout(() => {
                    $('#btn-trigger').prop('disabled', false).html('<i class="fas fa-power-off mr-2"></i> AKTIFKAN ALAT');
                    $('#loading-text').hide();
                }, 1000);
            });
        }
    </script>
@endpush