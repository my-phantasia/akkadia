<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PeminjamanController;

Route::get('/', function () {
    return redirect()->route('buku.index');
});

// Proteksi rute dengan middleware auth agar petugas harus login terlebih dahulu
Route::middleware(['auth'])->group(function () {

    // Rute untuk Manajemen & Pencarian Buku (Req #1 & #4)
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');

    // Rute untuk Transaksi Peminjaman & Pengembalian (Req #1 & #2)
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::patch('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');

    // Rute untuk Dashboard/Profil Anggota & Rekomendasi (Req #6)
    Route::get('/anggota/{anggota}', [AnggotaController::class, 'show'])->name('anggota.show');
});
