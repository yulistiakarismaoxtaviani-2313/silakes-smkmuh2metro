<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\DetailPresensi;
use App\Models\Prestasi;
use App\Models\JadwalPelajaran;
use App\Models\Pengumuman;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Siswa berdasarkan User yang login
        $siswa = Siswa::with('kelas')->where('id_user', Auth::id())->first();

        if (!$siswa) {
            return "Data Siswa tidak ditemukan. Pastikan id_user di tabel siswa cocok dengan ID di tabel users.";
        }

        $id_siswa = $siswa->id_siswa;
        $id_kelas = $siswa->id_kelas;
        $id_tahun_ajaran = $siswa->id_tahun_ajaran;

        // 2. Query Rekap Presensi
        $queryPresensi = DetailPresensi::where('id_siswa', $id_siswa);
        
        $hadir = (clone $queryPresensi)->where('status', 'hadir')->count();
        $alfa  = (clone $queryPresensi)->where('status', 'alfa')->count();
        $izin  = (clone $queryPresensi)->where('status', 'izin')->count();
        $sakit = (clone $queryPresensi)->where('status', 'sakit')->count();
        
        $totalPertemuan = $hadir + $alfa + $izin + $sakit;
        $persentase = $totalPertemuan > 0 ? round(($hadir / $totalPertemuan) * 100) : 0;

        // 3. FIX JADWAL: Support Bahasa Indonesia & Inggris
        Carbon::setLocale('id');
        $hari_indo = Carbon::now()->translatedFormat('l'); // Contoh: Senin
        $hari_inggris = Carbon::now()->format('l');        // Contoh: Monday

        $jadwalHariIni = JadwalPelajaran::where('id_kelas', $id_kelas)
                        ->where(function($query) use ($hari_indo, $hari_inggris) {
                            $query->where('hari', 'LIKE', $hari_indo)
                                  ->orWhere('hari', 'LIKE', $hari_inggris);
                        })
                        ->orderBy('jam_mulai', 'asc')
                        ->get();

        // 4. Data Total Hitung Prestasi
        $prestasiAkademik = Prestasi::where('id_siswa', $id_siswa)->where('kategori', 'akademik')->count();
        $prestasiNonAkademik = Prestasi::where('id_siswa', $id_siswa)->where('kategori', 'non-akademik')->count();

        // 5. BARU: Ambil Daftar Riwayat Prestasi Terbaru untuk Validasi (Limit 5 data terbaru)
       $prestasiTerbaru = Prestasi::where('id_siswa', $id_siswa)
    ->where(function($query) {
        $query->where('status_validasi', 'Pending')
              ->orWhere(function($subQuery) {
                  $subQuery->whereIn('status_validasi', ['Disetujui', 'Ditolak'])
                           ->where('updated_at', '>=', Carbon::now()->subDays(7));
              });
    })
    ->orderByRaw("FIELD(status_validasi, 'Pending', 'Disetujui', 'Ditolak')")
    ->orderBy('updated_at', 'desc')
    ->get();

        // 6. Pengumuman
        $pengumuman = Pengumuman::latest()->first();

        // Ambil semua variabel ke dalam view termasuk 'prestasiTerbaru'
        return view('siswa.dashboard', compact(
            'hadir', 'alfa', 'izin', 'sakit', 
            'persentase', 'totalPertemuan',
            'prestasiAkademik', 'prestasiNonAkademik',
            'prestasiTerbaru', // <-- Ditambahkan ke compact
            'jadwalHariIni', 'pengumuman', 'siswa', 'hari_indo'
        ));
    }
}