@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Buku</h3>
    <!-- Tombol ini sekarang memanggil modal dengan id #modalTambahBuku -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBuku">
        + Tambah Buku
    </button>
</div>

{{-- Tambahkan blok ini untuk menampilkan error validasi jika form gagal disubmit --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Req #4: Fitur Search dan Filter Kategori -->
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('buku.index') }}" method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari Judul / Penulis..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="kategori_id" class="form-select">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary w-100">Cari & Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Cover</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Kategori</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bukus as $buku)
            <tr>
                <td>
                    <!-- Req #3: Menampilkan Cover Buku -->
                    @if($buku->cover_path)
                        <img src="{{ Storage::url($buku->cover_path) }}" alt="Cover" width="50" class="img-thumbnail">
                    @else
                        <span class="text-muted small">No Cover</span>
                    @endif
                </td>
                <td>{{ $buku->judul }}</td>
                <td>{{ $buku->penulis }}</td>
                <td>{{ $buku->kategori->nama ?? '-' }}</td>
                <td>
                    <span class="badge bg-{{ $buku->stok > 0 ? 'success' : 'danger' }}">
                        {{ $buku->stok }} Tersedia
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data buku.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Req #4: Pagination -->
<div class="mt-3">
    {{ $bukus->links('pagination::bootstrap-5') }}
</div>

<!-- Modal Tambah Buku (Menyelesaikan Req #1 & #3) -->
<div class="modal fade" id="modalTambahBuku" tabindex="-1" aria-labelledby="modalTambahBukuLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- PENTING: enctype="multipart/form-data" wajib ada untuk upload file/gambar -->
            <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahBukuLabel">Tambah Buku Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Penulis <span class="text-danger">*</span></label>
                        <input type="text" name="penulis" class="form-control" value="{{ old('penulis') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control" min="0" value="{{ old('stok') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cover Buku (Opsional)</label>
                        <input type="file" name="cover" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <small class="text-muted">Format: JPG, JPEG, PNG (Maksimal 2MB)</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Buku</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
