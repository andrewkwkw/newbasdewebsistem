<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SmartTrashController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| File ini mengatur jalur komunikasi antara Web, Server, dan Alat (Python).
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ==============================================================================
// 1. JALUR UNTUK WEB DASHBOARD (User Klik Tombol)
// ==============================================================================
// URL: http://ip-server:8000/api/trigger-device
Route::post('/trigger-device', [SmartTrashController::class, 'triggerDevice']);


// ==============================================================================
// 2. JALUR UNTUK ALAT / PYTHON (Raspberry Pi)
// ==============================================================================
Route::prefix('v1')->group(function () {

    // GROUP DEVICE (Alat Bertanya & Lapor Status)
    Route::prefix('device')->group(function () {
        
        // Python Polling: "Ada perintah gak?"
        // URL: http://ip-server:8000/api/v1/device/check-trigger
        Route::get('/check-trigger', [SmartTrashController::class, 'checkTrigger']);

        // [BARU] Python Lapor: "Reset dong, gagal nih!"
        // URL: http://ip-server:8000/api/v1/device/reset
        Route::post('/reset', [SmartTrashController::class, 'resetStatus']);
    });

    // GROUP TRANSACTION (Alat Mengirim Data Sampah)
    Route::prefix('transaction')->group(function () {
        
        // Python Finish: "Nih simpan poinnya!"
        // URL: http://ip-server:8000/api/v1/transaction/save
        Route::post('/save', [SmartTrashController::class, 'store']);
        
    });

});