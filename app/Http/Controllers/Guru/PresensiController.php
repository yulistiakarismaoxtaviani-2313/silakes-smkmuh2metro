<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\DetailPresensi;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    /**
     * Tampilan Utama: Daftar Semua Sesi & Kelas
     */
    public function index()
    {
        $guru = Guru::where('id_user', Auth::id())->first();

$hariIndonesia = [
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
    'Saturday'  => 'Sabtu',
];

$hariInggris = now()->format('l');


if (!isset($hariIndonesia[$hariInggris])) {
    return view('guru.presensi.index', [
        'classes' => collect()
    ])->with('info', 'Hari Minggu tidak ada jadwal presensi.');
}

$hariIni = $hariIndonesia[$hariInggris];

$semuaKelas = Kelas::withCount('siswa')
    ->whereIn('id_kelas', function ($query) use ($guru, $hariIni) {
        $query->select('id_kelas')
            ->from('jadwal_pelajaran')
            ->where('id_guru', $guru->id_guru)
            ->where('hari', $hariIni);
    })
    ->get();

        $daftarPresensiHariIni = Presensi::whereDate('tanggal', Carbon::today())
            ->orderBy('jam_pelajaran', 'asc')
            ->get();

        $classes = collect();
        $sekarang = now();

        foreach ($daftarPresensiHariIni as $presensi) {
            foreach ($semuaKelas as $kelas) {
                
                $jumlahSudahAbsen = DetailPresensi::where('id_presensi', $presensi->id_presensi)
    ->whereHas('siswa', function($q) use ($kelas) {
        $q->where('id_kelas', $kelas->id_kelas);
    })
    ->whereIn('status', ['hadir', 'sakit', 'izin', 'alfa'])
    ->count();

    $awalSesi = $presensi->jamPelajaran->jam_mulai;

$jadwalMengajar = JadwalPelajaran::where('id_guru', $guru->id_guru)
    ->where('id_kelas', $kelas->id_kelas)
    ->where('hari', $hariIni)
    ->where('jenis', 'kbm')
    ->whereTime('jam_mulai', '<=', $awalSesi)
    ->whereTime('jam_selesai', '>', $awalSesi)
    ->exists();

if (!$jadwalMengajar) {
    continue;
}

   $sudahDivalidasiGuru = DetailPresensi::where('id_presensi', $presensi->id_presensi)
    ->whereHas('siswa', function ($q) use ($kelas) {
        $q->where('id_kelas', $kelas->id_kelas);
    })
    ->where('keterangan', 'Divalidasi oleh Guru')
    ->exists();

$waktuDibuka = Carbon::parse($presensi->waktu_dibuka);
$waktuDitutup = Carbon::parse($presensi->waktu_ditutup);

if ($sudahDivalidasiGuru || $presensi->status_sesi == 'ditutup') {

    $status = 'Selesai';

} elseif ($presensi->status_sesi == 'dibuka' && $sekarang->between($waktuDibuka, $waktuDitutup)) {

    $status = 'Berlangsung';

} else {

    $status = 'Menunggu';

}

               $classes->push([
    'id'       => $presensi->id_presensi,
    'id_kelas' => $kelas->id_kelas,
    'name'     => $kelas->nama_kelas,
    'date'     => Carbon::parse($presensi->tanggal)->translatedFormat('l, d F Y'),
    'sesi'     => $presensi->jamPelajaran->nama_jam ?? '-',
    'jam'      => $presensi->jam_pelajaran,
    'siswa'    => $kelas->siswa_count,
    'status'   => $status
]);
            }
        }

        return view('guru.presensi.index', compact('classes'));
    }

    /**
     * Tampilan Detail: Daftar Siswa per Kelas
     */
    public function show($id)
    {
        $presensi = Presensi::where('id_presensi', $id)->firstOrFail();
        $id_kelas = request('id_kelas');

        if (!$id_kelas) {

    $route = request()->routeIs('walikelas.*')
        ? 'walikelas.presensi.mengajar'
        : 'guru.presensi.index';

    return redirect()->route($route)
        ->with('error', 'Pilih kelas terlebih dahulu.');
}

        $daftarSiswaKelas = Siswa::with('user')
            ->where('id_kelas', $id_kelas)
            ->get();

        $sudahAbsen = DetailPresensi::where('id_presensi', $id)->get();

        $detail_presensi = $daftarSiswaKelas->map(function($siswa) use ($sudahAbsen) {
            $absen = $sudahAbsen->where('id_siswa', $siswa->id_siswa)->first();
            
            return (object)[
                'id' => $absen ? ($absen->id_detail_presensi ?? $absen->id) : 'siswa_' . $siswa->id_siswa,
                'siswa' => $siswa,
                'status' => $absen ? $absen->status : 'belum',
                'keterangan' => $absen ? $absen->keterangan : '-'
            ];
        });

        $rekap = [
            'total' => $detail_presensi->count(),
            'hadir' => $detail_presensi->where('status', 'hadir')->count(),
            'izin'  => $detail_presensi->where('status', 'izin')->count(),
            'sakit' => $detail_presensi->where('status', 'sakit')->count(),
            'alfa'  => $detail_presensi->where('status', 'alfa')->count(),
            'belum' => $detail_presensi->where('status', 'belum')->count(),
        ];

        $namaKelas = Kelas::find($id_kelas)->nama_kelas ?? 'Kelas';

        return view('guru.presensi.show', compact('presensi', 'rekap', 'detail_presensi', 'namaKelas'));
    }

    /**
     * Handler Update Status via AJAX (Disesuaikan dengan rute 1 parameter)
     */
    public function updateStatusDetail(Request $request, $id)
{

        $request->validate([
            'status' => 'required|in:hadir,belum,izin,sakit,alfa',
            'id_kelas' => 'required',
            'id_presensi' => 'required'
        ]);

        $id_presensi = $request->id_presensi;

        if (strpos($id, 'siswa_') !== false) {
            $idSiswaReal = str_replace('siswa_', '', $id);
            
            $detail = DetailPresensi::updateOrCreate(
                [
                    'id_presensi' => $id_presensi,
                    'id_siswa'    => $idSiswaReal
                ],
                [
                    'status'     => $request->status,
                    'keterangan' => 'Diubah oleh Guru (Koreksi)'
                ]
            );
            $idTargetReal = $detail->id_detail_presensi ?? $detail->id;
        } else {
$detail = DetailPresensi::where('id_detail_presensi', $id)
    ->firstOrFail();
    $detail->status = $request->status;
            $detail->keterangan = 'Diubah oleh Guru (Koreksi)';
            $detail->save();
            $idTargetReal = $id;
        }

        // Hitung ulang statistik data rekap terbaru
        $totalSiswa = Siswa::where('id_kelas', $request->id_kelas)->count();
        $hadir = DetailPresensi::where('id_presensi', $id_presensi)->whereHas('siswa', function($q) use ($request) { $q->where('id_kelas', $request->id_kelas); })->where('status', 'hadir')->count();
        $izin  = DetailPresensi::where('id_presensi', $id_presensi)->whereHas('siswa', function($q) use ($request) { $q->where('id_kelas', $request->id_kelas); })->where('status', 'izin')->count();
        $sakit = DetailPresensi::where('id_presensi', $id_presensi)->whereHas('siswa', function($q) use ($request) { $q->where('id_kelas', $request->id_kelas); })->where('status', 'sakit')->count();
        $alfa  = DetailPresensi::where('id_presensi', $id_presensi)->whereHas('siswa', function($q) use ($request) { $q->where('id_kelas', $request->id_kelas); })->where('status', 'alfa')->count();
        $belum = $totalSiswa - ($hadir + $izin + $sakit + $alfa);

        return response()->json([
            'success' => true,
            'id_aktual' => $idTargetReal,
            'rekapbaru' => [
                'total' => $totalSiswa,
                'hadir' => $hadir,
                'izin'  => $izin,
                'sakit' => $sakit,
                'alfa'  => $alfa,
                'belum' => $belum
            ]
        ]);
    }

    /**
     * Validasi Guru: Mengubah yang 'belum' menjadi 'alfa'
     */
    public function confirm($id)
    {
        $id_kelas = request('id_kelas');
        $daftarSiswa = Siswa::where('id_kelas', $id_kelas)->get();

        foreach ($daftarSiswa as $siswa) {
    DetailPresensi::where('id_presensi', $id)
        ->where('id_siswa', $siswa->id_siswa)
        ->update([
            'keterangan' => 'Divalidasi oleh Guru'
        ]);
}



    
        $route = request()->routeIs('walikelas.*')
    ? 'walikelas.presensi.mengajar'
    : 'guru.presensi.index';

return redirect()->route($route)
    ->with('success', "Presensi kelas berhasil divalidasi dan dikunci.");}

    /**
     * Fitur Tambahan: Tandai sudah diingatkan
     */
    public function markAllReminded($id)
    {
        $id_kelas = request('id_kelas');

        DetailPresensi::where('id_presensi', $id)
            ->whereHas('siswa', function($query) use ($id_kelas) {
                $query->where('id_kelas', $id_kelas);
            })
            ->where('status', 'belum')
            ->update(['keterangan' => 'Sudah diingatkan oleh guru']);

        return back()->with('success', 'Berhasil menandai siswa yang belum absen.');
    }
}