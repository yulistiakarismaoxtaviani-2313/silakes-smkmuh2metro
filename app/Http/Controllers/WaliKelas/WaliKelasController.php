<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaliKelasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 1. Cari data guru berdasarkan id_user yang sedang login
        $guru = Guru::where('id_user', $user->id_user)->first();

        if (!$guru) {
            return abort(403, "Data Guru tidak ditemukan.");
        }

        // 2. Cari kelas yang diampu oleh guru tersebut
        $kelas = Kelas::where('id_guru', $guru->id_guru)->first();

        if (!$kelas) {
            return abort(403, "Anda tidak terdaftar sebagai Wali Kelas di kelas manapun.");
        }

        // 3. Inisialisasi Query untuk Siswa di kelas tersebut
        $query = Siswa::with(['user', 'profil'])
                      ->where('id_kelas', $kelas->id_kelas);

        // --- LOGIKA FILTER ---
        
        // Filter Berdasarkan Jenis Kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter Berdasarkan Status (Aktif/Nonaktif)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // --- LOGIKA SEARCH ---
        
        // Search Nama atau NIS
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'like', "%$search%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('nama', 'like', "%$search%");
                  });
            });
        }

        // 4. Eksekusi Pagination (dengan Query String agar filter tidak hilang saat pindah halaman)
        $siswa = $query->paginate(10)->withQueryString();
        
        // 5. Hitung Statistik (Tetap dari total siswa asli di kelas tersebut tanpa terpengaruh filter pencarian)
        $allSiswa = Siswa::where('id_kelas', $kelas->id_kelas)->get();
        
        $namaKelas  = $kelas->nama_kelas;
        $totalSiswa = $allSiswa->count();
        $lakiLaki   = $allSiswa->where('jenis_kelamin', 'L')->count();
        $perempuan  = $allSiswa->where('jenis_kelamin', 'P')->count();

        return view('walikelas.siswa.index', compact(
            'siswa', 
            'kelas', 
            'namaKelas', 
            'totalSiswa', 
            'lakiLaki', 
            'perempuan'
        ));
    }

    public function show($id)
    {
        // Load semua relasi termasuk tahunAjaran
        $siswa = Siswa::with(['user', 'profil', 'tahunAjaran', 'kelas'])
                      ->where('id_siswa', $id)
                      ->firstOrFail();

        $user = Auth::user();
        $guru = Guru::where('id_user', $user->id_user)->first();
        
        if (!$guru) return abort(403);
        
        $kelas = Kelas::where('id_guru', $guru->id_guru)->first();

        if (!$kelas) return abort(403);

        // Keamanan wali kelas: Hanya bisa melihat siswa di kelasnya sendiri
        if ($siswa->id_kelas !== $kelas->id_kelas) {
            return abort(403, "Akses ditolak.");
        }

        return view('walikelas.siswa.show', compact('siswa', 'kelas'));
    }
}