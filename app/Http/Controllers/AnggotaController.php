<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Services\RekomendasiService;

class AnggotaController extends Controller
{
    public function __construct(private readonly RekomendasiService $rekomendasiService) {}

    // Req #6: Menampilkan rekomendasi di profil/dashboard anggota
    public function show(Anggota $anggota)
    {
        $anggota->load('peminjamans.detailPeminjamans.buku');

        // Memanggil service rekomendasi
        $rekomendasiBuku = $this->rekomendasiService->getRekomendasiUntukAnggota($anggota->id);

        return view('anggota.show', compact('anggota', 'rekomendasiBuku'));
    }
}
