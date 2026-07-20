<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;

Route::get('/', function () {
    return redirect()->route('buku.index');
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// routes/web.php

Route::middleware(['auth'])->group(function () {
    // Menggunakan Resource Route untuk otomatisasi CRUD penuh (Req #1)
    Route::resource('buku', BukuController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('anggota', AnggotaController::class)->parameters([
        'anggota' => 'anggota'
    ]);

    // Transaksi Peminjaman & Pengembalian (Req #1 & #2)
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::patch('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
});
