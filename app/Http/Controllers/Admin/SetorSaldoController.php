<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use App\Models\TrashLog;
use App\Models\MasukUser;
use App\Models\JenisSampah;
use App\Models\Transaction; // Pastikan model Transaction ada
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SetorSaldoController extends Controller
{
    /**
     * Menampilkan halaman form untuk input data dan setor saldo.
     * Logika ini sekarang berada di controller yang tepat.
     */
    public function create()
    {
        // 1. Dapatkan ID admin yang sedang login
        $adminId = Auth::id();

        // 2. Ambil HANYA user yang dimiliki oleh admin ini
        $user = User::where('admin_id', $adminId)
                     ->where('role', 2) // Asumsi role_id 2 untuk user biasa
                     ->orderBy('username', 'asc')
                     ->get();
        
        // 3. Ambil HANYA jenis sampah yang dimiliki oleh admin ini
        $jenisSampah = JenisSampah::where('admin_id', $adminId)->get();

        // 4. Tampilkan view dan kirim data yang sudah terfilter
        // Pastikan path view ini benar: resources/views/tambah-jenis-sampah/createdata.blade.php
        return view('tambah-jenis-sampah.createdata', [
            'user' => $user,   // Ubah jadi 'user' supaya blade yang panggil $user sesuai
            'jenisSampah' => $jenisSampah,  // juga agar blade sesuai dengan $jenisSampah
        ]);
    }

    /**
     * Memproses form, menambah saldo, dan mencatat transaksi.
     */

    public function store(Request $request)
    {
        // 1. VALIDASI
        $request->validate([
            'user_ids'        => 'required|array|min:1', 
            'user_ids.*'      => 'exists:users,id',
            'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'jml_sampah'      => 'required|numeric|min:0.01',
            'tipe_setoran'    => 'required|in:kg,poin'
        ]);

        $admin = Auth::user();
        
        // Ambil User Pertama
        $targetUserId = $request->user_ids[0];

        // Cek Admin tidak kirim ke diri sendiri
        if ($targetUserId == $admin->id) {
            return back()->withErrors(['user_ids' => 'Admin tidak boleh mengirim ke diri sendiri!']);
        }

        $jumlahInput = (float) $request->jml_sampah;
        $jenisSampah = JenisSampah::findOrFail($request->jenis_sampah_id);
        $tipe = $request->tipe_setoran;
        
        $penerima = User::findOrFail($targetUserId);

        $nominalAkhir = 0;
        $desc = "";
        
        if ($tipe === 'poin') {
            // Validasi Poin
            if ($penerima->points < $jumlahInput) {
                return back()->with('error', 'Poin warga tidak mencukupi untuk ditukar!');
            }
            // Poin * Harga Poin (Full 100%)
            $nominalAkhir = $jumlahInput * $jenisSampah->harga_per_poin;
            $desc = "Tukar " . $jumlahInput . " Poin Smart Trash ({$jenisSampah->nama_sampah})";
        } else {
            // Kg * Harga Kg (Dipotong 4%)
            $nominalFull = $jumlahInput * $jenisSampah->harga_per_kg;
            $nominalAkhir = $nominalFull * 0.96;
            $desc = "Setor Manual {$jenisSampah->nama_sampah} seberat {$jumlahInput} Kg";
        }

        DB::transaction(function () use ($admin, $penerima, $nominalAkhir, $jumlahInput, $jenisSampah, $tipe, $desc) {

            // Jika Tukar Poin, Potong poinnya!
            if ($tipe === 'poin') {
                $penerima->points -= $jumlahInput;
                $penerima->save();
                
                // Catat history pemotongan poin di TrashLog
                if (class_exists('\App\Models\TrashLog')) {
                    \App\Models\TrashLog::create([
                        'user_id' => $penerima->id,
                        'amount'  => 1, // dummy
                        'points'  => -$jumlahInput,
                        'source'  => 'Redeem Poin',
                    ]);
                }
            }

            // A. UPDATE SALDO USER (INCREMENT SAJA)
            $penerima->increment('saldo', $nominalAkhir);

            // B. CATAT TRANSAKSI (HANYA UNTUK USER)
            Transaction::create([
                'user_id'         => $penerima->id,
                'type'            => 'credit', // Credit = Uang Masuk ke User
                'amount'          => $nominalAkhir,
                'jenis_sampah_id' => $jenisSampah->id,
                'berat'           => $jumlahInput, // Bisa kg, bisa poin (disatukan di field berat untuk kepraktisan db)
                'description'     => $desc . " (Oleh {$admin->username})",
            ]);

            // C. CATAT RIWAYAT MASUK
            MasukUser::create([
                'user_id'         => $penerima->id,
                'jenis_sampah_id' => $jenisSampah->id,
                'jml_sampah_perkg'=> $jumlahInput,
                'uang'            => $nominalAkhir,
                'admin_id'        => $admin->id, // Admin hanya tercatat sebagai petugas
            ]);
        });

        return back()->with('success', 'Transaksi Berhasil! Saldo User bertambah Rp ' . number_format($nominalAkhir, 0, ',', '.'));
    }


    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $start = Carbon::parse($bulan)->startOfMonth();
        $end = Carbon::parse($bulan)->endOfMonth();

        // Ubah kembali menggunakan tabel Transaction (Setor Manual / Tukar Poin)
        $transaksi = Transaction::with(['user', 'jenisSampah'])
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        // Hapus null jenis sampah
        $transaksi = $transaksi->filter(function ($t) {
            return $t->jenisSampah !== null;
        });

        // Hitung total berat/poin yang disetor (kolom 'berat')
        $totalPoin = $transaksi->sum('berat');
        
        // Hitung total uang
        $totalRp = $transaksi->sum('amount');

        // Grouping Per Jenis Sampah
        $perJenis = $transaksi->groupBy('jenis_sampah_id')->map(function ($row) {
            $first = $row->first();
            $poinSetor = $row->sum('berat');
            $uang = $row->sum('amount');
            return [
                'nama_sampah' => $first->jenisSampah->nama_sampah,
                'total_poin'  => $poinSetor,
                'total_uang'  => $uang
            ];
        })->filter()->sortByDesc('total_poin');

        // Grouping Per Nasabah
        $perUser = $transaksi->groupBy('user_id')->map(function ($row) {
            $first = $row->first();
            $poinSetor = $row->sum('berat');
            $uang = $row->sum('amount');
            return [
                'user'       => $first->user->fullname ?? $first->user->name ?? 'Warga Umum',
                'total_poin' => $poinSetor,
                'total_uang' => $uang
            ];
        })->filter()->sortByDesc('total_poin');

        return view('admin.laporan.index', compact(
            'bulan',
            'totalPoin',
            'totalRp',
            'perJenis',
            'perUser',
            'transaksi'
        ));
    }

    public function cetakPdf(Request $request)
    {
        // 1. Tentukan Bulan
        $bulan = $request->input('bulan', date('Y-m'));
        [$year, $month] = explode('-', $bulan);

        // 2. Ambil Data Transaksi
        // Jika Anda menggunakan SoftDeletes pada JenisSampah, tambahkan ->withTrashed() pada query relasi
        $transaksi = Transaction::with(['user', 'jenisSampah' => function($query) {
                // $query->withTrashed(); // Aktifkan baris ini jika pakai SoftDeletes
            }])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        // === FIX UTAMA DI SINI ===
        // Filter: Hapus transaksi yang jenis sampahnya (jenisSampah) bernilai null
        $transaksi = $transaksi->filter(function ($t) {
            return $t->jenisSampah !== null;
        });
        // =========================

        // 3. Hitung Ringkasan
        $totalKg = $transaksi->sum('berat');
        
        $totalUangFull = 0;
        foreach($transaksi as $t) {
            // Tidak perlu "if" lagi karena data null sudah dibuang di atas
            $totalUangFull += $t->berat * $t->jenisSampah->harga_per_kg;
        }
        $totalUangBersih = $totalUangFull * 0.96;

        // 4. Grouping Per Jenis Sampah
        $perJenis = $transaksi->groupBy('jenis_sampah_id')->map(function ($row) {
            $first = $row->first();
            // Cek double safety (opsional)
            if (!$first->jenisSampah) return null;

            $kg = $row->sum('berat');
            $uangFull = $row->sum(fn($t) => $t->berat * $t->jenisSampah->harga_per_kg);
            
            return [
                'nama_sampah' => $first->jenisSampah->nama_sampah,
                'total_kg' => $kg,
                'total_uang_full' => $uangFull,
                'total_uang_bersih' => $uangFull * 0.96
            ];
        })->filter(); // Filter lagi untuk membuang array null jika ada

        // 5. Grouping Per User
        $perUser = $transaksi->groupBy('user_id')->map(function ($row) {
            $first = $row->first();
            // Cek user exists
            if (!$first->user) return null; 

            $kg = $row->sum('berat');
            $uangFull = $row->sum(fn($t) => $t->berat * $t->jenisSampah->harga_per_kg);

            return [
                'user' => $first->user->fullname,
                'total_kg' => $kg,
                'total_uang_full' => $uangFull,
                'total_uang_bersih' => $uangFull * 0.96
            ];
        })->filter();

        // 6. Generate PDF
        $pdf = Pdf::loadView('admin.laporan_pdf', compact(
            'transaksi', 'bulan', 'totalKg', 'totalUangFull', 'totalUangBersih', 'perJenis', 'perUser'
        ));

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-Bulanan-'.$bulan.'.pdf');
    }

}