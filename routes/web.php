<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PeminjamanController;

Route::get('/', function () {
    return redirect()->route('buku.index');
});

// routes/web.php

Route::middleware(['auth'])->group(function () {
    // Menggunakan Resource Route untuk otomatisasi CRUD penuh (Req #1)
    Route::resource('buku', BukuController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('anggota', AnggotaController::class);

    // Transaksi Peminjaman & Pengembalian (Req #1 & #2)
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::patch('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
});
