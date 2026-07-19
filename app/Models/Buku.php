<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    protected $fillable = ['kategori_id', 'judul', 'penulis', 'stok', 'cover_path'];

    // Req #8: Inverse dari Kategori memiliki banyak buku
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    // Req #11: Buku memiliki banyak detail peminjaman
    public function detailPeminjamans(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    // Req #4: Scope untuk Search & Filter
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn ($q) =>
            $q->where('judul', 'like', "%{$term}%")
              ->orWhere('penulis', 'like', "%{$term}%")
        );
    }

    public function scopeFilterKategori(Builder $query, ?int $kategoriId): Builder
    {
        return $query->when($kategoriId, fn ($q) => $q->where('kategori_id', $kategoriId));
    }
}
