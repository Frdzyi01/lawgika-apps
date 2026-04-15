<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventUpComingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_event'      => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'banner'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'lokasi'          => 'required|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_event'     => 'nullable|string|max:255',
            'kapasitas'       => 'nullable|integer|min:0',
            'narasumber'      => 'nullable|string|max:500',
            'harga'           => 'nullable|integer|min:0',
            'tipe_event'      => 'nullable|in:gratis,berbayar',
            'status'          => 'required|boolean',
        ];
    }
}
