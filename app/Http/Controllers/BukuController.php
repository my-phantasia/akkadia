<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Services\BukuService;
use App\Http\Requests\StoreBukuRequest;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Constructor Property Promotion (PHP 8+)
    public function __construct(private readonly BukuService $bukuService) {}

    // Req #1 (Read) & Req #4 (Search, Filter, Pagination)
    public function index(Request $request)
    {
        $bukus = Buku::with('kategori')
            ->search($request->query('search')) // Menggunakan scope dari Model
            ->filterKategori($request->query('kategori_id'))
            ->paginate(10)
            ->withQueryString(); // Mempertahankan query string saat pagination

        return view('buku.index', compact('bukus'));
    }

    // Req #1 (Create) & Req #3 (Upload)
    public function store(StoreBukuRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            $data['cover_path'] = $this->bukuService->uploadCover($request->file('cover'));
        }

        Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }
}
