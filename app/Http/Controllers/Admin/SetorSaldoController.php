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
            'jml_sampah_perkg'=> 'required|numeric|min:0.01',
        ]);

        $admin = Auth::user();
        
        // Ambil User Pertama
        $targetUserId = $request->user_ids[0];

        // Cek Admin tidak kirim ke diri sendiri
        if ($targetUserId == $admin->id) {
            return back()->withErrors(['user_ids' => 'Admin tidak boleh mengirim ke diri sendiri!']);
        }

        $beratKg     = (float) $request->jml_sampah_perkg;
        $jenisSampah = JenisSampah::findOrFail($request->jenis_sampah_id);

        // Hitung Nominal
        $nominalFull = $beratKg * $jenisSampah->harga_per_kg;
        $nominal96   = $nominalFull * 0.96; 

        // --- [HAPUS BAGIAN INI] ---
        // Tidak perlu cek saldo admin, karena uang dari sistem
        // if ($admin->saldo < $nominal96) {
        //    return back()->with('error', 'Saldo Admin Tidak Cukup! ...');
        // }
        // --------------------------

        DB::transaction(function () use ($admin, $targetUserId, $nominal96, $beratKg, $jenisSampah) {

            // --- [HAPUS BAGIAN INI] ---
            // Jangan kurangi saldo admin
            // User::where('id', $admin->id)->decrement('saldo', $nominal96);
            // --------------------------

            // A. UPDATE SALDO USER (INCREMENT SAJA)
            // Uang bertambah di user, seolah-olah "dicetak" oleh sistem atau diambil dari kas besar bank sampah
            $penerima = User::find($targetUserId);
            $penerima->increment('saldo', $nominal96);

            // B. CATAT TRANSAKSI (HANYA UNTUK USER)
            Transaction::create([
                'user_id'         => $penerima->id,
                'type'            => 'credit', // Credit = Uang Masuk ke User
                'amount'          => $nominal96,
                'jenis_sampah_id' => $jenisSampah->id,
                'berat'           => $beratKg,
                'description'     => "Terima setor {$jenisSampah->nama_sampah} {$beratKg} Kg (Diproses oleh {$admin->username})",
            ]);

            // C. CATAT RIWAYAT MASUK
            MasukUser::create([
                'user_id'         => $penerima->id,
                'jenis_sampah_id' => $jenisSampah->id,
                'jml_sampah_perkg'=> $beratKg,
                'uang'            => $nominal96,
                'admin_id'        => $admin->id, // Admin hanya tercatat sebagai petugas
            ]);
        });

        return back()->with('success', 'Transaksi Berhasil! Saldo User bertambah Rp ' . number_format($nominal96));
    }


    public function index(Request $request)
    {
        // 1. Filter Bulan
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $start = Carbon::parse($bulan)->startOfMonth();
        $end = Carbon::parse($bulan)->endOfMonth();

        // 2. Ambil Data dari TrashLog (Bukan Transaction)
        // Kita hapus filter 'admin_id' sementara supaya semua data warga terlihat
        $transaksi = TrashLog::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        // 3. HITUNG TOTAL POIN (Langsung dari kolom 'points')
        $totalPoin = $transaksi->sum('points');

        // 4. Total Sampah (Dalam Pcs/Item, karena Smart Trash hitungannya pcs)
        $totalKg = $transaksi->sum('amount');

        // 5. Grouping Per Jenis Sampah
        // Karena TrashLog biasanya tidak ada 'jenis_sampah_id', kita buat manual kategori "Botol Plastik"
        // atau kita grouping berdasarkan 'source' jika ada.
        $perJenis = collect([
            [
                'nama_sampah' => 'Botol Plastik (Smart Trash)',
                'total_kg'    => $totalKg,
                'total_poin'  => $totalPoin
            ]
        ]);

        // 6. Grouping Per Nasabah
        $perUser = $transaksi->groupBy('user_id')->map(function ($items) {
            return [
                'user'       => $items->first()->user->fullname ?? $items->first()->user->name ?? 'Warga Umum',
                'total_kg'   => $items->sum('amount'),
                'total_poin' => $items->sum('points'),
            ];
        })->sortByDesc('total_poin'); // Urutkan dari poin terbanyak

        // 7. Modifikasi Data Transaksi agar tidak Error di View
        // View mengharapkan objek 'jenisSampah' dan 'harga_per_kg', kita inject manual
        foreach($transaksi as $t) {
            // Kita "pura-pura" membuat objek jenisSampah agar view tidak error
            $t->jenisSampah = (object) [
                'nama_sampah' => 'Botol Plastik',
                'harga_per_kg' => $t->amount > 0 ? round($t->points / $t->amount) : 0 // Hitung rata-rata poin per item
            ];
            
            // Map 'amount' (pcs) ke 'berat' agar view yang pakai $t->berat tetap jalan
            $t->berat = $t->amount; 
        }

        return view('admin.laporan.index', compact(
            'bulan',
            'totalPoin',
            'totalKg',
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