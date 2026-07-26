<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPelajaran;
use App\Models\DetailUjian;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index()
    {
        // 1. Ambil data Guru yang sedang login (Beserta User & Profil)
        $guru = Auth::user()->guru()->with(['user','profilGuru','mapel'])->first();

        if (!$guru) {
            return redirect()->back()->with('error', 'Data profil guru tidak ditemukan.');
        }

        // 2. Ambil Tahun Ajaran & Semester Aktif secara Dinamis
        $ta_aktif = TahunAjaran::where('status', 'aktif')->first();
        
        $semester_aktif = Semester::where('status', 'aktif')
                            ->where('id_tahun_ajaran', $ta_aktif->id_tahun_ajaran ?? null)
                            ->first();

        $tahun_ajaran_text = $ta_aktif ? $ta_aktif->tahun_ajaran : 'Tidak Ada TA Aktif';
        $semester_text = $semester_aktif ? ucfirst($semester_aktif->nama_semester) : 'Tidak Ada Semester Aktif';

        // 3. Ambil Jadwal Mengajar (Relasi ke Kelas & Mapel)
        $jadwal_mengajar = JadwalPelajaran::with(['kelas', 'mapel'])
            ->where('id_guru', $guru->id_guru)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai', 'asc')
            ->get()
            ->groupBy('hari');

        // 4. Hitung Total Jam Mengajar
        $total_menit = 0;
        foreach ($jadwal_mengajar as $hari => $sesi) {
            foreach ($sesi as $s) {
                if ($s->jam_mulai && $s->jam_selesai) {
                    $total_menit += Carbon::parse($s->jam_mulai)->diffInMinutes(Carbon::parse($s->jam_selesai));
                }
            }
        }
        $total_jam = round($total_menit / 60);

        // 5. Ambil Jadwal Ujian (Dinamis berdasarkan id_pengawas)
        // Eager Loading: Mapel -> JadwalUjian -> Kelas & JenisUjian
        $jadwal_ujian = DetailUjian::with([
                'mapel', 
                'jadwalUjian.kelas', 
                'jadwalUjian.jenisUjian'
            ]) 
            ->where('id_pengawas', $guru->id_guru) 
            ->orderBy('tanggal', 'asc')
            ->get()
            ->groupBy(function($val) {
                return Carbon::parse($val->tanggal)->isoFormat('dddd, D MMMM YYYY');
            });

        // 6. Ambil Daftar Jenis Ujian untuk Dropdown (Dinamis dari Data yang Ada)
        $daftar_jenis_ujian = $jadwal_ujian->flatten()->map(function($item) {
            return $item->jadwalUjian->jenisUjian->nama_ujian ?? 'Ujian';
        })->unique();

        // 7. Kirim semua data ke View
        return view('guru.informasi-akademik.jadwal.index', [
            'guru' => $guru,
            'jadwal_mengajar' => $jadwal_mengajar,
            'jadwal_ujian' => $jadwal_ujian,
            'total_jam' => $total_jam,
            'tahun_ajaran' => $tahun_ajaran_text,
            'semester' => $semester_text,
            'daftar_jenis_ujian' => $daftar_jenis_ujian
        ]);
    }

   

   public function downloadMengajar()
{
    $guru = Auth::user()->guru()->with(['user','profilGuru','mapel'])->first();

    $ta_aktif = TahunAjaran::where('status', 'aktif')->first();

    $semester_aktif = Semester::where('status', 'aktif')
        ->where('id_tahun_ajaran', $ta_aktif->id_tahun_ajaran ?? null)
        ->first();

    $jadwal_mengajar = JadwalPelajaran::with(['kelas', 'mapel'])
        ->where('id_guru', $guru->id_guru)
        ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
        ->orderBy('jam_mulai')
        ->get()
        ->groupBy('hari');

    $total_menit = 0;

    foreach ($jadwal_mengajar as $hari => $sesi) {
        foreach ($sesi as $s) {
            if ($s->jam_mulai && $s->jam_selesai) {
                $total_menit += Carbon::parse($s->jam_mulai)
                    ->diffInMinutes(Carbon::parse($s->jam_selesai));
            }
        }
    }

    $total_jam = round($total_menit / 60);

    

    // TES VIEW
    $html = view(
    'guru.informasi-akademik.jadwal.cetak-mengajar',
    [
        'guru' => $guru,
        'jadwal' => $jadwal_mengajar,
        'tahun_ajaran' => $ta_aktif->tahun_ajaran ?? '-',
        'semester' => ucfirst($semester_aktif->nama_semester ?? '-'),
        'total_jam' => $total_jam,
    ]
)->render();

$pdf = Pdf::loadHTML($html)
    ->setPaper('a4', 'portrait');

return $pdf->download(
    'Jadwal_Mengajar_' . $guru->user->nama . '.pdf'
);
}

public function downloadUjian()
{
    $guru = Auth::user()->guru()->with(['user','mapel'])->first();

    // Tahun Ajaran Aktif
    $ta_aktif = TahunAjaran::where('status', 'aktif')->first();

    // Semester Aktif
    $semester_aktif = Semester::where('status', 'aktif')
        ->where('id_tahun_ajaran', $ta_aktif->id_tahun_ajaran ?? null)
        ->first();

    $jadwal_ujian = DetailUjian::with([
            'mapel',
            'jadwalUjian.kelas',
            'jadwalUjian.jenisUjian'
        ])
        ->where('id_pengawas', $guru->id_guru)
        ->orderBy('tanggal', 'asc')
        ->get()
        ->groupBy(function ($val) {
            return Carbon::parse($val->tanggal)
                ->isoFormat('dddd, D MMMM YYYY');
        });

    $pdf = Pdf::loadView(
        'guru.informasi-akademik.jadwal.cetak-ujian',
        [
            'guru' => $guru,
            'jadwal' => $jadwal_ujian,
            'tahun_ajaran' => $ta_aktif->tahun_ajaran ?? '-',
            'semester' => ucfirst($semester_aktif->nama_semester ?? '-'),
        ]
    )->setPaper('a4', 'portrait');

    return $pdf->download(
        'Jadwal_Ujian_' . $guru->user->nama . '.pdf'
    );
}
}