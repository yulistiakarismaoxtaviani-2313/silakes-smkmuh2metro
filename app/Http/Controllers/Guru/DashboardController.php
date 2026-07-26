<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Pengumuman;
use App\Models\JadwalPelajaran;
use App\Models\Guru;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $dayName = Carbon::now()->locale('id')->dayName;

        // 1. Ambil data Guru berdasarkan user login
        $guru = Guru::where('id_user', $user->id_user)->first();
        
        // Cek apakah user adalah Wali Kelas untuk redirect
        $isWali = $guru ? Kelas::where('id_guru', $guru->id_guru)->exists() : false;
        if ($isWali) {
            return redirect()->route('walikelas.dashboard');
        }

        if (!$guru) {
            $presensiHariIni = collect([]);
        } else {
            // 2. Ambil semua Jadwal Mengajar Guru hari ini sebagai patokan kartu
            $jadwalGuruHariIni = JadwalPelajaran::with(['kelas'])
                ->where('id_guru', $guru->id_guru)
                ->where('hari', $dayName)
                ->get();

            $presensiHariIni = $jadwalGuruHariIni->map(function ($jadwal) use ($today) {
                // Cari sesi presensi yang dibuat (oleh Admin/siapapun) berdasarkan jam pelajaran
                // Catatan: Pastikan format enum 'Jam ke-X' sesuai dengan jam di jadwal
                $jamPelajaran =
    Carbon::parse($jadwal->jam_mulai)->format('H:i')
    . ' - ' .
    Carbon::parse($jadwal->jam_selesai)->format('H:i');

$presensi = Presensi::whereDate('tanggal', $today)
    ->where('jam_pelajaran', $jamPelajaran)
    ->first();

                // Hitung total siswa AKTIF di kelas tersebut
                $totalSiswa = DB::table('siswa')
                    ->where('id_kelas', $jadwal->id_kelas)
                    ->where('status', 'aktif')
                    ->count();

                if ($presensi) {
                    // Hitung rekap dengan prefix tabel untuk menghindari kolom 'status' yang ambigu
                    $rekap = DB::table('detail_presensi')
                        ->join('siswa', 'detail_presensi.id_siswa', '=', 'siswa.id_siswa')
                        ->where('detail_presensi.id_presensi', $presensi->id_presensi)
                        ->where('siswa.id_kelas', $jadwal->id_kelas)
                        ->selectRaw("
                            SUM(CASE WHEN detail_presensi.status = 'hadir' THEN 1 ELSE 0 END) as hadir,
                            SUM(CASE WHEN detail_presensi.status = 'izin' THEN 1 ELSE 0 END) as izin,
                            SUM(CASE WHEN detail_presensi.status = 'sakit' THEN 1 ELSE 0 END) as sakit,
                            SUM(CASE WHEN detail_presensi.status = 'alfa' THEN 1 ELSE 0 END) as alfa
                        ")->first();

                    return [
    'id_presensi' => $presensi->id_presensi,
    'id_kelas' => $jadwal->id_kelas,
    'nama_kelas' => $jadwal->kelas->nama_kelas ?? 'N/A',
    'jam_pelajaran' => $presensi->jam_pelajaran,
    'status_sesi' => $presensi->status_sesi,
    'total' => $totalSiswa,
    'hadir' => $rekap->hadir ?? 0,
    'izin' => $rekap->izin ?? 0,
    'sakit' => $rekap->sakit ?? 0,
    'alfa' => $rekap->alfa ?? 0,
];
                }

                // Default jika sesi presensi belum ada/belum dibuka oleh Admin
                return [
                    'id_presensi' => null,
                    'nama_kelas' => $jadwal->kelas->nama_kelas ?? 'N/A',
                    'jam_pelajaran' => 'Jam ke-', // Bisa diisi manual atau dari kolom jadwal
                    'status_sesi' => 'belum dibuka',
                    'total' => $totalSiswa,
                    'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alfa' => 0,
                ];
            });
        }

        // 3. Ambil Pengumuman Terbaru
        $pengumuman = Pengumuman::whereIn('target', ['semua', 'guru'])
            ->where('status', 'aktif')
            ->orderBy('created_at', 'desc')
            ->first();

        // 4. Data Jadwal Pelajaran untuk list tampilan
        $jadwalHariIni = JadwalPelajaran::with(['kelas', 'mapel'])
            ->where('id_guru', $guru->id_guru ?? 0)
            ->where('hari', $dayName)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return view('guru.dashboard', compact('presensiHariIni', 'pengumuman', 'jadwalHariIni'));
    }
}