<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anggota extends Model
{
    protected $fillable = ['nama', 'email', 'telepon'];

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }
}
