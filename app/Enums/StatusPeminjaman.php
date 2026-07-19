<?php

namespace App\Enums;

enum StatusPeminjaman: string
{
    case DIPINJAM = 'dipinjam';
    case DIKEMBALIKAN = 'dikembalikan';
}
