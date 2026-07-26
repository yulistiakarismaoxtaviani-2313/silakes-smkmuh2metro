<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use App\Models\Presensi;
use App\Models\DetailPresensi;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\JadwalPelajaran; 
use App\Models\JamPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Data Dropdown
        $daftar_kelas = Kelas::all();

        // 2. Query Utama
        $query = Presensi::query();

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status_sesi', $request->status);
        }

        // Filter Jam Pelajaran (Mendukung pencarian potongan jam digital, misal: "08:00")
        if ($request->jam) {
    $query->where('id_jam', $request->jam);
}

        // Filter Search (Tanggal)
        if ($request->filled('search')) {
            $query->whereDate('tanggal', $request->search);
        }

        // Filter Kelas
        if ($request->filled('kelas')) {
            $query->whereHas('details.siswa', function($q) use ($request) {
                $q->where('id_kelas', $request->kelas);
            });
        }

        $presensis = $query->orderBy('tanggal', 'desc')->paginate(10);

        // 3. Statistik Real-time Hari Ini
        $total_kelas_db = Kelas::count();
        
        $kelas_sudah_count = DetailPresensi::whereHas('presensi', function($q) {
                $q->whereDate('tanggal', Carbon::today());
            })
            ->whereIn('detail_presensi.status', ['hadir', 'sakit', 'izin']) 
            ->join('siswa', 'detail_presensi.id_siswa', '=', 'siswa.id_siswa')
            ->distinct('siswa.id_kelas')
            ->count('siswa.id_kelas');

        $kelas_belum_count = max(0, $total_kelas_db - $kelas_sudah_count);

        $presensi_hari_ini = Presensi::whereDate('tanggal', Carbon::today())->get();
        
        $stats = [
            'total_siswa'   => Siswa::count(),
            'total_kelas'   => $total_kelas_db,
            'presensi_buka' => $presensi_hari_ini->where('status_sesi', 'dibuka')->count(),
            'presensi_tutup'=> $presensi_hari_ini->where('status_sesi', 'ditutup')->count(),
            'kelas_sudah'   => $kelas_sudah_count,
            'kelas_belum'   => $kelas_belum_count,
        ];

        $jamPelajaran = JamPelajaran::orderBy('id_jam')->get();
        $tahun_aktif = TahunAjaran::where('status', 'aktif')->first();
        return view('admin.presensi.index', compact('presensis', 'stats', 'daftar_kelas', 'tahun_aktif', 'jamPelajaran'));
    }

    /**
     * Membuat Sesi Presensi Massal Berdasarkan Rentang Tanggal dan Master Jadwal Pelajaran
     * Hari Minggu dilewati otomatis, hari Sabtu TETAP MASUK.
     */
    public function store(Request $request)
    {
        // 1. Validasi rentang tanggal
        $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan'      => 'nullable|string',
        ]);

        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($request->tanggal_selesai);

        // Buat rentang tanggal menggunakan Carbon Period
        $period = CarbonPeriod::create($tanggalMulai, $tanggalSelesai);

        // Mapping hari standar Indonesia sesuai isi database
        $daftarHari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu'
        ];

        $totalSesiDibuat = 0;

        DB::beginTransaction();

        try {
            // Loop setiap tanggal dalam rentang pilihan admin
            foreach ($period as $date) {
                
                // Ambil angka hari (1 = Senin, 6 = Sabtu, 7 = Minggu) menggunakan format standar 'N'
                $angkaHari = (int) $date->format('N');

                // A. SKIP HARI MINGGU SAJA
                if ($angkaHari === 7) {
                    continue;
                }

                // Antisipasi jika angka hari di luar range mapping
                if (!array_key_exists($angkaHari, $daftarHari)) {
                    continue;
                }

                $namaHari = $daftarHari[$angkaHari];

                // B. AMBIL MASTER JADWAL DI HARI TERSEBUT
                $jadwals = JadwalPelajaran::where('hari', $namaHari)
                          ->where('jenis', 'kbm') // <--- FILTER INI KUNCINYA
                          ->get();

$semuaSesi = JamPelajaran::orderBy('jam_mulai')->get();

                foreach ($jadwals as $jadwal) {

    $jadwalMulai = Carbon::createFromFormat('H:i:s', $jadwal->jam_mulai);
    $jadwalSelesai = Carbon::createFromFormat('H:i:s', $jadwal->jam_selesai);

    foreach ($semuaSesi as $sesi) {

        $sesiMulai = Carbon::createFromFormat('H:i:s', $sesi->jam_mulai);
        $sesiSelesai = Carbon::createFromFormat('H:i:s', $sesi->jam_selesai);

        // cek apakah jadwal mencakup sesi ini
        if (!($jadwalMulai <= $sesiMulai && $jadwalSelesai >= $sesiSelesai)) {
            continue;
        }

        $labelJamPelajaran = substr($sesi->jam_mulai, 0, 5)
            . ' - ' .
            substr($sesi->jam_selesai, 0, 5);

        $waktuDibukaSesi = $date->format('Y-m-d') . ' ' . $sesi->jam_mulai;
        $waktuDitutupSesi = $date->format('Y-m-d') . ' ' . $sesi->jam_selesai;

        $statusOtomatis = Carbon::parse($waktuDitutupSesi)->isPast()
            ? 'ditutup'
            : 'dibuka';

        $presensi = Presensi::firstOrCreate(
    [
        'tanggal' => $date->format('Y-m-d'),
        'id_jam'  => $sesi->id_jam,
    ],
    [
        'id_jam'        => $sesi->id_jam,
        'jam_pelajaran' => $labelJamPelajaran,
        'keterangan'    => $request->keterangan ?? "Generasi otomatis presensi mata pelajaran.",
        'status_sesi'   => $statusOtomatis,
        'waktu_dibuka'  => $waktuDibukaSesi,
        'waktu_ditutup' => $waktuDitutupSesi,
    ]
);

        $siswaDiKelas = Siswa::where('id_kelas', $jadwal->id_kelas)->get();

        $existingSiswa = DetailPresensi::where('id_presensi', $presensi->id_presensi)
            ->pluck('id_siswa')
            ->toArray();

        $dataDetail = [];

        foreach ($siswaDiKelas as $siswa) {

            if (in_array($siswa->id_siswa, $existingSiswa)) {
                continue;
            }

            $dataDetail[] = [
                'id_presensi' => $presensi->id_presensi,
                'id_siswa'    => $siswa->id_siswa,
                'status'      => 'alfa',
                'keterangan'  => null,
                'file_bukti'  => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        if (!empty($dataDetail)) {
            DetailPresensi::insert($dataDetail);
        }

        $totalSesiDibuat++;
    }
}
            }

            // Jika tidak ditemukan jadwal pelajaran di sepanjang rentang tanggal terpilih
            if ($totalSesiDibuat === 0) {
                DB::rollback();
                return redirect()->back()->with('error', 'Tidak ada master jadwal pelajaran baru yang ditemukan untuk digenerate pada rentang tanggal tersebut.');
            }

            DB::commit();
            return redirect()->route('admin.presensi.index')->with('success', "Berhasil memproses otomatis {$totalSesiDibuat} sesi presensi baru. Sesi yang telah lewat waktunya otomatis dikunci (Ditutup).");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memproses otomatisasi presensi: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $daftar_kelas = Kelas::all();
        return view('admin.presensi.create', compact('daftar_kelas'));
    }

    public function show(Request $request, $id)
    {
        $presensi = Presensi::findOrFail($id);

        $query = Kelas::query()
            ->leftJoin('siswa', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->leftJoin('detail_presensi', function($join) use ($id) {
                $join->on('siswa.id_siswa', '=', 'detail_presensi.id_siswa')
                     ->where('detail_presensi.id_presensi', '=', $id);
            })
            ->select(
                'kelas.nama_kelas',
                'kelas.id_kelas',
                DB::raw('COUNT(DISTINCT siswa.id_siswa) as total_siswa'),
                DB::raw("SUM(CASE WHEN detail_presensi.status = 'hadir' THEN 1 ELSE 0 END) as hadir"),
                DB::raw("SUM(CASE WHEN detail_presensi.status = 'izin' THEN 1 ELSE 0 END) as izin"),
                DB::raw("SUM(CASE WHEN detail_presensi.status = 'sakit' THEN 1 ELSE 0 END) as sakit"),
                DB::raw("SUM(CASE WHEN detail_presensi.status = 'alfa' THEN 1 ELSE 0 END) as alfa")
            )
            ->groupBy('kelas.id_kelas', 'kelas.nama_kelas');

        if ($request->filled('search')) {
            $query->where('kelas.nama_kelas', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('id_kelas')) {
            $query->where('kelas.id_kelas', $request->id_kelas);
        }

        $rekap_kelas = $query->paginate(10);

        $total_stats = [
            'hadir' => DB::table('detail_presensi')->where('id_presensi', $id)->where('status', 'hadir')->count(),
            'izin'  => DB::table('detail_presensi')->where('id_presensi', $id)->where('status', 'izin')->count(),
            'sakit' => DB::table('detail_presensi')->where('id_presensi', $id)->where('status', 'sakit')->count(),
            'alfa'  => DB::table('detail_presensi')->where('id_presensi', $id)->where('status', 'alfa')->count(),
        ];

        $daftar_kelas = Kelas::all();

        return view('admin.presensi.show', compact('presensi', 'rekap_kelas', 'total_stats', 'daftar_kelas'));
    }

    public function showKelas(Request $request, $id_presensi, $id_kelas)
    {
        $presensi = Presensi::findOrFail($id_presensi);
        $kelas = Kelas::findOrFail($id_kelas);

        $query = Siswa::query()
            ->join('users', 'siswa.id_user', '=', 'users.id_user')
            ->join('detail_presensi', 'siswa.id_siswa', '=', 'detail_presensi.id_siswa')
            ->where('detail_presensi.id_presensi', $id_presensi)
            ->where('siswa.id_kelas', $id_kelas)
            ->select(
                'siswa.id_siswa',
                'siswa.nis',
                'users.nama as nama_siswa',
                'detail_presensi.status',
                'detail_presensi.keterangan'
            );

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('users.nama', 'like', '%' . $request->search . '%')
                  ->orWhere('siswa.nis', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('detail_presensi.status', $request->status);
        }

        $siswa = $query->get();

        return view('admin.presensi.show-detail-siswa', compact('presensi', 'kelas', 'siswa'));
    }

    /**
     * Menghapus satu sesi presensi beserta seluruh data detail siswa di dalamnya.
     * Fitur andalan jika admin ingin menghapus jadwal presensi di Tanggal Merah.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $presensi = Presensi::findOrFail($id);

            // 1. Hapus semua detail siswa terlebih dahulu (Mencegah error Constraint FK)
            DetailPresensi::where('id_presensi', $id)->delete();

            // 2. Hapus sesi induk presensi
            $presensi->delete();

            DB::commit();
            return redirect()->route('admin.presensi.index')->with('success', 'Sesi presensi pada jam tersebut berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus sesi presensi: ' . $e->getMessage());
        }
    }
}