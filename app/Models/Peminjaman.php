<?php

namespace App\Models;

use App\Enums\StatusPeminjaman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    protected $fillable = ['anggota_id', 'user_id', 'tanggal_pinjam', 'tanggal_kembali', 'status'];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'status' => StatusPeminjaman::class, // Casting ke Enum
    ];

    // Req #9: Inverse dari Anggota memiliki banyak peminjaman
    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    // Req #10: Peminjaman memiliki banyak detail peminjaman
    public function detailPeminjamans(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }
}
