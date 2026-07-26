<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        // Mengambil data user yang sedang login beserta relasi guru dan kelasnya
        $user = User::with(['guru.profilGuru', 'guru.kelas'])->find(Auth::id());

        return view('walikelas.profil.index', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        // Eager load data guru dan profil untuk efisiensi di form edit
        $user->load('guru.profilGuru');
        return view('walikelas.profil.edit', compact('user'));
    }

    // --- UPDATE DATA (Nama, Email, No HP, Mapel) ---
    public function update(Request $request)
    {
        $user = auth()->user();
        $guru = $user->guru;

        $request->validate([
            'nama'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'no_hp' => 'nullable|string|max:15',
            'mapel' => 'nullable|string|max:100',
        ]);

        // Update tabel users (nama & email)
        $user->update([
            'nama'  => $request->nama,
            'email' => $request->email
        ]);

        // Update atau create data di profil_guru melalui relasi
        if ($guru) {
            $guru->profilGuru()->updateOrCreate(
                ['id_guru' => $guru->id_guru],
                [
                    'no_hp' => $request->no_hp, 
                    'mapel' => $request->mapel
                ]
            );
        }

        return redirect()->route('walikelas.profil.index')->with('success', 'Data profil berhasil diperbarui!');
    }

    // --- UPDATE FOTO PROFIL ---
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $guru = Guru::where('id_user', $user->id_user)->first();

        if ($request->hasFile('foto')) {
            // Hapus foto lama dari storage jika bukan foto default
            if ($user->photo && $user->photo != 'default.png') {
                Storage::delete('public/profil/' . $user->photo);
            }

            $file = $request->file('foto');
            $nama_file = time() . "_" . $user->username . "." . $file->getClientOriginalExtension();
            $file->storeAs('profil', $nama_file, 'public');
            
            // Sinkronisasi foto ke tabel users dan tabel guru
            $user->update(['photo' => $nama_file]);
            if ($guru) {
                $guru->update(['foto' => $nama_file]);
            }

            return redirect()->route('walikelas.profil.index')->with('success', 'Foto profil berhasil diganti!');
        }

        return back()->with('error', 'Gagal mengunggah foto.');
    }

    // --- UPDATE PASSWORD (Logika baru sesuai tombol di view) ---
    public function updatePassword(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'username' => 'required|max:255',
        'password' => 'nullable|min:8|confirmed',
    ], [
        'username.required' => 'Username wajib diisi.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'password.min' => 'Password minimal 8 karakter.',
    ]);

    // Cek apakah username sudah dipakai user lain
    $cek = User::where('username', $request->username)
        ->where('id_user', '!=', $user->id_user)
        ->exists();

    if ($cek) {
        return back()
            ->withErrors(['username' => 'Username sudah digunakan.'])
            ->withInput();
    }

    $data = [
        'username' => $request->username,
    ];

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    User::where('id_user', $user->id_user)->update($data);

    return redirect()
        ->route('walikelas.profil.index')
        ->with('success', 'Username dan password berhasil diperbarui!');
}
}