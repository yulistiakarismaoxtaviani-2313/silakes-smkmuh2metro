<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLogin() {
        return view('auth.login');
    }

    /**
     * Proses Login dengan deteksi otomatis Wali Kelas
     */
    public function login(Request $request) {
        $request->validate([
            'login_identity' => 'required',
            'password' => 'required'
        ]);

        $loginValue = $request->login_identity;
$remember = $request->has('remember');

$loggedIn = Auth::attempt([
    'username' => $loginValue,
    'password' => $request->password
], $remember);

if (!$loggedIn) {
    $loggedIn = Auth::attempt([
        'email' => $loginValue,
        'password' => $request->password
    ], $remember);
}

if (!$loggedIn) {
    $siswa = Siswa::where('nis', $loginValue)->first();

    if ($siswa) {
        $user = User::find($siswa->id_user);

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, $remember);
            $loggedIn = true;
        }
    }
}

if (!$loggedIn) {
    $guru = Guru::where('nip', $loginValue)->first();

    if ($guru) {
        $user = User::find($guru->id_user);

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, $remember);
            $loggedIn = true;
        }
    }
}

if ($loggedIn) {
    $request->session()->regenerate();

    $user = Auth::user();
    $user->update(['terakhir_login' => now()]);

    // lanjutkan redirect dashboard seperti yang sudah ada
            
            $role = strtolower($user->role);

            // Khusus Admin
if (in_array($role, ['admin_presensi', 'admin_prestasi'])) {
    return redirect()->route('admin.dashboard');
}

// Tetap pakai logika lama untuk guru & wali kelas
if ($role === 'guru' || $role === 'walikelas') {
    $guru = Guru::where('id_user', $user->id_user)->first();
    $isWali = $guru
        ? Kelas::where('id_guru', $guru->id_guru)->exists()
        : false;

    if ($isWali) {
        return redirect()->route('walikelas.dashboard');
    }

    return redirect()->route('guru.dashboard');
}

// Siswa
if ($role === 'siswa') {
    return redirect()->route('siswa.dashboard');
}
        }

        return back()->withInput()->with('loginError', 'Email/Username atau password salah!');
    }

    /**
     * Menampilkan halaman register
     */
    public function showRegister() {
        $data_kelas = Kelas::all();
        $data_prodi = DB::table('program_keahlian')->get(); 
        return view('auth.register', compact('data_kelas', 'data_prodi'));
    }

    /**
     * Proses Registrasi dengan Upload Foto
     */
    public function register(Request $request) {

        $request->validate([
            'role' => 'required|in:siswa,guru',
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'nik' => 'nullable|digits:16',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'jenis_kelamin' => 'required|in:L,P',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi foto
            'foto'=> 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_program_keahlian' => $request->role === 'siswa' ? 'required|exists:program_keahlian,id_program_keahlian' : 'nullable',
            'konsentrasi_keahlian' => $request->role === 'siswa'? 'required': 'nullable',
            'id_kelas' => ($request->role === 'siswa' || $request->ask_wali === 'ya') ? 'required' : 'nullable',
        ]);

        // 2. Ambil tahun ajaran aktif (di sini saja)
    $tahunAktif = \App\Models\TahunAjaran::where('status', 'aktif')->first();

    if (!$tahunAktif) {
        return back()->withErrors(['error' => 'Tahun ajaran aktif tidak ditemukan.']);
    }

        // Definisikan variabel di luar agar bisa diakses di catch jika error
    $nama_file = 'default.png';

        try {
            DB::transaction(function () use ($request, &$nama_file, $tahunAktif) { // Tambahkan &$nama_file agar bisa diakses di catch                
                // --- LOGIKA UPLOAD FOTO ---
                $nama_file = 'default.png';
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    // Format: 20240520_username.jpg
                    $nama_file = time() . "_" . $request->username . "." . $file->getClientOriginalExtension();
            $file->storeAs('profil', $nama_file, 'public');
                }

                // 1. Simpan ke tabel Users
                $user = User::create([
                    'nama' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                    'photo' => $nama_file // Simpan nama file di tabel users
                ]);

                $newUserId = $user->id_user; 

                // 2. Jika Registrasi sebagai Siswa
                if ($request->role === 'siswa') {
                    $siswa = Siswa::create([
                        'id_user' => $newUserId,
                        'nis' => $request->nis ?? $request->username,
                        'jenis_kelamin' => $request->jenis_kelamin,
                        'status' => 'aktif',
                        'id_kelas' => $request->id_kelas,
                        'foto' => $nama_file, // Sinkronkan foto ke tabel siswa jika kolomnya ada
                        'id_tahun_ajaran' => $tahunAktif->id_tahun_ajaran
                    ]);

                    DB::table('profil_siswa')->insert([
                        'id_siswa' => $siswa->id_siswa,
                        'id_program_keahlian' => $request->id_program_keahlian,
                        'nik' => $request->nik ?? '-',
                        'tempat_lahir' => $request->tempat_lahir ?? '-',
                        'tanggal_lahir' => $request->tanggal_lahir ?? now()->format('Y-m-d'),
                        'alamat_siswa' => $request->alamat ?? '-',
                        'agama' => $request->agama ?? '-',
                        'no_hp' => $request->no_hp ?? '-',
                        'nama_ayah' => $request->nama_ayah ?? '-',
                        'nama_ibu' => $request->nama_ibu ?? '-',
                        'pekerjaan_ayah' => $request->pekerjaan_ayah ?? '-',
                        'pekerjaan_ibu' => $request->pekerjaan_ibu ?? '-',
                        'status_akun' => 'aktif',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } 
                
                // 3. Jika Registrasi sebagai Guru
                else if ($request->role === 'guru') {
                    $guru = Guru::create([
                        'id_user' => $newUserId,
                        'nip' => $request->nip ?? $request->username,
                        'jenis_kelamin' => $request->jenis_kelamin,
                        'status' => 'aktif',
                        'foto' => $nama_file,
                    ]);

                    DB::table('profil_guru')->insert([
                        'id_guru' => $guru->id_guru,
                        'no_hp' => $request->no_hp ?? '-',
                        'status_akun' => 'aktif',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // --- INTEGRASI MULTIPLE MAPEL ---
    if ($request->has('mapel')) {
        // $request->mapel adalah array dari select multiple name="mapel[]"
        $guru->mapel()->attach($request->mapel);
    }

                    // 4. Logika Jika Guru mendaftar sebagai Wali Kelas
                    if ($request->ask_wali === 'ya' && $request->filled('id_kelas')) {
                        DB::table('kelas')
                            ->where('id_kelas', $request->id_kelas)
                            ->update([
                                'id_guru' => $guru->id_guru,
                                'updated_at' => now()
                            ]);
                    }
                }
            });

            return redirect('/login')->with('success', 'Registrasi Berhasil! Silakan Login.');

        } catch (\Exception $e) {
            // Hapus foto jika database gagal menyimpan data (Rollback manual untuk file)
            if (isset($nama_file) && $nama_file !== 'default.png') {
                Storage::delete('public/profil/' . $nama_file);
            }

            dd([
                'Pesan Error' => $e->getMessage(),
                'Baris' => $e->getLine(),
                'Detail' => 'Terjadi kesalahan saat menyimpan data.'
            ]);
        }
    }

    /**
     * Proses Logout
     */
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
    /**
 * Menampilkan halaman lupa password
 */
public function showForgotPassword()
{
    return view('auth.forgot-password');
}

/**
 * Proses lupa password
 */
public function forgotPassword(Request $request)
{
    $request->validate([
        'identity' => 'required'
    ]);

    $identity = $request->identity;

    // Cari berdasarkan username atau email
    $user = User::where('username', $identity)
                ->orWhere('email', $identity)
                ->first();

    // Jika belum ketemu, cari berdasarkan NIS
    if (!$user) {
        $siswa = Siswa::where('nis', $identity)->first();

        if ($siswa) {
            $user = User::find($siswa->id_user);
        }
    }

    // Jika belum ketemu, cari berdasarkan NIP/NBM guru
    if (!$user) {
        $guru = Guru::where('nip', $identity)->first();

        if ($guru) {
            $user = User::find($guru->id_user);
        }
    }

    if (!$user) {
        return back()->with('error', 'Akun tidak ditemukan.');
    }

    return back()->with(
        'success',
        'Akun ditemukan. Silakan hubungi Admin untuk melakukan reset password.'
    );
}
}