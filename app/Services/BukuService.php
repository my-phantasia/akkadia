<?php

// app/Services/BukuService.php

namespace App\Services;

use App\Models\Buku;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BukuService
{
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

    // Tambahan logika Update dengan SoC
    public function updateBuku(Buku $buku, array $data, ?UploadedFile $newCover = null): Buku
    {
        if ($newCover) {
            // Hapus cover lama terlebih dahulu
            $this->deleteCover($buku->cover_path);
            // Upload cover baru
            $data['cover_path'] = $this->uploadCover($newCover);
        }

        $buku->update($data);
        return $buku;
    }
}
