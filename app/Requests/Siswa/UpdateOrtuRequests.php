<?php

namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrtuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk Data Orang Tua/Wali sesuai mockup.
     */
    public function rules(): array
    {
        return [
            'nama_ayah'      => 'required|string|max:255',
            'pekerjaan_ayah' => 'required|string',
            'nama_ibu'       => 'required|string|max:255',
            'pekerjaan_ibu'  => 'required|string',
            'alamat_ortu'    => 'required|string|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_ayah.required'      => 'Nama ayah wajib diisi.',
            'nama_ibu.required'       => 'Nama ibu wajib diisi.',
            'pekerjaan_ayah.required' => 'Silakan pilih atau isi pekerjaan ayah.',
            'pekerjaan_ibu.required'  => 'Silakan pilih atau isi pekerjaan ibu.',
            'alamat_ortu.required'    => 'Alamat orang tua wajib diisi lengkap.',
        ];
    }
}