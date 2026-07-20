<?php

// app/Http/Controllers/PeminjamanController.php
namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Services\PeminjamanService;
use App\Http\Requests\StorePeminjamanRequest;
use Exception;

class PeminjamanController extends Controller
{
    // Inject Service via Constructor
    public function __construct(private readonly PeminjamanService $peminjamanService) {}

    // READ
    public function index()
    {
        $peminjamans = Peminjaman::with(['anggota', 'detailPeminjamans.buku'])
            ->latest()
            ->paginate(10);

        return view('peminjaman.index', compact('peminjamans'));
    }

    // CREATE
    public function store(StorePeminjamanRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id() ?? 1; // Fallback ID 1 jika belum set auth

            $this->peminjamanService->prosesPeminjaman($data, $data['buku_ids']);

            return redirect()->route('peminjaman.index')->with('success', 'Transaksi peminjaman berhasil.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // UPDATE STATUS (Pengembalian)
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
