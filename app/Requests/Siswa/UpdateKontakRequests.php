<?php

namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateKontakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk Kontak (No HP & Email).
     */
    public function rules(): array
    {
        return [
            'no_hp' => 'required|numeric|digits_between:10,15',
            // Email harus unik di tabel users, kecuali untuk ID user yang sedang login sekarang
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ];
    }

    public function messages(): array
    {
        return [
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.numeric'  => 'Nomor HP harus berupa angka.',
            'no_hp.digits_between' => 'Nomor HP minimal 10 dan maksimal 15 digit.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email ini sudah digunakan oleh orang lain.',
        ];
    }
}