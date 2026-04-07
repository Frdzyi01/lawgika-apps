<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateperaturanKBLIRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_kbli'      => 'required|string|max:20',
            'judul_peraturan'=> 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'tanggal_berlaku'=> 'required|date',
            'status'         => 'required|in:aktif,direvisi,dicabut',
            'file_dokumen'   => 'nullable|mimes:pdf|max:10240',
        ];
    }
}
