<?php

namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePribadiRequest extends FormRequest
{
    /**
     * Izinkan semua siswa yang login untuk melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk Data Pribadi (Nama, TTL, Agama, Alamat).
     */
    public function rules(): array
    {
        return [
            'nama'           => 'required|string|max:255',
            'tempat_lahir'   => 'required|string|max:100',
            'tanggal_lahir'  => 'required|date',
            'agama'          => 'required|in:Islam,Kristen,Katolik,Hindu,Budha,Khonghucu',
            'alamat'         => 'required|string|min:10',
        ];
    }

    /**
     * Custom pesan error dalam Bahasa Indonesia (Opsional).
     */
    public function messages(): array
    {
        return [
            'nama.required'          => 'Nama lengkap wajib diisi.',
            'tempat_lahir.required'  => 'Tempat lahir tidak boleh kosong.',
            'tanggal_lahir.required' => 'Tanggal lahir harus berupa format tanggal.',
            'agama.required'         => 'Pilih salah satu agama yang tersedia.',
            'alamat.min'             => 'Alamat minimal harus 10 karakter.',
        ];
    }
}