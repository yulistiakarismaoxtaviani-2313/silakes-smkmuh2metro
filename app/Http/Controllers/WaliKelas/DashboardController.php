<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data guru berdasarkan id_user
        $dataGuru = Guru::where('id_user', $user->id_user)->first();

        if (!$dataGuru) {
            abort(403, 'Data Guru tidak ditemukan.');
        }

        // Ambil kelas pengampu
        $kelasPengampu = Kelas::where('id_guru', $dataGuru->id_guru)->first();

        if (!$kelasPengampu) {
            abort(403, 'Anda belum ditunjuk sebagai Wali Kelas.');
        }

        $namaKelas = $kelasPengampu->nama_kelas;
        $totalSiswa = Siswa::where('id_kelas', $kelasPengampu->id_kelas)->count();

        // --- STATISTIK HARI INI ---
        $today = now()->toDateString();
        
        $stats = [
            'hadir' => DB::table('detail_presensi')
                ->join('siswa', 'detail_presensi.id_siswa', '=', 'siswa.id_siswa')
                ->join('presensi', 'detail_presensi.id_presensi', '=', 'presensi.id_presensi')
                ->where('siswa.id_kelas', $kelasPengampu->id_kelas)
                ->where('presensi.tanggal', $today)
                ->where('detail_presensi.status', 'hadir')
                ->count(),
            
            'izin_sakit' => DB::table('detail_presensi')
                ->join('siswa', 'detail_presensi.id_siswa', '=', 'siswa.id_siswa')
                ->join('presensi', 'detail_presensi.id_presensi', '=', 'presensi.id_presensi')
                ->where('siswa.id_kelas', $kelasPengampu->id_kelas)
                ->where('presensi.tanggal', $today)
                ->whereIn('detail_presensi.status', ['izin', 'sakit'])
                ->count(),
        ];

        // --- AMBIL DATA SISWA BERMASALAH (MINIMAL ALFA 5 KALI) ---
        $siswaBermasalah = Siswa::where('siswa.id_kelas', $kelasPengampu->id_kelas)
            ->join('users', 'siswa.id_user', '=', 'users.id_user')
            ->leftJoin('detail_presensi', 'siswa.id_siswa', '=', 'detail_presensi.id_siswa')
            ->select(
                'siswa.id_siswa', 
                'users.nama', 
                'siswa.nis', 
                DB::raw('COUNT(CASE WHEN detail_presensi.status = "alfa" THEN 1 END) as jumlah_alfa')
            )
            ->groupBy('siswa.id_siswa', 'users.nama', 'siswa.nis')
            ->having('jumlah_alfa', '>=', 5) // Diubah dari > 0 menjadi >= 5 agar menyaring yang sudah 5 kali ke atas
            ->orderBy('jumlah_alfa', 'desc')
            ->limit(10)
            ->get();

        return view('walikelas.dashboard', compact(
            'dataGuru', 
            'kelasPengampu', 
            'namaKelas', 
            'totalSiswa', 
            'stats',
            'siswaBermasalah'
        ));
    }
}