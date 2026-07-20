@extends('layouts.app')

@section('content')
<div class="mb-3">
    <a href="{{ route('anggota.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali ke Daftar Anggota</a>
</div>

<div class="row">
    <!-- Kolom Kiri: Profil & Rekomendasi Buku -->
    <div class="col-md-4">
        <!-- Card Profil Anggota -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Profil Anggota</h5>
            </div>
            <div class="card-body">
                <h3 class="h5 mb-1 text-primary">{{ $anggota->nama }}</h3>
                <p class="text-muted small mb-3">ID Anggota: #{{ $anggota->id }}</p>
                <hr>
                <div class="mb-2">
                    <small class="text-muted d-block">Email</small>
                    <strong>{{ $anggota->email }}</strong>
                </div>
                <div class="mb-0">
                    <small class="text-muted d-block">No. Telepon</small>
                    <strong>{{ $anggota->telepon ?? '-' }}</strong>
                </div>
            </div>
        </div>

        <!-- Req #6: Card Rekomendasi Buku -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">💡 Rekomendasi Buku Untuk Anda</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($rekomendasiBuku as $rekomendasi)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="me-2">
                                    <h6 class="mb-1 fw-bold text-dark">{{ $rekomendasi->judul }}</h6>
                                    <small class="text-muted d-block">Penulis: {{ $rekomendasi->penulis }}</small>
                                    <span class="badge bg-secondary btn-sm mt-1">{{ $rekomendasi->kategori->nama ?? 'Umum' }}</span>
                                </div>
                                @if($rekomendasi->cover_path)
                                    <img src="{{ Storage::url($rekomendasi->cover_path) }}" alt="Cover" width="40" class="img-thumbnail flex-shrink-0">
                                @endif
                            </div>
                            <div class="mt-2 d-flex justify-content-between align-items-center">
                                <small class="text-success fw-bold">Stok: {{ $rekomendasi->stok }} tersedia</small>
                            </div>
                        </div>
                    @empty
                        <div class="p-3 text-center text-muted small">
                            Belum ada rekomendasi spesifik. Tambah riwayat peminjaman terlebih dahulu.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Req #9 Riwayat Peminjaman Buku -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">📜 Riwayat Transaksi Peminjaman</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID Pinjam</th>
                                <th>Tanggal Pinjam</th>
                                <th>Buku yang Dipinjam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggota->peminjamans as $pinjam)
                                <tr>
                                    <td><strong>#{{ $pinjam->id }}</strong></td>
                                    <td>{{ $pinjam->tanggal_pinjam->format('d M Y') }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0 ps-0">
                                            @foreach($pinjam->detailPeminjamans as $detail)
                                                <li class="text-dark small">
                                                    📖 {{ $detail->buku->judul ?? '[Buku Dihapus]' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        @if($pinjam->status->value === 'dipinjam')
                                            <span class="badge bg-warning text-dark">Dipinjam</span>
                                        @else
                                            <span class="badge bg-success">
                                                Dikembalikan <br>
                                                <small style="font-size: 0.75rem;">({{ $pinjam->tanggal_kembali?->format('d M Y') }})</small>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center p-4 text-muted">
                                        Anggota ini belum pernah melakukan transaksi peminjaman buku.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
