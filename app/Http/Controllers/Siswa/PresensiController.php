<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\DetailPresensi;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresensiController extends Controller
{
    /**
     * Tampilan Utama: Daftar Sesi Presensi Hari Ini & Rekap Mandiri
     */
    public function index()
    {
        $siswa = Auth::user()->siswa;

        // Ambil semua data untuk dropdown
$tahunAjaranList = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();
$semesterList = Semester::all();

// Ambil filter dari URL
$id_tahun_ajaran = request('id_tahun_ajaran');
$id_semester = request('id_semester');

// Default ke yang aktif jika belum dipilih
if (!$id_tahun_ajaran) {
    $id_tahun_ajaran = TahunAjaran::where('status', 'aktif')
        ->value('id_tahun_ajaran');
}

if (!$id_semester) {
    $id_semester = Semester::where('status', 'aktif')
        ->value('id_semester');
}

$tahunAjaran = TahunAjaran::find($id_tahun_ajaran);
$semester = Semester::find($id_semester);

$tahun = explode('/', $tahunAjaran->tahun_ajaran);

$awalTahun = $tahun[0];
$akhirTahun = $tahun[1];

if ($semester->nama_semester == 'ganjil') {
    $tanggalMulai = $awalTahun . '-07-01';
    $tanggalSelesai = $awalTahun . '-12-31';
} else {
    $tanggalMulai = $akhirTahun . '-01-01';
    $tanggalSelesai = $akhirTahun . '-06-30';
}

        // 1. Ambil Semua Riwayat Presensi untuk Rekap & Tabel
        $riwayat_semua = Presensi::with(['details' => function($q) use ($siswa) {
        $q->where('id_siswa', $siswa->id_siswa);
    }])
    ->whereBetween('tanggal', [
        $tanggalMulai,
        $tanggalSelesai
    ])
    ->orderBy('tanggal', 'desc')
    ->get();

        // 2. Logika Rekap Bulanan (Januari - Juni)
        $rekap_bulanan = [];
        $rekap_bulanan = [];

if ($semester->nama_semester == 'ganjil') {
    $bulanList = [7,8,9,10,11,12];
} else {
    $bulanList = [1,2,3,4,5,6];
}

foreach ($bulanList as $m) {
    $namaBulan = Carbon::create()->month($m)->isoFormat('MMMM');

    $rekap_bulanan[$namaBulan] = [
        'hadir' => 0,
        'sakit' => 0,
        'izin' => 0,
        'alfa' => 0,
        'total' => 0
    ];
}
        

        $rekap_total = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alfa' => 0, 'total' => 0];

        foreach ($riwayat_semua as $sesi) {
            $absen = $sesi->details->first();
            $bulanSesi = Carbon::parse($sesi->tanggal)->isoFormat('MMMM');

            // Baca status aslinya langsung dari database hasil update siswa
            if ($absen) {
                $status_final = $absen->status;
            } else {
                $status_final = Carbon::now()->gt(Carbon::parse($sesi->waktu_ditutup)) ? 'alfa' : 'belum';
            }

            if (isset($rekap_bulanan[$bulanSesi]) && $status_final !== 'belum') {
                $rekap_bulanan[$bulanSesi][$status_final]++;
                $rekap_bulanan[$bulanSesi]['total']++;
                
                $rekap_total[$status_final]++;
                $rekap_total['total']++;
            }
        }

        // 3. Ambil Sesi Hari Ini yang Berstatus Terbuka
        $sesi_hari_ini = Presensi::whereDate('tanggal', Carbon::today())
    ->with(['details' => function($q) use ($siswa) {
        $q->where('id_siswa', $siswa->id_siswa);
    }])
    ->orderBy('waktu_dibuka')
    ->get();

        // Di halaman depan (Tab Hari Ini), kita ingin sesi TETAP MUNCUL 
        // agar siswa bisa melihat status barunya yang berubah menjadi "Terabsen: Hadir"
        $sesi_aktif = $sesi_hari_ini->filter(function($sesi) {
            $absenSiswa = $sesi->details->first();
            // Loloskan semua sesi hari ini selama recordnya ada di kelas itu
            return true;
        });

        // 4. Proses format data untuk tabel riwayat di view
        $data_riwayat = $this->formatRiwayat($riwayat_semua);

        return view('siswa.presensi.index', [
            'sesi_aktif'    => $sesi_aktif,
            'rekap_bulanan' => $rekap_bulanan,
            'rekap'         => $rekap_total, 
            'rekap_total'   => $rekap_total,
            'riwayat'       => $data_riwayat,
            'tahunAjaranList' => $tahunAjaranList,
            'semesterList' => $semesterList,
            'id_tahun_ajaran' => $id_tahun_ajaran,
            'id_semester' => $id_semester
        ]);
    }

    /**
     * Helper Function: Memformat data riwayat agar rapi saat dibaca Blade
     */
    private function formatRiwayat($riwayat_presensi) 
    {
        $data = [];
        foreach ($riwayat_presensi as $sesi) {
            $absen = $sesi->details->first();
            $status_final = $absen ? $absen->status : (Carbon::now()->gt(Carbon::parse($sesi->waktu_ditutup)) ? 'alfa' : 'belum');

            $data[] = [
                'tanggal' => $sesi->tanggal,
                'jam_pelajaran' => $sesi->jam_pelajaran,
                'status' => $status_final,
                // Pastikan waktu updated_at terbaca saat status sudah bukan alfa lagi
                'waktu_absen' => ($absen && !in_array($status_final, ['alfa', 'belum'])) ? Carbon::parse($absen->updated_at)->format('H:i') : '-',
                'file_bukti' => $absen ? $absen->file_bukti : null,
            ];
        }
        return $data; 
    }

    /**
     * Tampilkan Form Absensi 
     */
    public function create($id)
    {
        $sesi = Presensi::findOrFail($id);
        $id_siswa = Auth::user()->siswa->id_siswa;

        $detail = DetailPresensi::where('id_presensi', $id)
            ->where('id_siswa', $id_siswa)
            ->first();

       // Jika sudah divalidasi guru, siswa tidak boleh absen lagi


// Jika sudah pernah mengisi absensi
if (
    $detail &&
    $detail->status == 'alfa' &&
    $detail->keterangan == 'Divalidasi oleh Guru (Otomatis Alfa)'
) {
    return redirect()->route('siswa.presensi.index')
        ->with('error', 'Presensi telah divalidasi guru dan tidak dapat diubah.');
}

        $sekarang = Carbon::now();
        if (!$sekarang->between($sesi->waktu_dibuka, $sesi->waktu_ditutup)) {
            return redirect()->route('siswa.presensi.index')->with('error', 'Waktu absensi sudah berakhir atau belum dibuka.');
        }

        return view('siswa.presensi.form', compact('sesi'));
    }

    /**
     * Simpan Konfirmasi Data Absensi (Melakukan Update Data)
     */
    public function store(Request $request)
    {

    
        $request->validate([
            'id_presensi' => 'required|exists:presensi,id_presensi',
            'status' => 'required|in:hadir,alfa,izin,sakit',
            'keterangan' => 'nullable|string',
            'file_busca' => 'nullable|image|mimes:jpg,png,jpeg|max:2048' // sesuaikan name file_bukti di form kamu jika ada
        ]);

        $id_siswa = Auth::user()->siswa->id_siswa;

$detail = DetailPresensi::where('id_presensi', $request->id_presensi)
    ->where('id_siswa', $id_siswa)
    ->first();

// Jika sudah divalidasi guru
if (
    $detail &&
    $detail->status == 'alfa' &&
    $detail->keterangan == 'Divalidasi oleh Guru (Otomatis Alfa)'
) {
    return redirect()->route('siswa.presensi.index')
        ->with('error', 'Presensi telah divalidasi guru dan tidak dapat diubah.');
}

// Jika sudah pernah absen
if ($detail && $detail->status !== 'alfa') {
    return redirect()->route('siswa.presensi.index')
        ->with('error', 'Kamu sudah melakukan absensi sebelumnya.');
}

// Jika sudah pernah absen
if ($detail && $detail->status !== 'alfa') {
    return redirect()->route('siswa.presensi.index')
        ->with('error', 'Kamu sudah melakukan absensi sebelumnya.');
}
        $namaFile = $detail ? $detail->file_bukti : null;
        if ($request->hasFile('file_bukti')) {
            $file = $request->file('file_bukti');
            $namaFile = 'bukti_' . time() . '_' . $id_siswa . '.' . $file->getClientOriginalExtension();
            
            if (!file_exists(public_path('storage/bukti_absen'))) {
                mkdir(public_path('storage/bukti_absen'), 0777, true);
            }
            $file->move(public_path('storage/bukti_absen'), $namaFile);
        }

        // PROSES UPDATE: Mengubah status default 'alfa' dari admin menjadi pilihan siswa
       DetailPresensi::updateOrCreate(
    [
        'id_presensi' => $request->id_presensi,
        'id_siswa' => $id_siswa,
    ],
    [
        'status' => $request->status,
        'keterangan' => $request->keterangan,
        'file_bukti' => $namaFile,
    ]
);

        return redirect()->route('siswa.presensi.index')->with('success', 'Absensi berhasil dikirim!');
    }

    /**
     * Rekap Presensi Riwayat Pribadi Siswa
     */
    public function rekap()
    {
        $siswa = Auth::user()->siswa; 

        $riwayat = DetailPresensi::where('id_siswa', $siswa->id_siswa)
            ->with('presensi') 
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('siswa.presensi.rekap', compact('riwayat'));
    }

    public function unduhRekap(Request $request)
{
    $siswa = Auth::user()->siswa;

    $tahunAjaran = TahunAjaran::findOrFail(
        $request->id_tahun_ajaran
    );

    $semester = Semester::findOrFail(
        $request->id_semester
    );

    $tahun = explode('/', $tahunAjaran->tahun_ajaran);

    $awalTahun = $tahun[0];
    $akhirTahun = $tahun[1];

    if ($semester->nama_semester == 'ganjil') {
        $tanggalMulai = $awalTahun . '-07-01';
        $tanggalSelesai = $awalTahun . '-12-31';

        $bulanList = [7,8,9,10,11,12];
    } else {
        $tanggalMulai = $akhirTahun . '-01-01';
        $tanggalSelesai = $akhirTahun . '-06-30';

        $bulanList = [1,2,3,4,5,6];
    }

    $riwayat = Presensi::with([
        'details' => function ($q) use ($siswa) {
            $q->where('id_siswa', $siswa->id_siswa);
        }
    ])
    ->whereBetween('tanggal', [
        $tanggalMulai,
        $tanggalSelesai
    ])
    ->get();

    $rekap_bulanan = [];

    foreach ($bulanList as $m) {
        $namaBulan = Carbon::create()->month($m)->isoFormat('MMMM');

        $rekap_bulanan[$namaBulan] = [
            'hadir' => 0,
            'sakit' => 0,
            'izin'  => 0,
            'alfa'  => 0,
            'total' => 0,
        ];
    }

    $rekap_total = [
        'hadir' => 0,
        'sakit' => 0,
        'izin'  => 0,
        'alfa'  => 0,
        'total' => 0,
    ];

    foreach ($riwayat as $sesi) {

        $absen = $sesi->details->first();

        if (!$absen) {
            continue;
        }

        $bulan = Carbon::parse($sesi->tanggal)
            ->isoFormat('MMMM');

        if (isset($rekap_bulanan[$bulan])) {

            $rekap_bulanan[$bulan][$absen->status]++;
            $rekap_bulanan[$bulan]['total']++;

            $rekap_total[$absen->status]++;
            $rekap_total['total']++;
        }
    }

    $pdf = Pdf::loadView(
        'siswa.presensi.rekap_pdf',
        compact(
            'siswa',
            'semester',
            'tahunAjaran',
            'rekap_bulanan',
            'rekap_total'
        )
    );

    $namaSiswa = str_replace(' ', '_', $siswa->user->nama);

return $pdf->download(
    'Rekap_Presensi_' .
    $namaSiswa . '_' .
    $siswa->nis .
    '.pdf'
    );
}
}