<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User; // Untuk menghitung Guru berdasarkan role
use App\Models\Kelas;
use App\Models\ProgramKeahlian;
use App\Models\DetailPresensi;
use App\Models\Prestasi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Mengambil data statistik dasar
        $totalSiswa = Siswa::count();
        // Asumsi: Guru ada di tabel Users dengan role 'guru'
        $totalGuru  = User::where('role', 'guru')->count();
        $totalKelas = Kelas::count();
        $totalProdi = ProgramKeahlian::count();

        $role = auth()->user()->role;

$siswaBermasalah = collect();
$prestasiTerbaru = collect();

if ($role == 'admin_presensi') {

    $siswaBermasalah = DetailPresensi::query()
        ->select('id_siswa', DB::raw('count(*) as total_alfa'))
        ->where('status', 'alfa')
        ->groupBy('id_siswa')
        ->having('total_alfa', '>=', 5)
        ->orderByDesc('total_alfa')
        ->with(['siswa.user', 'siswa.kelas'])
        ->limit(10)
        ->get()
        ->map(function ($item) {
            return [
                'nama'  => $item->siswa->user->nama ?? 'Tidak Ditemukan',
                'nis'   => $item->siswa->nis ?? '-',
                'kelas' => $item->siswa->kelas->nama_kelas ?? '-',
                'alfa'  => $item->total_alfa,
            ];
        });

} else {

    $prestasiTerbaru = Prestasi::with([
            'siswa.user',
            'siswa.kelas'
        ])
        ->whereIn('status_validasi', ['Pending', 'Menunggu'])
        ->latest()
        ->take(10)
        ->get();

}

   return view('admin.dashboard', compact(
    'role',
    'totalSiswa',
    'totalGuru',
    'totalKelas',
    'totalProdi',
    'siswaBermasalah',
    'prestasiTerbaru'
));
}
}