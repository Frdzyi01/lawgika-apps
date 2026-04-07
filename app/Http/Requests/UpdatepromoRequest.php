<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatepromoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'gambar'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tipe_diskon'     => 'required|in:persen,nominal',
            'diskon'          => 'required|numeric|min:0',
            'tanggal_mulai'   => 'required|date',
            'tanggal_berakhir'=> 'required|date|after_or_equal:tanggal_mulai',
            'status'          => 'required|boolean',
        ];
    }
}
