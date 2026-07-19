<?php

namespace App\Services;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Collection;

class RekomendasiService
{
    // Req #6: Rekomendasi buku berdasarkan riwayat peminjaman anggota
    public function getRekomendasiUntukAnggota(int $anggotaId): Collection
    {
        // Cari kategori buku yang paling sering dipinjam oleh anggota ini
        $kategoriFavorit = Peminjaman::where('anggota_id', $anggotaId)
            ->join('detail_peminjamans', 'peminjamans.id', '=', 'detail_peminjamans.peminjaman_id')
            ->join('bukus', 'detail_peminjamans.buku_id', '=', 'bukus.id')
            ->select('bukus.kategori_id')
            ->groupBy('bukus.kategori_id')
            ->orderByRaw('COUNT(bukus.id) DESC')
            ->first();

        if (!$kategoriFavorit) {
            // Fallback: Rekomendasikan buku terbaru jika belum ada riwayat
            return Buku::latest()->take(5)->get();
        }

        // Rekomendasikan buku dari kategori favorit yang stoknya tersedia
        return Buku::where('kategori_id', $kategoriFavorit->kategori_id)
            ->where('stok', '>', 0)
            ->inRandomOrder()
            ->take(5)
            ->get();
    }
}
