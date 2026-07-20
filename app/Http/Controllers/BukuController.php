<?php
// app/Http/Controllers/BukuController.php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Services\BukuService;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Inject service untuk handle upload & update file cover
    public function __construct(private readonly BukuService $bukuService) {}

    // Req #1 (Read) & Req #4 (Search, Filter Kategori, Pagination)
    public function index(Request $request)
    {
        $bukus = Buku::with('kategori')
            ->search($request->query('search')) // Menggunakan scope dari model Buku
            ->filterKategori($request->query('kategori_id'))
            ->paginate(10)
            ->withQueryString();

        // Mengatasi poin Anda: Ambil semua kategori untuk dropdown filter
        $kategoris = Kategori::orderBy('nama', 'asc')->get();

        // Lempar data bukus dan kategoris ke view
        return view('buku.index', compact('bukus', 'kategoris'));
    }

    // Req #1 (Create) & Req #3 (Upload Cover)
    public function store(StoreBukuRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            $data['cover_path'] = $this->bukuService->uploadCover($request->file('cover'));
        }

        Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    // Req #1 (Update) & Req #3 (Update Cover via Service)
    public function update(UpdateBukuRequest $request, Buku $buku)
    {
        $this->bukuService->updateBuku(
            $buku,
            $request->validated(),
            $request->file('cover')
        );

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    // Req #1 (Delete) & Req #3 (Hapus file fisik cover)
    public function destroy(Buku $buku)
    {
        $this->bukuService->deleteCover($buku->cover_path);
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
