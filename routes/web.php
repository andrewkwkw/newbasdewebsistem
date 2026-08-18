<?php
use App\Http\Controllers\Auth\LoginController as Login;
use App\Http\Controllers\Auth\RegistrasiController;
use App\Http\Controllers\DataSampahController;
use App\Http\Controllers\JenisSampahController;
use App\Http\Controllers\Admin\SetorSaldoController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/test-koneksi-vps', function () {
    try {
        $response = Http::withToken(config('services.smart_trash.token'))
                        ->post(config('services.smart_trash.url'), [
                            'pesan' => 'Halo, ini tes koneksi dari lokal!'
                        ]);

        if ($response->successful()) {
            return 'Berhasil terhubung ke VPS! Respons: ' . $response->body();
        } else {
            return 'Gagal terhubung. Error: ' . $response->status() . ' - ' . $response->body();
        }
    } catch (\Exception $e) {
        return 'Gagal terhubung (Tidak ada respon dari server). Error: ' . $e->getMessage();
    }
});

Route::get('/', function () {
    return redirect('login');
});

Route::get('/privacy-policy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms-of-service', function () {
    return view('terms');
})->name('terms');

// Route::get('/', function () {
//     // Arahkan ke file view 'splash.blade.php' saat aplikasi pertama kali dibuka
//     return view('splash');
// });

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [Login::class, 'showLoginForm'])->name('login');
    Route::post('/login', [Login::class, 'login'])->name('login.attempt');
    Route::get('/register', [RegistrasiController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegistrasiController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    Route::middleware('is.role:1')->group(function () {
        Route::get('admin/dashboard', [MasterController::class, 'index'])->name('admin.dashboard');
        Route::post('/users/{id}/toggle-approve', [MasterController::class, 'toggleApprove'])->name('users.toggleApprove');
        Route::get('/masuk', [MasterController::class, 'indexmasuk'])->name('masuk');
        Route::post('/users', [MasterController::class, 'createmasuk'])->name('cmasuk');
        Route::get('/keluar', [MasterController::class, 'indexkeluar'])->name('keluar');
        Route::post('/ckeluar', [MasterController::class, 'createkeluar'])->name('ckeluar');
    });

    Route::middleware('is.role:1')->group(function () {
        Route::get('/tambahjenissampah', [JenisSampahController::class, 'index'])->name('jenis_sampah');
        Route::get('/createjenis', [JenisSampahController::class, 'CreateJenis'])->name('create_jenis');
        Route::post('/store_jenis', [JenisSampahController::class, 'store'])->name('jenis-sampah');
        Route::resource('jenis_sampah', JenisSampahController::class);
        Route::get('/admin/search', [MasterController::class, 'search'])->name('admin-search');
        Route::put('/jenis-sampahby/{id}', [JenisSampahController::class, 'updates'])->name('jenis-sampahby.update');
        Route::post('jenis-sampahku/{id}/editsampah', [JenisSampahController::class, 'edit'])->name('jenis-sampahku.edit');
        Route::resource('jenis-sampah', JenisSampahController::class);
        Route::resource('/admin/jenis-sampah', JenisSampahController::class)->names('jenis_sampah');
        Route::get('/createdata', [JenisSampahController::class, 'indexDataSampah'])->name('viewcreate');
        Route::get('/test-user', [JenisSampahController::class, 'indexDataSampah']);
        Route::put('/transaksi/update/{id}', [JenisSampahController::class, 'updates'])->name('transaksi.update');
        // Route::post('/createdatasampah', [DataSampahController::class, 'store'])->name('makedata');
        Route::get('/createdatasampahdata', [DataSampahController::class, 'index1'])->name('makedata1');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/userstambah-keuangan/{id}', [DataSampahController::class, 'tambahKeuangan'])->name('users_tambah');
        Route::get('/userstarik', [DataSampahController::class, 'tarikKeuangan'])->name('users_tarik');
        Route::get('/users/{id}/tambah-keuanganku', [UserController::class, 'lihatKeuangan'])->name('users_uangtambah');
        Route::get('/users/{id}/keluar-keuanganku', [UserController::class, 'lihatKeuanganKeluar'])->name('users_uangkeluar');
        Route::resource('/admin/jenis-sampah', JenisSampahController::class)->names('jenis_sampah');
        Route::get('/admin/setor-saldo/create', [SetorSaldoController::class, 'create'])->name('setor_saldo.create');
        Route::post('/admin/setor-saldo', [SetorSaldoController::class, 'store'])->name('makedata');
        Route::get('/admin/users', [JenisSampahController::class, 'AdminUsers'])->name('admin.users.index');
        Route::delete('/admin/users/{id}/sampah', [JenisSampahController::class, 'destroySampah'])->name('admin.users.sampah.destroy');
        Route::get('/profile/{id}/edit', [UserController::class, 'editProfile'])->name('users.profile.edit');
        Route::put('/users/profile/{id}', [UserController::class, 'updateProfile'])->name('users.profile.update');
        Route::post('/admin/tambah-saldo', [MasterController::class, 'tambahSaldo'])->name('admin.tambah-saldo');
        Route::get('/admin/laporan', [SetorSaldoController::class, 'index'])->name('admin.laporan');
        Route::post('/admin/update-saldo/{id}', [MasterController::class, 'updateSaldo'])->name('admin.update-saldo');
        Route::post('/admin/update-saldo-user/{id}', [MasterController::class, 'updateSaldoUser'])->name('admin.update-saldo-user');
        Route::post('/admin/update-setoran/{id}', [SetorSaldoController::class, 'update'])->name('admin.update-setoran');
        Route::get('/admin/laporan/pdf', [SetorSaldoController::class, 'cetakPdf'])->name('admin.laporan.pdf');
    });

    Route::middleware('is.role:2')->group(function () {
        Route::get('user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
        Route::get('user/history', [UserController::class, 'history'])->name('user.history');
        Route::get('/profile/{id}/edit', [UserController::class, 'editProfile'])->name('users.profile.edit');
        Route::put('/users/profile/{id}', [UserController::class, 'updateProfile'])->name('users.profile.update');
        Route::get('/kartu-saya', [UserController::class, 'kartuSaya'])->name('kartu.saya');
    });
    Route::post('/logout', [Login::class, 'logout'])->name('logout');
});