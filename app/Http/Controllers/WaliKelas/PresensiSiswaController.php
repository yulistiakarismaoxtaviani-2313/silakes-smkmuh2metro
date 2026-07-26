<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\DetailPresensi;
use App\Models\Guru;
use App\Models\Presensi;
use Carbon\Carbon;

class PresensiSiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('id_user', $user->id_user)->first();
        $kelas = Kelas::where('id_guru', $guru->id_guru)->first();
        $hari_ini = Carbon::today()->toDateString();

        if (!$kelas) return abort(403, 'Anda bukan Wali Kelas.');

        // 1. Ambil SEMUA sesi yang statusnya 'dibuka' hari ini
        $sesi_dibuka = Presensi::where('tanggal', $hari_ini)
                                ->where('status_sesi', 'dibuka')
                                ->orderBy('jam_pelajaran', 'asc')
                                ->get();

        // 2. Ambil daftar siswa
        $query = Siswa::with('user')->where('id_kelas', $kelas->id_kelas);
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }
        $siswa_list = $query->get();

        // 3. Petakan status siswa per sesi
        $data_presensi = $siswa_list->map(function($s) use ($sesi_dibuka) {
            $status_per_sesi = [];
            foreach ($sesi_dibuka as $sesi) {
                $absen = DetailPresensi::where('id_siswa', $s->id_siswa)
                    ->where('id_presensi', $sesi->id_presensi)
                    ->first();
                
                $status_per_sesi[$sesi->id_presensi] = $absen->status ?? 'belum';
            }
            $s->absen_hari_ini = $status_per_sesi;
            return $s;
        });

        // 4. HITUNG STATISTIK SECARA DINAMIS
        // Collapse mengubah array multi-dimensi menjadi satu array linear berisi string status saja
        $all_statuses = collect($data_presensi->pluck('absen_hari_ini')->collapse());
        
        $stats = [
            'total' => $siswa_list->count(),
            'hadir' => $all_statuses->filter(fn($status) => $status === 'hadir')->count(),
            'izin'  => $all_statuses->filter(fn($status) => $status === 'izin')->count(),
            'sakit' => $all_statuses->filter(fn($status) => $status === 'sakit')->count(),
            'alfa'  => $all_statuses->filter(fn($status) => $status === 'alfa')->count(),
            'belum' => $all_statuses->filter(fn($status) => $status === 'belum')->count(),
        ];

        return view('walikelas.presensi.index', compact('kelas', 'data_presensi', 'sesi_dibuka', 'stats'));
    }

    public function show($id_siswa)
    {
        $siswa = Siswa::with(['user', 'kelas'])->findOrFail($id_siswa);
        $hari_ini = Carbon::today()->toDateString();

        // 1. Ambil semua sesi yang dibuka Admin hari ini
        $sesi_hari_ini = Presensi::where('tanggal', $hari_ini)
            ->where('status_sesi', 'dibuka')
            ->orderBy('jam_pelajaran', 'asc')
            ->get();

        // 2. Ambil data absen siswa hari ini untuk dicocokkan
        $absen_siswa = DetailPresensi::where('id_siswa', $id_siswa)
            ->whereHas('presensi', function($q) use ($hari_ini) {
                $q->where('tanggal', $hari_ini);
            })->get()->keyBy('id_presensi');

        // 3. Gabungkan: Sesi Admin sebagai Master, Absen Siswa sebagai Detail
        $riwayat_lengkap = $sesi_hari_ini->map(function($sesi) use ($absen_siswa) {
            $detail = $absen_siswa->get($sesi->id_presensi);
            return (object)[
                'jam_pelajaran' => $sesi->jam_pelajaran,
                'status' => $detail->status ?? 'belum',
                'waktu_absen' => $detail ? $detail->created_at->format('H:i') : '-',
                'keterangan' => $detail->keterangan ?? 'Belum melakukan presensi'
            ];
        });

        return view('walikelas.presensi.show', compact('siswa', 'riwayat_lengkap'));
    }
}