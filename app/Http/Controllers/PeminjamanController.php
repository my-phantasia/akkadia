<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Services\PeminjamanService;
use App\Http\Requests\StorePeminjamanRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class PeminjamanController extends Controller
{
    public function __construct(private readonly PeminjamanService $peminjamanService) {}

    // Req #1 (Create Transaksi)
    public function store(StorePeminjamanRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id(); // Petugas yang mencatat

            $this->peminjamanService->prosesPeminjaman($data, $data['buku_ids']);

            return redirect()->route('peminjaman.index')->with('success', 'Transaksi peminjaman berhasil.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Req #2 (Proses Pengembalian)
    public function kembalikan(Peminjaman $peminjaman)
    {
        try {
            $this->peminjamanService->prosesPengembalian($peminjaman);
            return back()->with('success', 'Buku berhasil dikembalikan dan stok telah diupdate.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
