<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\MitraPendaftaranController;
use App\Http\Controllers\MitraQrController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| 
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
| 
*/

Route::get('/', function () {
    return view('mitra');
});

Route::get('/daftar', [PendaftaranController::class, 'create'])->name('daftar.create');
Route::post('/daftar', [PendaftaranController::class, 'store'])->name('daftar.store');
Route::get('/daftar/berhasil', [PendaftaranController::class, 'success'])->name('daftar.berhasil');

Route::get('/daftar-mitra', [MitraPendaftaranController::class, 'create'])->name('daftar-mitra.create');
Route::post('/daftar-mitra', [MitraPendaftaranController::class, 'store'])->name('daftar-mitra.store');
Route::get('/daftar-mitra/berhasil', [MitraPendaftaranController::class, 'success'])->name('daftar-mitra.success');

// Unduhan QR Referral – hanya untuk mitra yang sudah login
Route::middleware('auth')->get('/mitra/qr-referral/unduh', [MitraQrController::class, 'download'])->name('mitra.qr.download');
