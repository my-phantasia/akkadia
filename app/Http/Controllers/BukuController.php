<?php
// app/Http/Controllers/BukuController.php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Services\BukuService;
use App\Http\Requests\UpdateBukuRequest; // Buat request baru untuk update jika rules berbeda

class BukuController extends Controller
{
    public function __construct(private readonly BukuService $bukuService) {}

    // ... method index & store yang kemarin ...

    // Req #1: Update Buku
    public function update(UpdateBukuRequest $request, Buku $buku)
    {
        $this->bukuService->updateBuku(
            $buku,
            $request->validated(),
            $request->file('cover')
        );

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    // Req #1 & #3: Delete Buku beserta covernya
    public function destroy(Buku $buku)
    {
        // Hapus file fisiknya via service
        $this->bukuService->deleteCover($buku->cover_path);

        // Hapus data di DB
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
