<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\DetailPresensi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PresensiKelasExport;
use Maatwebsite\Excel\Facades\Excel;

class RekapPresensiController extends Controller
{
    /**
     * Halaman Utama: Menampilkan Rekap Absen Per Kelas
     */
    public function index(Request $request)
    {
        // 1. Ambil Data Referensi untuk UI Dropdown Filter & Card Ringkasan
        $totalSiswa = Siswa::count();
        $totalKelas = Kelas::count();
        $listTahunAjaran = TahunAjaran::all();
        $listSemester = Semester::all();

        // 2. Ambil parameter filter dari request (Opsional, agar semua data muncul jika belum di-filter)
        $selectedTA = $request->get('tahun_ajaran');
        $selectedSem = $request->get('semester');

        // 3. Hitung Total Statistik Atas (Global atau Berdasarkan Filter)
        $total_stats = [
            'hadir' => DetailPresensi::where('status', 'hadir')
                        ->when($selectedTA || $selectedSem, function($query) use ($selectedTA, $selectedSem) {
                            $query->whereHas('siswa.kelas', function($q) use ($selectedTA, $selectedSem) {
                                if($selectedTA) $q->where('id_tahun_ajaran', $selectedTA);
                                if($selectedSem) $q->where('id_semester', $selectedSem);
                            });
                        })->count(),
            'sakit' => DetailPresensi::where('status', 'sakit')
                        ->when($selectedTA || $selectedSem, function($query) use ($selectedTA, $selectedSem) {
                            $query->whereHas('siswa.kelas', function($q) use ($selectedTA, $selectedSem) {
                                if($selectedTA) $q->where('id_tahun_ajaran', $selectedTA);
                                if($selectedSem) $q->where('id_semester', $selectedSem);
                            });
                        })->count(),
            'izin'  => DetailPresensi::where('status', 'izin')
                        ->when($selectedTA || $selectedSem, function($query) use ($selectedTA, $selectedSem) {
                            $query->whereHas('siswa.kelas', function($q) use ($selectedTA, $selectedSem) {
                                if($selectedTA) $q->where('id_tahun_ajaran', $selectedTA);
                                if($selectedSem) $q->where('id_semester', $selectedSem);
                            });
                        })->count(),
            'alfa'  => DetailPresensi::where('status', 'alfa')
                        ->when($selectedTA || $selectedSem, function($query) use ($selectedTA, $selectedSem) {
                            $query->whereHas('siswa.kelas', function($q) use ($selectedTA, $selectedSem) {
                                if($selectedTA) $q->where('id_tahun_ajaran', $selectedTA);
                                if($selectedSem) $q->where('id_semester', $selectedSem);
                            });
                        })->count(),
        ];

        // 4. Query Utama Daftar Kelas & Hitung Akumulasi Seperti Logika Wali Kelas
        $kelasQuery = Kelas::with(['guru.user'])
            ->when($request->search, function ($query, $search) {
                $query->where('nama_kelas', 'like', "%{$search}%")
                      ->orWhereHas('guru.user', function($q) use ($search) {
                          $q->where('nama', 'like', "%{$search}%");
                      });
            })
            ->when($selectedTA, function ($query, $ta) {
                $query->where('id_tahun_ajaran', $ta);
            })
            ->when($selectedSem, function ($query, $sem) {
                $query->where('id_semester', $sem);
            })
            ->when($request->tingkat, function ($query, $tingkat) {
                $query->where('tingkat', $tingkat);
            });

        // Terapkan Pagination
        $kelasPaginated = $kelasQuery->paginate(10);

        // Petakan (Mapping) data presensi siswa ke dalam properti objek kelas masing-masing
        $dataRekap = $kelasPaginated->through(function($kelas) {
            $siswaDiKelas = Siswa::with('detail_presensi')->where('id_kelas', $kelas->id_kelas)->get();
            
            $kelas->hadir_count = $siswaDiKelas->sum(function($s) { return $s->detail_presensi->where('status', 'hadir')->count(); });
            $kelas->sakit_count = $siswaDiKelas->sum(function($s) { return $s->detail_presensi->where('status', 'sakit')->count(); });
            $kelas->izin_count  = $siswaDiKelas->sum(function($s) { return $s->detail_presensi->where('status', 'izin')->count(); });
            $kelas->alpa_count  = $siswaDiKelas->sum(function($s) { return $s->detail_presensi->where('status', 'alfa')->count(); });

            return $kelas;
        });

        $tahunAktif = TahunAjaran::where('status', 'Aktif')->first();
        return view('admin.rekap.index', compact(
            'dataRekap', 
            'totalSiswa', 
            'totalKelas', 
            'listTahunAjaran', 
            'listSemester',
            'total_stats',
            'selectedTA',
            'selectedSem',
            'tahunAktif'
        ));
    }

    /**
     * Halaman Detail: Menampilkan Rekap Absen Seluruh Siswa di Kelas Tertentu
     */
    public function show($id)
    {
        // Ambil info detail kelas & guru pembimbing
        $kelas = Kelas::with(['guru.user', 'tahunAjaran'])->findOrFail($id);

        // Ambil data siswa dengan format Object Eloquent murni agar terbaca oleh Blade ($item->nis)
        $siswa = Siswa::where('id_kelas', $id)
            ->with(['user'])
            ->withCount([
                'detail_presensi as hadir_count' => function ($query) {
                    $query->where('status', 'hadir');
                },
                'detail_presensi as sakit_count' => function ($query) {
                    $query->where('status', 'sakit');
                },
                'detail_presensi as izin_count' => function ($query) {
                    $query->where('status', 'izin');
                },
                'detail_presensi as alfa_count' => function ($query) {
                    $query->where('status', 'alfa');
                }
            ])
            ->get();

        // Akumulasi total statistik untuk ditaruh di card info bagian atas halaman detail
        $total_stats = [
            'hadir' => $siswa->sum('hadir_count'),
            'sakit' => $siswa->sum('sakit_count'),
            'izin'  => $siswa->sum('izin_count'),
            'alfa'  => $siswa->sum('alfa_count'),
        ];

        return view('admin.rekap.show', compact('kelas', 'siswa', 'total_stats'));
    }
    public function download($id)
{
    $kelas = Kelas::with(['guru.user', 'tahunAjaran'])
        ->findOrFail($id);

    $siswa = Siswa::where('id_kelas', $id)
        ->with(['user'])
        ->withCount([
            'detail_presensi as hadir_count' => fn($q) => $q->where('status', 'hadir'),
            'detail_presensi as sakit_count' => fn($q) => $q->where('status', 'sakit'),
            'detail_presensi as izin_count'  => fn($q) => $q->where('status', 'izin'),
            'detail_presensi as alfa_count'  => fn($q) => $q->where('status', 'alfa'),
        ])
        ->get();

    $total_stats = [
        'hadir' => $siswa->sum('hadir_count'),
        'sakit' => $siswa->sum('sakit_count'),
        'izin'  => $siswa->sum('izin_count'),
        'alfa'  => $siswa->sum('alfa_count'),
    ];

    $pdf = Pdf::loadView(
        'admin.rekap.pdf',
        compact('kelas', 'siswa', 'total_stats')
    );

    return $pdf->download(
        'Rekap_Presensi_'.$kelas->nama_kelas.'.pdf'
    );
}
public function downloadExcel($id)
{
    $kelas = Kelas::with(['guru.user', 'tahunAjaran'])
        ->findOrFail($id);

    $siswa = Siswa::where('id_kelas', $id)
        ->with(['user'])
        ->withCount([
            'detail_presensi as hadir_count' => fn($q) => $q->where('status', 'hadir'),
            'detail_presensi as sakit_count' => fn($q) => $q->where('status', 'sakit'),
            'detail_presensi as izin_count'  => fn($q) => $q->where('status', 'izin'),
            'detail_presensi as alfa_count'  => fn($q) => $q->where('status', 'alfa'),
        ])
        ->get();

    $total_stats = [
        'hadir' => $siswa->sum('hadir_count'),
        'sakit' => $siswa->sum('sakit_count'),
        'izin'  => $siswa->sum('izin_count'),
        'alfa'  => $siswa->sum('alfa_count'),
    ];

    return Excel::download(
        new PresensiKelasExport(
            $kelas,
            $siswa,
            $total_stats
        ),
        'Rekap_Presensi_'.$kelas->nama_kelas.'.xlsx'
    );
}
}