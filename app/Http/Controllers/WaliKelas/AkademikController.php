<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPelajaran;
use App\Models\JadwalUjian;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\Pengumuman;
use App\Models\Guru; // Tambahkan ini
use App\Models\Kelas; // Tambahkan ini
use App\Models\DetailUjian;
use Barryvdh\DomPDF\Facade\Pdf; 
use Carbon\Carbon; // Tambahkan ini juga karena kita pakai Carbon untuk format tanggal
use Illuminate\Support\Facades\Auth;

class AkademikController extends Controller
{
    // Halaman Jadwal (Pelajaran & Ujian)
    public function indexJadwal()
    {
        $user = Auth::user();
        // Ambil data guru yang sedang login
        $guru = Guru::with(['profilGuru','mapel'])->where('id_user', $user->id_user)->first();

        if (!$guru) {
            return abort(403, "Data profil guru tidak ditemukan.");
        }

       $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
       $semesterAktif = Semester::where('status', 'aktif')->first();
       $tahun_ajaran = $tahunAktif->tahun_ajaran ?? 'N/A';
       $semester = $semesterAktif->nama_semester ?? 'N/A';
        
        // Menghitung total jam mengajar guru tersebut
        $total_jam = JadwalPelajaran::where('id_guru', $guru->id_guru)->count();

        // 1. Ambil Jadwal Mengajar Pribadi
        $jadwal_mengajar = JadwalPelajaran::with(['mapel', 'kelas'])
                            ->where('id_guru', $guru->id_guru)
                            ->get()
                            ->groupBy('hari');

        // 2. Ambil Jadwal Pengawas Ujian Pribadi
        $jadwal_ujian = DetailUjian::with(['jadwalUjian.kelas', 'mapel'])
                            ->where('id_pengawas', $guru->id_guru)
                            ->get()
                            ->groupBy(function($item) {
    return \Carbon\Carbon::parse($item->tanggal)
        ->locale('id')
        ->translatedFormat('l, d F Y');
});

        return view('walikelas.akademik.jadwal.index', compact(
            'jadwal_mengajar', 
            'jadwal_ujian', 
            'guru', 
            'tahun_ajaran', 
            'semester', 
            'total_jam'
        ));
    }

    public function downloadMengajar()
{
    $guru = Guru::where('id_user', Auth::id())
    ->with(['profilGuru', 'user', 'mapel'])
    ->first();
    $jadwal = JadwalPelajaran::with(['mapel', 'kelas'])->where('id_guru', $guru->id_guru)->get()->groupBy('hari');
    // Ambil tahun ajaran & semester aktif

    $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

    $semesterAktif = Semester::where('status', 'aktif')->first();



    $tahun_ajaran = $tahunAktif->tahun_ajaran ?? 'N/A';

    $semester = $semesterAktif->nama_semester ?? 'N/A';
    $total_jam = $jadwal->flatten()->count();

    // UBAH DISINI: Pastikan nama view sesuai
    $pdf = Pdf::loadView('walikelas.akademik.jadwal.cetak-mengajar', compact('jadwal', 'guru', 'tahun_ajaran', 'semester', 'total_jam'));
    return $pdf->download('Jadwal_Mengajar_'.$guru->user->nama.'.pdf');
}

public function downloadUjian()
{
    $guru = Guru::where('id_user', Auth::id())->with(['profilGuru', 'user'])->first();
    $jadwal = DetailUjian::with(['jadwalUjian.kelas', 'mapel'])
                ->where('id_pengawas', $guru->id_guru)
                ->get()
                ->groupBy(function($item) {
    return \Carbon\Carbon::parse($item->tanggal)
        ->locale('id')
        ->translatedFormat('l, d F Y');
});
            
    $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

    $semesterAktif = Semester::where('status', 'aktif')->first();



    $tahun_ajaran = $tahunAktif->tahun_ajaran ?? 'N/A';

    $semester = $semesterAktif->nama_semester ?? 'N/A';

    // UBAH DISINI: Pastikan nama view sesuai
    $pdf = Pdf::loadView('walikelas.akademik.jadwal.cetak-ujian', compact('jadwal', 'tahun_ajaran', 'semester', 'guru'));
    return $pdf->download('Jadwal_Ujian_'.$guru->user->nama.'.pdf');
}


    // Halaman List Pengumuman
    public function indexPengumuman(Request $request) // Tambahkan Request
{
    $user = Auth::user();
    $guru = Guru::where('id_user', $user->id_user)->first();

    if (!$guru) return abort(403, "Data Guru tidak ditemukan.");

    $kelas = Kelas::where('id_guru', $guru->id_guru)->first();
    if (!$kelas) return abort(403, "Anda bukan Wali Kelas.");

    $id_kelas_wali = $kelas->id_kelas; 

    // Mulai Query
    $query = Pengumuman::where('status', 'aktif')
        ->where(function($q) use ($id_kelas_wali) {
            $q->whereIn('target', ['semua', 'guru'])
              ->orWhere(function($sq) use ($id_kelas_wali) {
                  $sq->where('target', 'kelas')
                     ->where('id_kelas', $id_kelas_wali);
              });
        });

    // FILTER: Urutan (Sort)
    if ($request->get('sort') == 'terlama') {
        $query->oldest();
    } else {
        $query->latest();
    }

    // FILTER: Kategori
if ($request->filled('kategori')) {
    // Menggunakan like % agar lebih fleksibel menangkap data
    $query->where('kategori', 'like', '%' . $request->kategori . '%');
}

    // SEARCH: Judul atau Isi
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('judul', 'like', "%$search%")
              ->orWhere('isi', 'like', "%$search%");
        });
    }

    // Gunakan withQueryString agar filter tidak hilang saat pindah halaman pagination
    $pengumumans = $query->paginate(10)->withQueryString();

    return view('walikelas.akademik.pengumuman.index', compact('pengumumans'));
}

    // Halaman Detail Pengumuman
    public function showPengumuman($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        
        // Opsional: Cek akses jika pengumuman targetnya spesifik kelas
        return view('walikelas.akademik.pengumuman.show', compact('pengumuman'));
    }
}