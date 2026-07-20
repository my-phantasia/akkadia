@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data Peminjaman</h3>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPeminjaman">
        + Buat Peminjaman Baru
    </button>
</div>

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Tanggal Pinjam</th>
                <th>Anggota</th>
                <th>Buku (Jumlah)</th>
                <th>Status</th>
                <th>Aksi Pengembalian</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $pinjam)
            <tr>
                <td>{{ $pinjam->tanggal_pinjam->format('d M Y') }}</td>
                <td>{{ $pinjam->anggota->nama }}</td>
                <td>
                    <!-- Req #10 & #11: Detail Peminjaman -->
                    <ul class="mb-0 ps-3">
                        @foreach($pinjam->detailPeminjamans as $detail)
                            <li>{{ $detail->buku->judul }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    @if($pinjam->status->value === 'dipinjam')
                        <span class="badge bg-warning text-dark">Dipinjam</span>
                    @else
                        <span class="badge bg-success">Dikembalikan <br> <small>({{ $pinjam->tanggal_kembali->format('d M Y') }})</small></span>
                    @endif
                </td>
                <td>
                    <!-- Req #2: Tombol Pengembalian (Update Stok) -->
                    @if($pinjam->status->value === 'dipinjam')
                        <form action="{{ route('peminjaman.kembalikan', $pinjam->id) }}" method="POST" onsubmit="return confirm('Apakah buku ini sudah dikembalikan?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success">Proses Kembalikan</button>
                        </form>
                    @else
                        <span class="text-muted small">Selesai</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada transaksi peminjaman.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $peminjamans->links('pagination::bootstrap-5') }}
</div>

<!-- Modal Tambah Peminjaman -->
<div class="modal fade" id="modalTambahPeminjaman" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('peminjaman.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Transaksi Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Pilih Anggota <span class="text-danger">*</span></label>
                    <select name="anggota_id" class="form-select" required>
                        <option value="">-- Pilih Anggota --</option>
                        @foreach($anggotas as $anggota)
                            <option value="{{ $anggota->id }}">{{ $anggota->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Pilih Buku <span class="text-danger">*</span></label>
                    <!-- Select multiple untuk meminjam > 1 buku. Tahan tombol CTRL/CMD untuk pilih banyak -->
                    <select name="buku_ids[]" class="form-select" multiple required style="height: 120px;">
                        @foreach($bukus as $buku)
                            <option value="{{ $buku->id }}">{{ $buku->judul }} (Sisa: {{ $buku->stok }})</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Tahan tombol CTRL/CMD untuk memilih lebih dari satu buku.</small>
                </div>

                <div class="mb-3">
                    <label>Tanggal Pinjam <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pinjam" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Proses Peminjaman</button>
            </div>
        </form>
    </div>
</div>
@endsection
