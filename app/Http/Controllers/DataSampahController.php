<?php

namespace App\Http\Controllers;
use App\Models\JenisSampah;
use App\Models\User;
use App\Models\MasukUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DataSampahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index1()
    {   
        $users = User::with('userData')->get();
        return view('tambah-jenis-sampah.viewdata',compact('users'));
    }

    public function tambahKeuangan($id)
    {
    // Temukan pengguna berdasarkan ID
    $user = User::findOrFail($id);

    // Kirimkan pengguna ke tampilan
    return view('admin.masuk', compact('user'));
    }
    
    public function tarikKeuangan()
    {
        return view('admin.keluar');
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'jml_sampah_perkg' => 'required|numeric|min:0.01',
        ]);

        $jenisSampah = JenisSampah::findOrFail($request->jenis_sampah_id);
        $hargaPerKg = $jenisSampah->harga_per_kg;
        $berat = $request->jml_sampah_perkg;
        $totalNominal = $berat * $hargaPerKg;

        $admin = auth()->user();
        $totalKeluar = $totalNominal * count($request->user_ids);

        // ✅ Debug sebelum pengurangan saldo
        dd([
            'saldo_admin_sebelum' => $admin->saldo,
            'total_keluar' => $totalKeluar,
            'saldo_admin_sesudah' => $admin->saldo - $totalKeluar,
            'jumlah_user_dipilih' => count($request->user_ids),
        ]);

        // Kalau data udah benar, baru hapus dd() di atas dan jalankan baris di bawah:
        /*
        if ($admin->saldo < $totalKeluar) {
            return redirect()->back()->with('error', 'Saldo admin tidak mencukupi.');
        }

        foreach ($request->user_ids as $userId) {
            MasukUser::create([
                'user_id' => $userId,
                'admin_id' => $admin->id,
                'jenis_sampah_id' => $request->jenis_sampah_id,
                'jml_sampah_perkg' => $berat,
                'uang' => $totalNominal,
            ]);
        }

        $admin->saldo -= $totalKeluar;
        $admin->save();

        return redirect()->route('jenis_sampah.index')->with('success', 'Transaksi berhasil!');
        */
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
