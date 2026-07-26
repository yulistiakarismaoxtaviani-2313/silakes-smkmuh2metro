<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // PENTING: Tambahkan ini
use Illuminate\Support\Facades\Storage; // PENTING: Tambahkan ini

class ProfilController extends Controller
{
    public function index()
    {
        $user = User::with('guru')->find(Auth::id());
        return view('guru.profil.index', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        $user->load('guru.profilGuru', 'guru.mapel');
        return view('guru.profil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $guru = $user->guru;

        $request->validate([
            'nama'  => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'mapel' => 'nullable|array',
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
        ]);

        $user->update(['nama' => $request->nama,'email' => $request->email,]);

        if ($guru) {
            $guru->profilGuru()->updateOrCreate(
                ['id_guru' => $guru->id_guru],
                ['no_hp' => $request->no_hp]
            );
            
            if ($request->has('mapel')) {
                $guru->mapel()->sync($request->mapel);
}
        }

        return redirect()->route('guru.profil.index')->with('success', 'Data profil berhasil diperbarui!');
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        // Cari data guru berdasarkan id_user
        $guru = Guru::where('id_user', $user->id_user)->first();

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika bukan default
            if ($user->photo && $user->photo != 'default.png') {
                Storage::delete('public/profil/' . $user->photo);
            }

            $file = $request->file('foto');
            $nama_file = time() . "_" . $user->username . "." . $file->getClientOriginalExtension();
            $file->storeAs('profil', $nama_file, 'public');
            
            // Update tabel User
            $user->update(['photo' => $nama_file]);
            
            // Update tabel Guru jika ada
            if ($guru) {
                $guru->update(['foto' => $nama_file]);
            }

            return redirect()->route('guru.profil.index')->with('success', 'Foto profil berhasil diganti!');
        }

        return back()->with('error', 'Gagal mengunggah foto.');
    }

    public function updatePassword(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'username' => 'required|max:255',
        'password' => 'nullable|min:8|confirmed',
    ]);

    // Cek username sudah dipakai user lain atau belum
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

    return redirect()->route('guru.profil.index')
        ->with('success', 'Username dan password berhasil diperbarui!');
}
}