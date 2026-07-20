@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Anggota</h3>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAnggota">
        + Tambah Anggota
    </button>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Nama Anggota</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggotas as $anggota)
            <tr>
                <td>{{ $anggota->nama }}</td>
                <td>{{ $anggota->email }}</td>
                <td>{{ $anggota->telepon ?? '-' }}</td>
                <td>
                    {{-- Tombol Detail untuk memanggil Req #6 (Rekomendasi) --}}
                    <a href="{{ route('anggota.show', $anggota->id) }}" class="btn btn-sm btn-info text-white">Lihat Profil</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Belum ada data anggota.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $anggotas->links('pagination::bootstrap-5') }}</div>

<!-- Modal Tambah Anggota -->
<div class="modal fade" id="modalTambahAnggota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('anggota.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Anggota Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>
                <div class="mb-3">
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label>No. Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
