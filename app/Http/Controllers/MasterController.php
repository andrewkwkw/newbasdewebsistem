<?php

namespace App\Http\Controllers;

use App\Models\Keluar;
use App\Models\KeluarUser;
use App\Models\Masuk;
use App\Models\MasukUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterController extends Controller
{

    public function index()
    {
        $admin = Auth::user();
        $adminId = $admin->id;

        $users = User::where('role', 2)
            ->where('admin_id', $adminId)
            ->with(['transactions.jenisSampah'])
            ->get();

        $totalMasuk = 0;
        $totalKeluar = 0;

        foreach ($users as $user) {

            // USER TOTAL MASUK (SETELAH POTONGAN)
            $masukBersih = $user->transactions
                ->where('type', 'credit')
                ->sum(function ($t) {

                    if ($t->berat && $t->jenisSampah) {
                        $full = $t->berat * $t->jenisSampah->harga_per_kg;
                        return $full * 0.96; // user terima 96%
                    }

                    return $t->amount ?? 0;
                });

            // USER TOTAL FULL (tanpa potongan)
            $masukFull = $user->transactions
                ->where('type', 'credit')
                ->sum(function ($t) {
                    if ($t->berat && $t->jenisSampah) {
                        return $t->berat * $t->jenisSampah->harga_per_kg;
                    }
                    return $t->amount ?? 0;
                });

            $keluar = $user->transactions
                ->where('type', 'debit')
                ->sum('amount');

            // SIMPAN KE PROPERTY
            $user->total_masuk = $masukFull; // tampil full
            $user->saldo = $masukBersih - $keluar;

            $totalMasuk += $masukFull;
            $totalKeluar += $keluar;
        }

        return view('admin.dashboard', [
            'users' => $users,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'adminSaldo' => $admin->saldo,
        ]);
    }



    public function tambahSaldo(Request $request)
    {
        $request->validate([
            'jumlah' => 'required',
        ]);

        // Hilangkan semua karakter kecuali angka
        $jumlah = preg_replace('/[^0-9]/', '', $request->jumlah);
        $jumlah = (int) $jumlah;

        $admin = Auth::user();

        DB::transaction(function () use ($admin, $jumlah) {

            // Tambahkan saldo
            $admin->saldo += $jumlah;
            $admin->save();

            Transaction::create([
                'user_id' => $admin->id,
                'type' => 'credit',
                'amount' => $jumlah,
                'description' => 'Penambahan saldo admin secara manual',
            ]);
        });

        return redirect()->back()->with('success', 'Saldo admin berhasil ditambahkan!');
    }



    public function updateSaldo(Request $request, $adminId)
    {
        // Validasi input
        $request->validate([
            'saldo_baru' => 'required|numeric|min:0',
        ]);

        $admin = User::findOrFail($adminId);
        $saldoBaru = (float) $request->saldo_baru;

        DB::transaction(function () use ($admin, $saldoBaru) {

            $saldoLama = $admin->saldo;

            // Update saldo admin
            $admin->saldo = $saldoBaru;
            $admin->save();

            // Catat perubahan saldo (opsional tapi sangat bagus untuk audit)
            Transaction::create([
                'user_id' => $admin->id,
                'type' => 'credit', // atau bisa 'adjust' jika ingin khusus
                'amount' => $saldoBaru - $saldoLama, // selisih perubahannya
                'description' => "Update saldo admin. Dari: Rp $saldoLama menjadi: Rp $saldoBaru",
            ]);
        });

        return redirect()
            ->back()
            ->with('success', 'Saldo admin berhasil diperbarui!');
    }

    public function toggleApprove($id)
    {
        $user = User::findOrFail($id);
        $user->approve = !$user->approve;
        $user->save();

        return response()->json(['status' => 'success', 'approve' => $user->approve]);
    }

    public function indexmasuk()
    {
        $users = User::with('masukUsers')->get();
        return view('admin.masuk', compact('users'));
    }

    public function simpanUang(Request $request, $id)
    {
        $request->validate([
            'uang' => 'required|integer',
        ]);

        $user = User::findOrFail($id);

        $masukUser = new MasukUser();
        $masukUser->user_id = $user->id;
        $masukUser->uang = $request->uang;
        $masukUser->save();

        return redirect()->route('masuk', $id)->with('success', 'Uang berhasil ditambahkan.');
    }

    public function createmasuk(Request $request)
    {

        // Validasi data dari formulir
        $validatedData = $request->validate([
            'email' => 'required|email|exists:users,email', // Pastikan email valid dan ada di database
            'uang' => 'required|numeric|min:1', // Pastikan uang adalah angka positif
        ]);

        // Temukan pengguna berdasarkan email
        $user = User::where('email', $request->input('email'))->firstOrFail();

        // Buat entri baru di model MasukUser
        MasukUser::create([
            'user_id' => $user->id,
            'uang' => $request->input('uang'),
        ]);
        // Redirect ke halaman detail pengguna atau halaman lain yang sesuai
        return redirect()->route('jenis_sampah');
    }


    public function indexkeluar()
    {
        $users = User::with('masukUsers')->get();
        return view('admin.keluar', compact('users'));
    }

    public function createkeluar(Request $request)
    {
        // Validasi data dari formulir
        $validatedData = $request->validate([
            'email' => 'required|email|exists:users,email', // Pastikan email valid dan ada di database
            'uang' => 'required|numeric|min:1', // Pastikan uang adalah angka positif
        ]);

        // Temukan pengguna berdasarkan email
        $user = User::where('email', $request->input('email'))->firstOrFail();

        // Pastikan bahwa user memiliki cukup saldo untuk pengeluaran ini
        $currentBalance = $user->masukUsers->sum('uang') - $user->keluarUsers->sum('uang');
        $uangToWithdraw = $request->input('uang');

        if ($uangToWithdraw > $currentBalance) {
            return redirect()->back()->withErrors(['uang' => 'Saldo tidak mencukupi untuk pengeluaran ini.']);
        }

        // Buat entri baru di model KeluarUser untuk mengurangi saldo
        KeluarUser::create([
            'user_id' => $user->id,
            'uang' => $uangToWithdraw,
        ]);

        // Redirect ke halaman yang sesuai
        return redirect()->route('jenis_sampah')->with('success', 'Pengeluaran berhasil dilakukan!');
    }



    public function search(Request $request)
    {

        $query = $request->input('query');

        // Cari pengguna berdasarkan username, email, atau nomor telepon
        $users = User::where('username', 'LIKE', "%$query%")
            ->orWhere('email', 'LIKE', "%$query%")
            ->orWhere('no_telpon', 'LIKE', "%$query%")
            ->get();

        // Hitung total uang masuk, uang keluar, dan saldo
        $totalMasuk = Masuk::sum('uang');
        $totalKeluar = Keluar::sum('uang');
        $totalSaldo = $totalMasuk - $totalKeluar;

        $totalMasuk = number_format($totalMasuk, 0, ',', '.');
        $totalKeluar = number_format($totalKeluar, 0, ',', '.');
        $totalSaldo = number_format($totalSaldo, 0, ',', '.');

        if ($users == true) {
            return view('admin.search', compact('users', 'totalMasuk', 'totalKeluar', 'totalSaldo'));
        } else {
            return back()->with('message', 'Data pengguna yang cocok dengan kata kunci ' . $query . 'tidak ditemukan.');
        }


    }


    public function updateSaldoUser(Request $request, $id)
    {
        $request->validate([
            'saldo_baru' => 'required',
        ]);

        // hapus format 2.000.000 → 2000000
        $saldoBaru = str_replace('.', '', $request->saldo_baru);
        $saldoBaru = (float) $saldoBaru;

        $user = User::findOrFail($id);

        DB::transaction(function () use ($user, $saldoBaru) {

            // Update kolom saldo user
            $user->saldo = $saldoBaru;
            $user->save();

            // Catat transaksi riwayat (opsional)
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'manual',
                'amount' => $saldoBaru,
                'description' => 'Perubahan saldo manual oleh admin',
            ]);
        });

        return back()->with('success', 'Saldo user berhasil diperbarui.');
    }

}
