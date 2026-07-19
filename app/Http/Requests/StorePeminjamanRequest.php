<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeminjamanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'anggota_id' => ['required', 'exists:anggotas,id'],
            'tanggal_pinjam' => ['required', 'date'],
            // Req #5: Pastikan ID buku yang dikirim ada di DB
            'buku_ids' => ['required', 'array', 'min:1'],
            'buku_ids.*' => ['exists:bukus,id'],
        ];
    }
}
