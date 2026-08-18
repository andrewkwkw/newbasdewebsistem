<?php

namespace App\Http\Controllers; // Sesuaikan dengan namespace Anda

use App\Models\JenisSampah;
use App\Models\DataSampah;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JenisSampahController extends Controller
{
    // ===================================================================
    // BAGIAN UNTUK MENGELOLA JENIS SAMPAH (CRUD)
    // ===================================================================


    public function AdminUsers()
    {
        // 1. Dapatkan ID admin yang sedang login
        $adminId = Auth::id();

        // 2. Ambil HANYA pengguna yang memiliki admin_id yang sama
        //    dan role_id bukan 1 (bukan admin lain)
        $users = User::where('admin_id', $adminId)
                     ->where('role', '!=', 1) 
                     ->orderBy('username', 'asc')
                     ->paginate(10); // Gunakan paginate untuk data yang banyak

        // 3. Kirim data yang sudah terfilter ke view
        return view('tambah-jenis-sampah.AdminUsers', compact('users'));
    }
    
    public function destroySampah($id)
    {
        DataSampah::where('user_id', $id)->delete();

        return redirect()->back()->with('success', 'Semua transaksi sampah milik user ini berhasil dihapus.');
    }

    /**
     * Menampilkan daftar jenis sampah yang dibuat oleh admin yang login.
     */
    public function index()
    {
        // Ambil semua jenis sampah dari database
        // Jika Anda menggunakan sistem multi-admin, filter berdasarkan admin_id
        $jenisSampah = JenisSampah::where('admin_id', Auth::id())
                                  ->orderBy('nama_sampah', 'asc')
                                  ->get();
        
        // Mengarahkan ke view daftar sampah dan mengirimkan datanya
        return view('tambah-jenis-sampah.addTypeTrash', compact('jenisSampah'));
    }

    /**
     * Menampilkan form untuk membuat jenis sampah baru.
     */
    public function CreateJenis(){
        $admins = User::where('id', Auth::id())->get();
        return view('tambah-jenis-sampah.createjenis', compact('admins'));
    }

    /**
     * Menyimpan jenis sampah baru dan menautkannya ke admin yang login.
     */
    public function store(Request $request)
{
    $request->validate([
        'nama_sampah'    => 'required|string|max:255|unique:jenis_sampah,nama_sampah',
        'harga_per_kg'   => 'required|numeric|min:0',
        'harga_per_poin' => 'required|numeric|min:0',
    ]);

    JenisSampah::create([
        'nama_sampah'    => $request->nama_sampah,
        'harga_per_kg'   => $request->harga_per_kg,
        'harga_per_poin' => $request->harga_per_poin,
        'admin_id'       => Auth::id(),
    ]);

    return redirect()->route('jenis_sampah.index')
        ->with('success', 'Jenis sampah baru berhasil ditambahkan!');
}

    /**
     * Menampilkan form untuk mengedit jenis sampah.
     */
    public function edit(JenisSampah $jenisSampah)
    {
        // Tambahkan pengecekan kepemilikan untuk keamanan
        if ($jenisSampah->admin_id != Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data ini.');
        }
        return view('tambah-jenis-sampah.editsampah', compact('jenisSampah'));
    }

    /**
     * Memperbarui data jenis sampah di database.
     */
    public function update(Request $request, JenisSampah $jenisSampah)
    {
        // CEK PEMILIK DATA
        if ($jenisSampah->admin_id != Auth::id()) {
            abort(403);
        }

        // VALIDASI
        $request->validate([
            'nama_sampah'    => 'required|string|max:255|unique:jenis_sampah,nama_sampah,' . $jenisSampah->id,
            'harga_per_kg'   => 'required|numeric|min:0',
            'harga_per_poin' => 'required|numeric|min:0',
        ]);

        // HARGA LAMA DAN BARU
        $hargaLama = $jenisSampah->harga_per_kg;
        $hargaBaru = $request->harga_per_kg;

        // UPDATE NAMA & HARGA SAJA DULU
        $jenisSampah->update([
            'nama_sampah'    => $request->nama_sampah,
            'harga_per_kg'   => $hargaBaru,
            'harga_per_poin' => $request->harga_per_poin,
        ]);

        // JIKA HARGA TIDAK BERUBAH, SELESAI
        if ($hargaLama == $hargaBaru) {
            return redirect()->route('jenis_sampah.index')
                ->with('success', 'Jenis sampah berhasil diperbarui!');
        }

        // ======== PROSES UPDATE SEMUA TRANSAKSI YANG TERDAMPAK ========
        DB::transaction(function () use ($jenisSampah, $hargaLama, $hargaBaru) {

            // AMBIL SEMUA TRANSAKSI credit YANG PAKAI JENIS SAMPAH INI
            $transaksis = Transaction::where('jenis_sampah_id', $jenisSampah->id)
                ->where('type', 'credit')
                ->get();

            foreach ($transaksis as $t) {

                $berat = $t->berat;

                // --- NOMINAL LAMA ---
                $fullLama   = $berat * $hargaLama;
                $bersihLama = $fullLama * 0.96;

                // --- NOMINAL BARU ---
                $fullBaru   = $berat * $hargaBaru;
                $bersihBaru = $fullBaru * 0.96;

                // SELISIH YANG BENAR (user hanya menerima 96%)
                $selisihUser  = $bersihBaru - $bersihLama;
                $selisihAdmin = $selisihUser; // admin hanya kena bersih, bukan FULL

                // UPDATE SALDO USER
                $user = User::find($t->user_id);
                if ($user) {
                    $user->saldo += $selisihUser;
                    $user->save();
                }

                // UPDATE SALDO ADMIN
                $admin = User::find($user->admin_id);
                if ($admin) {
                    $admin->saldo -= $selisihAdmin;
                    $admin->save();
                }

                // UPDATE NOMINAL TRANSAKSI
                $t->amount = $bersihBaru;
                $t->save();
            }
        });

        return redirect()->route('jenis_sampah.index')
            ->with('success', 'Harga berhasil diperbarui! Semua transaksi terkait telah disesuaikan.');
    }

    /**
     * Menghapus jenis sampah dari database.
     */
    public function destroy(JenisSampah $jenisSampah)
    {
        if ($jenisSampah->admin_id != Auth::id()) {
            abort(403);
        }
        $jenisSampah->delete();
        return redirect()->route('jenis_sampah.index')->with('success', 'Jenis sampah berhasil dihapus!');
    }


    // ===================================================================
    // BAGIAN UNTUK MENANGANI SETOR SALDO
    // ===================================================================

    /**
     * Menampilkan halaman form untuk input data dan setor saldo.
     * Logika ini sekarang berada di controller yang tepat.
     */
    public function indexDataSampah()
{
    $adminId = Auth::id();

    $user = User::where('admin_id', $adminId)
        ->where('role', 2)
        ->orderBy('username')
        ->get();

    $jenisSampah = JenisSampah::where('admin_id', $adminId)->get();

    // CARI TRANSAKSI TERBARU USER PERTAMA (atau user pilihan)
    $latestTransaction = Transaction::whereIn('user_id', $user->pluck('id'))
        ->where('type', 'credit')
        ->latest()
        ->first();

    return view('tambah-jenis-sampah.createdata', compact(
        'user',
        'jenisSampah',
        'latestTransaction'
    ));
}

public function updates(Request $request, $id)
{
    $transaksi = Transaction::findOrFail($id);

    $request->validate([
        'jenis_sampah_id' => 'required',
        'berat' => 'required|numeric|min:0.1'
    ]);

    $jenis = JenisSampah::find($request->jenis_sampah_id);

    $full = $request->berat * $jenis->harga_per_kg;
    $bersih = $full * 0.96;

    // UPDATE TRANSAKSI
    $transaksi->jenis_sampah_id = $request->jenis_sampah_id;
    $transaksi->berat = $request->berat;
    $transaksi->amount = $bersih; // yg 96%
    $transaksi->save();

    return back()->with('success', 'Transaksi berhasil diperbarui.');
}

    /**
     * Memproses form, menambah saldo, dan mencatat transaksi.
     */
    public function setorSaldo(Request $request)
    {
        $request->validate([
            'user_ids'        => 'required|array|min:1',
            'user_ids.*'      => 'exists:users,id',
            'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'jml_sampah_perkg'  => 'required|numeric|min:0.01',
        ]);

        $userIds = $request->input('user_ids');
        $beratKg = (float) $request->input('jml_sampah_perkg');
        $jenisSampah = JenisSampah::find($request->input('jenis_sampah_id'));
        
        // Tambahkan pengecekan kepemilikan untuk keamanan
        if ($jenisSampah->admin_id != Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menggunakan jenis sampah milik admin lain.');
        }

        $totalNominal = $beratKg * $jenisSampah->harga_per_kg;

        DB::transaction(function () use ($userIds, $beratKg, $totalNominal, $jenisSampah) {
            foreach ($userIds as $userId) {
                $user = User::find($userId);
                if ($user && $user->admin_id == Auth::id()) { // Pastikan user milik admin yg login
                    $user->saldo += $totalNominal;
                    $user->save();

                    Transaction::create([
                        'user_id' => $user->id,
                        'jenis_sampah_id' => $jenisSampah->id,
                        'berat' => $beratKg,
                        'type' => 'credit',
                        'amount' => $totalNominal * 0.96,
                        'description' => "Setor {$jenisSampah->nama_sampah} seberat $beratKg Kg",
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Saldo berhasil ditambahkan!');
    }
}