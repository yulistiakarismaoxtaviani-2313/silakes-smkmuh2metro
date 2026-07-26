<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    /**
     * Menampilkan halaman profil.
     */
    public function index()
    {
        return view('admin.profil.index', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update Informasi Profil & Foto.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input (Ganti 'name' jadi 'nama' agar sinkron dengan Blade)
        $request->validate([
            'nama'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id_user . ',id_user'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // Maksimal 2MB
        ]);

        // 2. Logika Upload Foto
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo && Storage::disk('public')->exists('profil/' . $user->photo)) {
                Storage::disk('public')->delete('profil/' . $user->photo);
            }

            $file = $request->file('photo');
            // Menggunakan id_user untuk penamaan file
            $nama_file = time() . '_' . $user->id_user . '.' . $file->getClientOriginalExtension();
            
            // Simpan ke storage/app/public/profil
            $file->storeAs('profil', $nama_file, 'public');
            
            // Update nama file di database
            $user->photo = $nama_file;
        }

        // 3. Update data lainnya (Pastikan menggunakan $request->nama)
        $user->nama = $request->nama; 
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Update Password.
     */
    public function updatePassword(Request $request)
    {
        // Validasi Password
        $request->validate([
            'current_password' => ['required', 'current_password'], // Cek password lama
            'password' => ['required', Password::defaults(), 'confirmed'], // Konfirmasi password baru
        ]);

        // Simpan Password Baru
        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Keamanan akun berhasil diperbarui! Password telah diganti.');
    }
}