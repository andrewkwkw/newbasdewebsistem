@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1>Dashboard</h1>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- SALDO ADMIN --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="text-muted mb-2">Saldo Admin Saat Ini</h5>

                        <h3 class="mt-2 text-success fw-bold">
                            Rp {{ number_format((float)str_replace('.', '', $adminSaldo), 0, ',', '.') }}
                        </h3>

                        <button class="btn btn-warning btn-sm mt-3" 
                                data-toggle="modal" 
                                data-target="#updateSaldoModal">
                            <i class="fas fa-edit"></i> Update Saldo
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM TAMBAH SALDO --}}
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Tambah Saldo Admin</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.tambah-saldo') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label>Jumlah Saldo</label>
                                <input 
                                    type="text"
                                    name="jumlah"
                                    id="jumlah"
                                    class="form-control"
                                    placeholder="Contoh: 2.000.000"
                                    required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Tambah Saldo
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL USER --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Daftar Pengguna & Rincian Keuangan</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Lengkap</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>No. Telp</th>
                                        <th>Alamat</th>
                                        <th>Total Masuk</th>
                                        <th>Saldo User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->fullname }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->no_telpon }}</td>
                                            <td>{{ $user->tempat }}</td>
                                            <td>Rp {{ number_format($user->total_masuk, 0, ',', '.') }}</td>
                                            <td>
                                                <strong class="{{ $user->saldo >= 0 ? 'text-success' : 'text-danger' }}">
                                                    Rp {{ number_format($user->saldo, 0, ',', '.') }}
                                                </strong>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-muted py-4">Belum ada data pengguna.</td>
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

{{-- =====================
     MODAL UPDATE SALDO
===================== --}}
<div class="modal fade" id="updateSaldoModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header bg-warning">
        <h5 class="modal-title">Update Saldo Admin</h5>
        <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
        </button>
      </div>

      <form action="{{ route('admin.update-saldo', auth()->id()) }}" method="POST">
        @csrf

        <div class="modal-body">
            <label>Saldo Baru</label>
            <input 
                type="text"
                name="saldo_baru"
                id="saldo_baru"
                class="form-control"
                value="{{ $adminSaldo }}"
                required>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">
              <i class="fas fa-save"></i> Simpan
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

@endsection

{{-- =====================
     SCRIPT SECTION
===================== --}}
@section('scripts')
<script>
    // Format angka otomatis saat mengetik (mengubah 2000000 → 2.000.000)
    function formatRupiah(inputId) {
        document.getElementById(inputId).addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, "");
            this.value = new Intl.NumberFormat('id-ID').format(value);
        });
    }

    formatRupiah('jumlah');
    formatRupiah('saldo_baru');
</script>
@endsection
