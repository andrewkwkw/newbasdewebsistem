<?php

namespace App\Http\Controllers;

use App\Models\KeluarUser;
use App\Models\MasukUser;
use App\Models\Transaction;
use App\Models\User;
use App\Models\TrashLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function simpanTransaksi(Request $request)
    {
        // 1. Validasi Input
        $qrCode = $request->input('qr_code');
        $poinMasuk = $request->input('points'); // Berapa poin yg mau ditambah

        if (!$qrCode || !$poinMasuk) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak lengkap'], 400);
        }

        // 2. Cari User
        $user = User::where('qr_code', $qrCode)->first();

        if ($user) {
            // 3. Update Poin (INCREMENT)
            // Ini akan menambah poin lama + poin baru
            $user->increment('points', $poinMasuk);

            return response()->json([
                'status' => 'success',
                'message' => 'Poin berhasil ditambahkan',
                'total_poin' => $user->points
            ], 200);
        }

        return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan'], 404);
    }
    // --- API UNTUK RASPBERRY PI (VALIDASI USER) ---
    public function cekUser(Request $request)
    {
        // Ambil QR Code dari JSON yang dikirim Python
        $qrCode = $request->input('qr_code'); 

        $user = User::where('qr_code', $qrCode)->first();

        if ($user) {
            return response()->json([
                'status' => 'success',
                'valid' => true,
                'user' => [
                    'nama' => $user->fullname,
                    'poin' => $user->points // Pastikan kolom di database namanya 'points'
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'valid' => false,
            'message' => 'User tidak ditemukan'
        ], 404);
    }

    // --- FITUR AUTO GENERATE QR CODE ---
    public function kartuSaya()
    {
        $user = Auth::user();

        // Cek apakah qr_code kosong/null
        if (empty($user->qr_code)) {
            // Generate kode unik dan simpan
            $user->qr_code = 'KSP-' . strtoupper(Str::random(10)); 
            $user->save();
        }

        return view('kartu'); 
    }    

    // --- DASHBOARD USER (PERBAIKAN UTAMA DISINI) ---
    public function index()
    {
        $userId = auth()->id();
        $userFresh = User::find($userId);

        // Generate QR Code jika belum ada
        if (empty($userFresh->qr_code)) {
            $userFresh->qr_code = 'KSP-' . strtoupper(Str::random(10));
            $userFresh->save();
        }

        $points = $userFresh->points; 

        // --- Transaksi Saldo (Manual) ---
        $transactions = Transaction::with('jenisSampah')
            ->where('user_id', $userId)
            ->get();

        // [BARU] AMBIL RIWAYAT POIN DARI SMART TRASH
        // Kita ambil 5 atau 10 data terakhir saja biar dashboard tidak kepanjangan
        $trashLogs = TrashLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc') // Urutkan dari yang terbaru
            ->limit(10) 
            ->get();

        // --- Hitung Saldo & Reduksi (Biarkan kode lama ini) ---
        $totalSaldo = $transactions->sum(function ($t) {
            if ($t->jenisSampah) {
                return ($t->berat * $t->jenisSampah->harga_per_kg) * 0.96; 
            }
            return 0; 
        });

        $dataReduksi = $transactions->filter(fn ($t) => $t->jenisSampah)->map(function ($t) {
            $hargaKotor = $t->berat * $t->jenisSampah->harga_per_kg;
            return (object)[
                'jenis'   => $t->jenisSampah->nama_sampah,
                'awal'    => $t->berat,
                'reduksi' => $t->berat,
                'uang'    => $hargaKotor * 0.96 
            ];
        });

        $totalReduksiKg = $dataReduksi->sum('awal');
        $totalReduksiUang = $dataReduksi->sum('uang'); 

        return view('users.dashboard', compact(
            'totalSaldo',
            'dataReduksi',
            'totalReduksiKg',
            'totalReduksiUang',
            'points',
            'userFresh',
            'trashLogs' // [BARU] Kirim variabel ini ke view
        ));
    }

    // --- FITUR LAINNYA (Standard) ---

    public function indexmasuk()
    {
        return view('users.masuk');
    }

    public function createmasuk(Request $request)
    {
        $request->validate(['uang' => 'required']);
        MasukUser::create(['uang' => $request->uang]);
        return redirect('user/dashboard');
    }

    public function indexkeluar()
    {
        return view('users.keluar');
    }

    public function createkeluar(Request $request)
    {
        $request->validate(['uang' => 'required']);
        KeluarUser::create(['uang' => $request->uang]);
        return redirect('user/dashboard');
    }

    public function show($id)
    {
        $user = User::with('masukUsers', 'keluarUsers', 'dataSampah')->findOrFail($id);
        $totalMasuk = $user->masukUsers->sum('uang');
        $totalKeluar = $user->keluarUsers->sum('uang');
        $totalSaldo = $totalMasuk - $totalKeluar;

        return view('users.show', compact('user', 'totalMasuk', 'totalKeluar', 'totalSaldo'));
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('username', 'LIKE', "%$query%")
                    ->orWhere('email', 'LIKE', "%$query%")
                    ->orWhere('no_telpon', 'LIKE', "%$query%")
                    ->get();

        $totalMasuk = MasukUser::sum('uang');
        $totalKeluar = KeluarUser::sum('uang');
        $totalSaldo = $totalMasuk - $totalKeluar;

        // Formatting angka sebaiknya dilakukan di View, tapi disini tidak apa-apa
        $totalMasuk = number_format($totalMasuk, 0, ',', '.');
        $totalKeluar = number_format($totalKeluar, 0, ',', '.');
        $totalSaldo = number_format($totalSaldo, 0, ',', '.');

        return view('users.search', compact('users', 'totalMasuk', 'totalKeluar', 'totalSaldo'));
    }

    public function index2()
    {
        $users = User::with('userData')->get(); 
        return view('tambah-jenis-sampah.viewdata', compact('users'));
    }

    public function show1($id)
    {
        $user = User::with('userData')->findOrFail($id); 
        return view('tambah-jenis-sampah.datasampahuser', compact('user'));
    }

    public function lihatKeuangan($id)
    {
        $user = User::findOrFail($id);
        $keuangan = MasukUser::where('user_id', $id)->paginate(5);
        return view('tambah-jenis-sampah.tambahuang', compact('user', 'keuangan'));
    }

    public function lihatKeuanganKeluar($id)
    {
        $user = User::findOrFail($id);
        $keluar = KeluarUser::where('user_id', $user->id)->paginate(10);
        return view('tambah-jenis-sampah.keluar', compact('user', 'keluar'));
    }

    public function editProfile($id)
    {
        $user = User::findOrFail($id);
        return view('admin.profile.index', compact('user'));
    }

    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'no_telpon' => 'required|string|max:15',
            'tempat' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'fullname' => 'required|string|max:255',
        ]);

        $user->update($request->only([
            'username', 'email', 'no_telpon', 'tempat', 'tanggal_lahir', 'fullname'
        ]));

        return redirect()->route('users.profile.edit', $id)
                        ->with('success', 'Profil berhasil diperbarui.');
    }
}