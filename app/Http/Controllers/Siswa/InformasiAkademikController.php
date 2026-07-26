<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use App\Models\JadwalUjian;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\Pengumuman;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InformasiAkademikController extends Controller
{
    /**
     * Menampilkan halaman Jadwal Pelajaran dan Jadwal Ujian
     */
    public function jadwal()
{
    $user = Auth::user();
    
    // 1. Ambil data siswa
    $siswa = \App\Models\Siswa::where('id_user', $user->id_user)->first();

    if (!$siswa) {
        return "Data profil siswa tidak ditemukan.";
    }

    // 2. AMBIL TAHUN AJARAN & SEMESTER AKTIF
    $tahunAktif = \App\Models\TahunAjaran::where('status', 'aktif')->first();
    $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first();

    $id_tahun = $tahunAktif->id_tahun_ajaran ?? $siswa->id_tahun_ajaran;
    $id_semester = $semesterAktif->id_semester ?? null;

    // 3. CARI DATA JADWAL UJIAN TERLEBIH DAHULU (PENTING: Pindahkan ke sini)
    $ujianUtama = \App\Models\JadwalUjian::where('id_kelas', $siswa->id_kelas)
        ->where('id_tahun_ajaran', $id_tahun)
        ->where('id_semester', $id_semester)
        ->latest()
        ->first();

    // 4. Ambil data Kelas & Wali Kelas
    $kelasInfo = \App\Models\Kelas::with('guru.user')->find($siswa->id_kelas);

    // 5. ISI ARRAY STATS (Sekarang $ujianUtama sudah ada isinya)
    $stats = [
        'nama_kelas'   => $kelasInfo->nama_kelas ?? '-',
        'wali_kelas'   => $kelasInfo->guru->user->nama ?? '-',
        'tahun_ajaran' => $tahunAktif->tahun_ajaran ?? 'Belum Diatur',
        'semester'     => $semesterAktif->nama_semester ?? 'Belum Diatur',
        'judul_ujian'  => $ujianUtama->judul ?? 'Jadwal Ujian', 
    ];

    // 6. QUERY JADWAL PELAJARAN
    $jadwalPelajaran = JadwalPelajaran::with(['mapel', 'guru.user'])
        ->where('id_kelas', $siswa->id_kelas)
        ->where('id_tahun_ajaran', $id_tahun)
        ->where('id_semester', $id_semester)
        ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
        ->orderBy('jam_mulai', 'asc')
        ->get()
        ->groupBy('hari');

    // 7. QUERY DETAIL JADWAL UJIAN
    $jadwalUjian = collect();
    if ($ujianUtama) {
        $jadwalUjian = \App\Models\DetailUjian::with(['mapel', 'pengawas.user'])
            ->where('id_jadwal_ujian', $ujianUtama->id_jadwal_ujian ?? $ujianUtama->id)
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get()
            ->groupBy('hari');
    }

    return view('siswa.informasi-akademik.jadwal.index', compact('jadwalPelajaran', 'jadwalUjian', 'stats'));
}

public function downloadPDF($type)
{
    $user = Auth::user();
    $siswa = \App\Models\Siswa::with('kelas')->where('id_user', $user->id_user)->first();
    
    // Ambil status aktif
    $tahunAktif = \App\Models\TahunAjaran::where('status', 'aktif')->first();
    $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first();

    // Siapkan data stats agar sama dengan yang dipanggil di Blade PDF
    $stats = [
        'nama_kelas'   => $siswa->kelas->nama_kelas ?? '-',
        'tahun_ajaran' => $tahunAktif->tahun_ajaran ?? '-',
        'semester'     => $semesterAktif->nama_semester ?? '-',
        'judul_ujian'  => $ujianUtama->judul ?? '-',
    ];

    if ($type === 'pelajaran') {
        // Nama variabel disamakan dengan di Blade: $jadwalPelajaran
        $jadwalPelajaran = \App\Models\JadwalPelajaran::with(['mapel', 'guru.user'])
            ->where('id_kelas', $siswa->id_kelas)
            ->where('id_tahun_ajaran', $tahunAktif->id_tahun_ajaran ?? null)
            ->where('id_semester', $semesterAktif->id_semester ?? null)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->get()
            ->groupBy('hari');
            
        $pdf = Pdf::loadView('siswa.informasi-akademik.jadwal.pdf_pelajaran', compact('jadwalPelajaran', 'stats', 'siswa'));
        return $pdf->download('Jadwal_Pelajaran_' . $siswa->nis . '.pdf');

    } else {
        // Logika untuk jadwal ujian
        $ujianUtama = \App\Models\JadwalUjian::where('id_kelas', $siswa->id_kelas)
            ->where('id_tahun_ajaran', $tahunAktif->id_tahun_ajaran ?? null)
            ->where('id_semester', $semesterAktif->id_semester ?? null)
            ->latest()
            ->first();

        // Nama variabel disamakan dengan di Blade: $jadwalUjian
        $jadwalUjian = collect();
        if ($ujianUtama) {
            $jadwalUjian = \App\Models\DetailUjian::with(['mapel', 'pengawas.user'])
                ->where('id_jadwal_ujian', $ujianUtama->id_jadwal_ujian ?? $ujianUtama->id)
                ->get()
                ->groupBy('hari');
        }

        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

$semesterAktif = Semester::where('status', 'aktif')
    ->where('id_tahun_ajaran', $tahunAktif->id_tahun_ajaran ?? null)
    ->first();

$tahun_ajaran = $tahunAktif->tahun_ajaran ?? '-';
$semester = ucfirst($semesterAktif->nama_semester ?? '-');

        $pdf = Pdf::loadView('siswa.informasi-akademik.jadwal.pdf_ujian', compact('jadwalUjian', 'stats', 'tahun_ajaran', 'semester', 'siswa'));
        return $pdf->download('Jadwal_Ujian_' . $siswa->nis . '.pdf');
    }
}


    /**
     * Menampilkan daftar pengumuman yang relevan untuk siswa dengan Filter & Search
     */
    public function indexPengumuman(Request $request)
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Mulai Query
        $query = Pengumuman::where('status', 'aktif')
            ->where('tanggal_tayang', '<=', $today)
            ->where(function($query) use ($user) {
                $query->where('target', 'semua')
                      ->orWhere('target', 'siswa')
                      ->orWhere(function($q) use ($user) {
                          $q->where('target', 'kelas')
                            ->where('id_kelas', $user->id_kelas);
                      });
            });

        // Pencarian: Cari Judul atau Kategori
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kategori', 'like', '%' . $searchTerm . '%');
            });
        }

        // Pengurutan: Terbaru (desc) atau Terlama (asc)
        $sort = $request->get('sort', 'latest'); // default terbaru
        if ($sort == 'oldest') {
            $query->orderBy('tanggal_tayang', 'asc');
        } else {
            $query->orderBy('tanggal_tayang', 'desc');
        }

        $pengumuman = $query->get();

        return view('siswa.informasi-akademik.pengumuman.index', compact('pengumuman'));
    }

    /**
     * Menampilkan detail satu pengumuman
     */
    public function showPengumuman($id)
    {
        $detail = Pengumuman::findOrFail($id);

        // Opsional: Cek jika pengumuman ini memang boleh dilihat siswa tersebut
        // (Misal jika targetnya kelas lain, beri 403)

        return view('siswa.informasi-akademik.pengumuman.show', compact('detail'));
    }
}