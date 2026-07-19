<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Enums\StatusPeminjaman;
use Illuminate\Support\Facades\DB;
use Exception;

class PeminjamanService
{
    // Req #1 & #5: Create peminjaman dan validasi/kurangi stok
    public function prosesPeminjaman(array $data, array $bukuIds): Peminjaman
    {
        return DB::transaction(function () use ($data, $bukuIds) {
            $peminjaman = Peminjaman::create([
                ...$data,
                'status' => StatusPeminjaman::DIPINJAM,
            ]);

            foreach ($bukuIds as $bukuId) {
                // Lock for update mencegah race condition saat cek stok
                $buku = Buku::where('id', $bukuId)->lockForUpdate()->first();

                if ($buku->stok < 1) {
                    throw new Exception("Stok buku '{$buku->judul}' tidak mencukupi.");
                }

                // Kurangi stok
                $buku->decrement('stok');

                $peminjaman->detailPeminjamans()->create([
                    'buku_id' => $buku->id,
                    'jumlah' => 1 // Asumsi 1 buku per judul
                ]);
            }

            return $peminjaman;
        });
    }

    // Req #2 & #5: Proses pengembalian dan tambah stok
    public function prosesPengembalian(Peminjaman $peminjaman): void
    {
        if ($peminjaman->status === StatusPeminjaman::DIKEMBALIKAN) {
            throw new Exception("Buku sudah dikembalikan sebelumnya.");
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->update([
                'status' => StatusPeminjaman::DIKEMBALIKAN,
                'tanggal_kembali' => now(),
            ]);

            foreach ($peminjaman->detailPeminjamans as $detail) {
                // Tambah stok kembali
                $detail->buku->increment('stok', $detail->jumlah);
            }
        });
    }
}
