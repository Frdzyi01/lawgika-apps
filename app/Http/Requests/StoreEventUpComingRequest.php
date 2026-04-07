<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventUpComingRequest extends FormRequest
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
            'status'          => 'required|boolean',
        ];
    }
}
