<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TrashLog; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmartTrashController extends Controller
{
    /**
     * =========================================================================
     * 1. WEB TRIGGER
     * Dipanggil saat User klik tombol "AKTIFKAN ALAT" di Dashboard Web.
     * URL: POST /api/trigger-device
     * =========================================================================
     */
    public function triggerDevice(Request $request)
    {
        // Validasi: Pastikan user_id dikirim
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->input('user_id');

        try {
            // Kita asumsikan ID alat adalah 1 (Single Device)
            // Cek apakah data alat sudah ada di database?
            $device = DB::table('devices')->where('id', 1)->first();

            // Jika belum ada, kita buat otomatis (Auto-init)
            if (!$device) {
                DB::table('devices')->insert([
                    'id' => 1,
                    'name' => 'Main Smart Trash',
                    'status' => 'idle',
                    'current_user_id' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $device = DB::table('devices')->where('id', 1)->first();
            }

            // Cek apakah alat sedang sibuk?
            if ($device->status == 'busy') {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Alat sedang digunakan orang lain. Harap tunggu sebentar.'
                ], 400); // 400 Bad Request
            }

            // UPDATE STATUS JADI 'TRIGGERED'
            // Ini tanda buat Python supaya dia mulai bekerja
            DB::table('devices')->where('id', 1)->update([
                'status' => 'triggered',
                'current_user_id' => $userId,
                'updated_at' => now()
            ]);

            return response()->json([
                'status' => 'success', 
                'message' => 'Perintah terkirim! Kamera alat akan segera aktif.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Gagal trigger alat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * =========================================================================
     * 2. POLLING DARI PYTHON
     * Alat (Raspberry Pi) bertanya setiap detik: "Ada perintah gak?"
     * URL: GET /api/v1/device/check-trigger
     * =========================================================================
     */
    public function checkTrigger()
    {
        try {
            $device = DB::table('devices')->where('id', 1)->first();

            // Jika alat belum di-init, anggap IDLE
            if (!$device) {
                return response()->json(['status' => 'idle']);
            }

            // JIKA STATUSNYA 'TRIGGERED' (Ada yang baru klik tombol di Web)
            if ($device->status == 'triggered') {
                
                // Ambil data user yang menekan tombol
                $user = User::find($device->current_user_id);

                if ($user) {
                    // UBAH JADI 'BUSY'
                    // Supaya perintah ini tidak diambil dua kali / double execution
                    DB::table('devices')->where('id', 1)->update(['status' => 'busy']);

                    // Kirim respon "ACTIVE" ke Python
                    return response()->json([
                        'status' => 'active', 
                        'user' => [
                            'name' => $user->fullname ?? $user->name,
                            'qr_code' => $user->qr_code // Ini kunci untuk simpan poin nanti
                        ]
                    ]);
                }
            }

            // Jika status idle atau busy (tapi bukan triggered baru)
            return response()->json(['status' => 'idle']);

        } catch (\Exception $e) {
            // Jika error, return idle biar Python gak crash
            return response()->json(['status' => 'idle', 'error' => $e->getMessage()]);
        }
    }

    /**
     * =========================================================================
     * 3. TERIMA SAMPAH & RESET STATUS
     * Dipanggil Python setelah sampah valid masuk & dihitung.
     * URL: POST /api/v1/transaction/save
     * =========================================================================
     */
    public function store(Request $request)
    {
        // Validasi Input dari Python
        // Python mengirim: { "qr_code": "...", "points": 50 }
        $request->validate([
            'qr_code' => 'required',
            'points'  => 'required|integer',
        ]);

        try {
            // Cari User berdasarkan QR Code
            $user = User::where('qr_code', $request->qr_code)->first();

            // Jika user tidak ditemukan
            if (!$user) {
                // Tetap reset alat biar gak macet selamanya di status 'busy'
                $this->resetDeviceStatus();
                
                return response()->json([
                    'status' => 'error', 
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            // Simpan Log Transaksi (Riwayat)
            try {
                if (class_exists('App\Models\TrashLog')) {
                    TrashLog::create([
                        'user_id' => $user->id,
                        'amount'  => 1, // Default 1 item per transaksi
                        'points'  => $request->points,
                        'source'  => 'Smart Trash Device',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            } catch (\Exception $e) {
                // Log error tapi jangan hentikan proses poin
                Log::error("Gagal simpan TrashLog: " . $e->getMessage());
            }

            // Update Poin User
            $user->points += $request->points;
            $user->save();

            // [PENTING] RESET ALAT JADI IDLE
            // Supaya alat bisa menerima perintah dari user lain
            $this->resetDeviceStatus();

            return response()->json([
                'status'      => 'success',
                'message'     => 'Poin Berhasil Ditambahkan',
                'total_poin'  => $user->points,
                'user_name'   => $user->fullname ?? $user->name
            ], 200);

        } catch (\Exception $e) {
            // Reset alat kalau server error
            $this->resetDeviceStatus();

            return response()->json([
                'status'  => 'error',
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * =========================================================================
     * 4. [BARU] RESET STATUS MANUAL
     * Dipanggil Python jika Gagal Deteksi / Invalid / Cancel
     * URL: POST /api/v1/device/reset
     * =========================================================================
     */
    public function resetStatus()
    {
        try {
            // Paksa reset ke IDLE
            $this->resetDeviceStatus();

            return response()->json([
                'status' => 'success', 
                'message' => 'Device status has been reset to IDLE'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Reset Failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper Function untuk mereset status alat
     */
    private function resetDeviceStatus()
    {
        DB::table('devices')->where('id', 1)->update([
            'status' => 'idle',
            'current_user_id' => null,
            'message' => null, // Reset message juga
            'updated_at' => now()
        ]);
    }

    /**
     * =========================================================================
     * 5. [BARU] TERIMA LOG DARI PYTHON
     * URL: POST /api/v1/device/log
     * =========================================================================
     */
    public function deviceLog(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        try {
            DB::table('devices')->where('id', 1)->update([
                'message' => $request->message,
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * =========================================================================
     * 6. [BARU] CEK STATUS UNTUK WEB DASHBOARD
     * URL: GET /api/device-status
     * =========================================================================
     */
    public function deviceStatus()
    {
        try {
            $device = DB::table('devices')->where('id', 1)->first();
            return response()->json([
                'status' => $device->status ?? 'idle',
                'message' => $device->message ?? ''
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }
}