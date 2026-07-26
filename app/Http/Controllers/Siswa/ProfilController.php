<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Siswa\UpdatePribadiRequest;
use App\Http\Requests\Siswa\UpdateKontakRequest;
use App\Http\Requests\Siswa\UpdateOrtuRequest;
use App\Models\ProgramKeahlian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

// TAMBAHKAN DUA BARIS INI:
use App\Models\User;
use App\Models\Siswa;

class ProfilController extends Controller
{
    /**
     * Tampilan Utama (Lihat Profil)
     */
    public function index()
{
    // Menarik User -> Siswa -> Profil Siswa sekaligus (Eager Loading)
    $user = \App\Models\User::with('siswa.profil', 'siswa.profil.programKeahlian', 'siswa.tahunAjaran')->where('id_user', Auth::id())->first();

    return view('siswa.profil.index', compact('user'));
}
    //Fitur Cetak/Download Kartu Siswa
    public function downloadKartu()
    {
        $user = Auth::user();
        // Mengarahkan ke file resources/views/siswa/profil/kartu.blade.php
        return view('siswa.profil.kartu', compact('user'));
    }

    //Tampilan Ganti Foto Profil (Tampilan Kartu Biru)
     
    public function editFoto()
    {
        $user = Auth::user();
        return view('siswa.profil.edit-foto', compact('user'));
    }

    //Proses Upload Foto Profil Baru
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $siswa = Siswa::where('id_user', $user->id_user)->first();

        if ($request->hasFile('foto')) {
            // 1. Hapus foto lama
            if ($user->photo && $user->photo !== 'default.png') {
                Storage::disk('public')->delete('profil/' . $user->photo);
            }

            // 2. Upload foto baru
            $file = $request->file('foto');
            $nama_file = time() . "_" . $user->username . "." . $file->getClientOriginalExtension();
            $file->storeAs('profil', $nama_file, 'public');

            // 3. Simpan ke database (User & Siswa)
            $user->update(['photo' => $nama_file]);
            $siswa->update(['foto' => $nama_file]);

            return back()->with('success', 'Foto profil berhasil diperbarui!');
        }

        return redirect()->route('siswa.profil.index')->with('success', 'Foto profil berhasil diperbarui!');
    }

    //Form Edit & Update Data Pribadi
    public function editPribadi()
    {
        $user = \App\Models\User::with('siswa.profil')->where('id_user', Auth::id())->first();
         return view('siswa.profil.edit-Pribadi', compact('user'));
    }

   // Proses Simpan Perubahan
  public function updatePribadi(Request $request)
{
    $user = \App\Models\User::find(Auth::id());
    $siswa = $user->siswa;

    // 1. Ambil data profil yang sudah ada (kalau ada)
    $profil = $siswa->profil; 

    // 2. Update Nama & Jenis Kelamin (ini sudah aman)
    $user->update(['nama' => $request->nama]);
    $siswa->update(['jenis_kelamin' => $request->jenis_kelamin]);

    // 3. LOGIKA PENYELAMAT:
    if ($profil) {
        // Kalau datanya SUDAH ADA, tinggal update yang ada di form pribadi saja
        $profil->update([
            'tempat_lahir'  => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama'         => $request->agama,
            'alamat_siswa'  => $request->alamat_siswa,
        ]);
    } else {
        // Kalau datanya BELUM ADA (Baris baru), kita terpaksa kasih nilai default 
        // supaya MySQL nggak demo masak (eror).
        $siswa->profil()->create([
            'id_siswa'            => $siswa->id_siswa,
            'id_program_keahlian' => $siswa->id_program_keahlian,
            'tempat_lahir'        => $request->tempat_lahir,
            'tanggal_lahir'       => $request->tanggal_lahir,
            'agama'               => $request->agama,
            'alamat_siswa'        => $request->alamat_siswa,
        ]);
    }

    return redirect()->route('siswa.profil.index')->with('success', 'Data pribadi berhasil diperbarui!');
}

    // Form Edit & Update Kontak
    
    public function editKontak()
    {
       $user = \App\Models\User::with('siswa.profil')->where('id_user', Auth::id())->first();
       return view('siswa.profil.edit-kontak', compact('user'));
    }

    public function updateKontak(Request $request)
    {
        {
    $user = \App\Models\User::find(Auth::id());
    $profil = $user->siswa->profil;

    $request->validate([
        'no_hp' => 'required|numeric|digits_between:10,15',
        'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
    ]);

    // 1. Update Email di tabel Users
    $user->update([
        'email' => $request->email,
    ]);

    // 2. Update No HP di tabel Profil Siswa
    if ($profil) {
        $profil->update([
            'no_hp' => $request->no_hp,
        ]);
    }
        return redirect()->route('siswa.profil.index')->with('success', 'Kontak berhasil diperbarui!');
    }
    }
    /**
     * Form Edit & Update Orang Tua
     */
    public function editOrtu()
    {
        // Mengambil data user beserta relasi hingga ke profil
    $user = \App\Models\User::with('siswa.profil')->where('id_user', Auth::id())->first();
    return view('siswa.profil.edit-ortu', compact('user'));
    }

    public function updateOrtu(Request $request)
    {
       $user = \App\Models\User::find(Auth::id());
    $profil = $user->siswa->profil;

    $request->validate([
        'nama_ayah'      => 'nullable|string|max:255',
        'pekerjaan_ayah' => 'nullable|string|max:255',
        'nama_ibu'       => 'nullable|string|max:255',
        'pekerjaan_ibu'  => 'nullable|string|max:255',
    ]);

    if ($profil) {
        $profil->update([
            'nama_ayah'      => $request->nama_ayah,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'nama_ibu'       => $request->nama_ibu,
            'pekerjaan_ibu'  => $request->pekerjaan_ibu,
        ]);
    }
        return redirect()->route('siswa.profil.index')->with('success', 'Data orang tua berhasil diperbarui!');
    }

    /**
     * Form Edit Password
     */
    public function editPassword()
    {
        $user = Auth::user();
        return view('siswa.profil.edit-password', compact('user'));
    }

    /**
     * Proses Update Password
     */
    public function updatePassword(Request $request)
{
    $user = User::find(Auth::id());

    $request->validate([
        'username' => 'required|max:255',
        'current_password' => 'required',
        'password' => 'nullable|min:8|confirmed',
    ], [
        'username.required' => 'Username wajib diisi.',
        'current_password.required' => 'Password lama wajib diisi.',
        'password.min' => 'Password minimal harus 8 karakter.',
        'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
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

    // Cek password lama
    if (!Hash::check($request->current_password, $user->password)) {
        return back()
            ->with('error', 'Kata sandi saat ini tidak sesuai!')
            ->withInput();
    }

    // Update username
    $user->username = $request->username;

    // Update password jika diisi
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()
        ->route('siswa.profil.index')
        ->with('success', 'Username dan kata sandi berhasil diperbarui!');
}

    public function editProgram()
{
    $user = User::with('siswa.profil')
        ->where('id_user', Auth::id())
        ->first();

    $programKeahlian = ProgramKeahlian::all();

    return view('siswa.profil.edit-program', compact(
        'user',
        'programKeahlian'
    ));
}

public function updateProgram(Request $request)
{
    $request->validate([
        'id_program_keahlian' => 'required',
        'konsentrasi_keahlian' => 'required',
    ]);

    $profil = Auth::user()->siswa->profil;

    $profil->update([
        'id_program_keahlian' => $request->id_program_keahlian,
        'konsentrasi_keahlian' => $request->konsentrasi_keahlian,
    ]);

    return redirect()
        ->route('siswa.profil.index')
        ->with('success', 'Program keahlian berhasil diperbarui!');
}
}