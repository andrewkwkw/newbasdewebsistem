<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DeviceApiController extends Controller
{
    // 1. [WEB] Dipanggil saat Caesar klik tombol "AKTIFKAN ALAT" di Dashboard
    public function triggerDevice(Request $request)
    {
        $userId = $request->input('user_id');

        // Cek status alat (Asumsi ID alat = 1)
        $device = DB::table('devices')->where('id', 1)->first();

        if ($device->status == 'busy') {
            return response()->json(['status' => 'error', 'message' => 'Alat sedang digunakan orang lain!'], 400);
        }

        // Update status jadi TRIGGERED
        DB::table('devices')->where('id', 1)->update([
            'status' => 'triggered',
            'current_user_id' => $userId,
            'updated_at' => now()
        ]);

        return response()->json(['status' => 'success', 'message' => 'Perintah dikirim ke alat.']);
    }

    // 2. [PYTHON] Dipanggil alat setiap 1 detik (Polling)
    public function checkTrigger()
    {
        $device = DB::table('devices')->where('id', 1)->first();

        // Jika statusnya TRIGGERED (Ada user yang baru klik tombol)
        if ($device->status == 'triggered') {
            
            // Ambil data user yang men-trigger
            $user = User::find($device->current_user_id);

            // Ubah status jadi BUSY (Supaya gak didouble-claim)
            DB::table('devices')->where('id', 1)->update(['status' => 'busy']);

            return response()->json([
                'status' => 'active', // Kuncinya disini (Python baca ini)
                'user' => [
                    'name' => $user->fullname ?? 'User',
                    'qr_code' => $user->qr_code // Ini dipake Python buat nanti simpen poin
                ]
            ]);
        }

        // Jika tidak ada apa-apa
        return response()->json(['status' => 'idle']);
    }
}