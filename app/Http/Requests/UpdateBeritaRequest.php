<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBeritaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul'           => 'required|string|max:255',
            'kategori'        => 'required|string|in:Berita Hukum,Bisnis,Update Perizinan',
            'penulis'         => 'nullable|string|max:255',
            'jabatan_penulis' => 'required|string|max:255',
            'published_at'    => 'required|date',
            'gambar'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'excerpt'         => 'nullable|string|max:500',
            'konten'          => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required'           => 'Judul berita wajib diisi.',
            'kategori.required'        => 'Kategori wajib dipilih.',
            'kategori.in'              => 'Kategori tidak valid.',
            'jabatan_penulis.required' => 'Jabatan penulis wajib diisi.',
            'published_at.required'    => 'Tanggal publikasi wajib diisi.',
            'gambar.image'             => 'File harus berupa gambar.',
            'gambar.mimes'             => 'Format gambar harus jpg, jpeg, atau png.',
            'gambar.max'               => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
