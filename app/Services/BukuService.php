<?php

namespace App\Services;

use App\Models\Buku;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BukuService
{
    // Req #3: Upload cover buku
    public function uploadCover(UploadedFile $file): string
    {
        return $file->store('covers', 'public');
    }

    public function deleteCover(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
