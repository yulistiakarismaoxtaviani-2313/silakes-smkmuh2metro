<?php

namespace App\Http\Controllers\Walikelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\DetailPresensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class WaliRekapController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('id_user', $user->id_user)->first();
        $kelas = Kelas::with(['tahunAjaran','semester'])->where('id_guru', $guru->id_guru)->first();

        if (!$kelas) return abort(403, 'Anda bukan Wali Kelas.');

        // 1. Query Dasar Siswa di Kelas Tersebut
        $query = Siswa::with(['user', 'detail_presensi'])
            ->where('id_kelas', $kelas->id_kelas);

        // Filter Pencarian Nama
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        $siswa_list = $query->get();

        // 2. Hitung Akumulasi per Siswa
        $list_siswa = $siswa_list->map(function($s) {
            return [
                'nis'  => $s->nis,
                'nama' => $s->user->nama,
                'jk'   => $s->jenis_kelamin, // Asumsi ada field ini di tabel siswa
                'h'    => $s->detail_presensi->where('status', 'hadir')->count(),
                'i'    => $s->detail_presensi->where('status', 'izin')->count(),
                's'    => $s->detail_presensi->where('status', 'sakit')->count(),
                'a'    => $s->detail_presensi->where('status', 'alfa')->count(),
            ];
        });

        // 3. Data Ringkasan untuk Card Atas
        $summary = [
            'nama_kelas'  => $kelas->nama_kelas,
            'total_siswa' => $siswa_list->count(),
            'laki_laki'   => $siswa_list->where('jenis_kelamin', 'L')->count(),
            'perempuan'   => $siswa_list->where('jenis_kelamin', 'P')->count(),
            'total_h'     => $list_siswa->sum('h'),
            'total_i'     => $list_siswa->sum('i'),
            'total_s'     => $list_siswa->sum('s'),
            'total_a'     => $list_siswa->sum('a'),
        ];

        $tahunAjaran = TahunAjaran::all();
        $semester = Semester::all();
        return view('walikelas.rekap-presensi.index', compact('summary', 'list_siswa','kelas'));
    }

   public function exportPdf()
{
    $user = Auth::user();

    $guru = Guru::where('id_user', $user->id_user)->first();

    $kelas = Kelas::with(['guru.user', 'tahunAjaran', 'semester'])
        ->where('id_guru', $guru->id_guru)
        ->firstOrFail();

    $siswa = Siswa::with('user')
        ->where('id_kelas', $kelas->id_kelas)
        ->get();

    foreach ($siswa as $item) {

        $item->hadir_count = DetailPresensi::where('id_siswa', $item->id_siswa)
            ->where('status', 'hadir')
            ->count();

        $item->izin_count = DetailPresensi::where('id_siswa', $item->id_siswa)
            ->where('status', 'izin')
            ->count();

        $item->sakit_count = DetailPresensi::where('id_siswa', $item->id_siswa)
            ->where('status', 'sakit')
            ->count();

        $item->alfa_count = DetailPresensi::where('id_siswa', $item->id_siswa)
            ->where('status', 'alfa')
            ->count();
    }

    $total_stats = [
        'hadir' => DetailPresensi::whereIn(
            'id_siswa',
            $siswa->pluck('id_siswa')
        )->where('status', 'hadir')->count(),

        'izin' => DetailPresensi::whereIn(
            'id_siswa',
            $siswa->pluck('id_siswa')
        )->where('status', 'izin')->count(),

        'sakit' => DetailPresensi::whereIn(
            'id_siswa',
            $siswa->pluck('id_siswa')
        )->where('status', 'sakit')->count(),

        'alfa' => DetailPresensi::whereIn(
            'id_siswa',
            $siswa->pluck('id_siswa')
        )->where('status', 'alfa')->count(),
    ];

    $pdf = Pdf::loadView(
        'walikelas.rekap-presensi.pdf',
        compact('kelas', 'siswa', 'total_stats')
    )->setPaper('a4', 'portrait');

    return $pdf->download('Rekap-Presensi-' . $kelas->nama_kelas . '.pdf');
}
}