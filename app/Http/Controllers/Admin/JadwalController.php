<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Import Semua Model yang Dibutuhkan
use App\Models\User;
use App\Models\JadwalPelajaran;
use App\Models\JadwalUjian;
use App\Models\DetailUjian;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\JenisUjian;

class JadwalController extends Controller
{
    /**
     * Menampilkan daftar jadwal (Pelajaran & Ujian) dengan fitur Filter
     */
    public function index(Request $request)
    {
        // Data untuk dropdown filter
        $semester = Semester::all();
        $kelas = Kelas::all();
        $jenisUjian = JenisUjian::all();

        // Ambil status tab dari URL, default-nya 'pelajaran'
        $tab = $request->get('tab', 'pelajaran');

        // Statistik
        $totalJadwal = JadwalPelajaran::distinct('id_kelas')->count('id_kelas') + JadwalUjian::count();
        $totalKelas = Kelas::count();
        $totalGuru = Guru::count();
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first()->tahun_ajaran ?? 'N/A';

        // Query Jadwal Pelajaran (Paginasi per KELAS)
        $pelajaran = Kelas::with(['jadwalPelajaran.mapel', 'guru.user'])
            ->whereHas('jadwalPelajaran', function($query) use ($request) {
                if ($request->id_semester) {
                    $query->where('id_semester', $request->id_semester);
                }
                if ($request->search) {
                    $query->where(function($q) use ($request) {
                        $q->whereHas('mapel', function($subQ) use ($request) {
                            $subQ->where('nama_mapel', 'like', '%' . $request->search . '%');
                        })->orWhere('kegiatan_kustom', 'like', '%' . $request->search . '%');
                    });
                }
            })
            ->when($request->id_kelas, function ($query) use ($request) {
                return $query->where('id_kelas', $request->id_kelas);
            })
            ->paginate(10); // Menampilkan 10 KELAS per halaman

        // Query Jadwal Ujian dengan Filter
        $ujian = JadwalUjian::with(['kelas.guru.user', 'details', 'jenisUjian'])
            ->when($request->id_semester, function ($query) use ($request) {
                return $query->where('id_semester', $request->id_semester);
            })
            ->when($request->id_kelas, function ($query) use ($request) {
                return $query->where('id_kelas', $request->id_kelas);
            })
            ->when($request->id_jenis_ujian, function ($query) use ($request) {
                return $query->where('id_jenis_ujian', $request->id_jenis_ujian);
            })
            ->when($request->search, function ($query) use ($request) {
                return $query->where('judul', 'like', '%' . $request->search . '%');
            })
            ->latest() 
            ->paginate(10); 

        return view('admin.jadwal.index', compact(
            'pelajaran', 'ujian', 'tab', 'semester', 'kelas', 'jenisUjian',
            'totalJadwal', 'totalKelas', 'totalGuru', 'tahunAjaranAktif'
        ));
    }

    /**
     * Form Tambah Jadwal Pelajaran
     */
    public function createPelajaran()
    {
        $kelas = Kelas::all();
        $mapel = Mapel::all();
        $guru = Guru::with('user')->get(); 

        // Cari data yang sedang aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $semesterAktif = Semester::where('status', 'Aktif')->first();

        return view('admin.jadwal.create_pelajaran', compact(
            'kelas', 'guru', 'mapel', 'tahunAjaranAktif', 'semesterAktif'
        ));
    }

    /**
     * Simpan Jadwal Pelajaran (Amankan data Jenis & Kegiatan Kustom)
     */
    public function storePelajaran(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required',
            'inputs.*.hari' => 'required',
            'inputs.*.jam_mulai' => 'required',
            'inputs.*.jam_selesai' => 'required',
            'inputs.*.jenis' => 'required',
            'inputs.*.id_mapel' => 'nullable',
            'inputs.*.id_guru' => 'nullable',
            'inputs.*.kegiatan_kustom' => 'nullable',
        ]);

        $ta = TahunAjaran::where('status', 'Aktif')->first();
        $smstr = Semester::where('status', 'Aktif')->first();

        if (!$ta || !$smstr) {
            return back()->withInput()->with('error', 'Gagal: Pastikan ada Tahun Ajaran dan Semester yang berstatus Aktif!');
        }

        try {
            DB::transaction(function () use ($request, $ta, $smstr) {
                foreach ($request->inputs as $value) {
                    // Deteksi jenis murni dari form submission
                    $jenisBaris = $value['jenis'] ?? 'kbm';
                    
                    // Fallback jika salah kirim state dari frontend
                    if ($jenisBaris === 'kbm' && empty($value['id_mapel'])) {
                        $jenisBaris = 'non_kbm'; 
                    }

                    $isKbm = $jenisBaris === 'kbm';
                    $kegiatanKustom = $value['kegiatan_kustom'] ?? null;
                    
                    // Isi teks otomatis jika tidak diinput oleh user agar tidak null di DB
                    if (!$isKbm && empty($kegiatanKustom)) {
                        $kegiatanKustom = ($jenisBaris === 'istirahat') ? 'Istirahat' : 'Kegiatan Non-KBM';
                    }

                    JadwalPelajaran::create([
                        'id_kelas'        => $request->id_kelas,
                        'id_tahun_ajaran' => $ta->id_tahun_ajaran ?? $ta->id,
                        'id_semester'     => $smstr->id_semester ?? $smstr->id,
                        'hari'            => $value['hari'],
                        'jam_mulai'       => $value['jam_mulai'],
                        'jam_selesai'     => $value['jam_selesai'],
                        'jenis'           => $jenisBaris, 
                        'id_mapel'        => $isKbm ? $value['id_mapel'] : null,
                        'id_guru'         => $isKbm ? $value['id_guru'] : null,
                        'kegiatan_kustom' => !$isKbm ? $kegiatanKustom : null,
                    ]);
                }
            });

            return redirect()->route('admin.jadwal.index', ['tab' => 'pelajaran'])
                             ->with('success', 'Jadwal Pelajaran Berhasil Disimpan');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal simpan: ' . $e->getMessage());
        }
    }

    public function showPelajaran($id_kelas)
    {
        $kelas = Kelas::with(['guru.user'])->findOrFail($id_kelas);
        $jadwalData = JadwalPelajaran::with(['mapel', 'guru.user', 'semester', 'tahunAjaran'])
                    ->where('id_kelas', $id_kelas)
                    ->get();
        $infoJadwal = $jadwalData->first();
        $jadwal = $jadwalData->groupBy('hari');

        return view('admin.jadwal.show_pelajaran', compact('kelas', 'jadwal', 'infoJadwal'));
    }

    public function editPelajaran($id_kelas)
    {
        $kelas = Kelas::findOrFail($id_kelas);
        $guru = Guru::with('user')->get();
        $mapel = Mapel::all();
        
        // Ambil jadwal pelajaran khusus kelas ini berserta relasinya
        $jadwal = JadwalPelajaran::with(['semester', 'tahunAjaran'])
                    ->where('id_kelas', $id_kelas)
                    ->get();

        // Ambil data aktif dari DB sebagai backup jika data jadwal kelas masih kosong
        $semesterAktif = Semester::where('status', 'Aktif')->first();
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();

        return view('admin.jadwal.edit_pelajaran', compact(
            'kelas', 'guru', 'mapel', 'jadwal', 'semesterAktif', 'tahunAjaranAktif'
        ));
    }

    /**
     * Update Jadwal Pelajaran (Amankan data Jenis & Kegiatan Kustom)
     */
    public function updatePelajaran(Request $request, $id_kelas)
    {
        $request->validate([
            'id_tahun_ajaran' => 'required',
            'id_semester' => 'required',
            'inputs.*.hari' => 'required',
            'inputs.*.jam_mulai' => 'required',
            'inputs.*.jam_selesai' => 'required',
            'inputs.*.jenis' => 'required',
            'inputs.*.id_mapel' => 'nullable',
            'inputs.*.id_guru' => 'nullable',
            'inputs.*.kegiatan_kustom' => 'nullable',
        ]);

        try {
            DB::transaction(function () use ($request, $id_kelas) {
                // Hapus jadwal lama milik kelas ini sebelum diganti yang baru
                JadwalPelajaran::where('id_kelas', $id_kelas)->delete();
                
                foreach ($request->inputs as $value) {
                    // Deteksi jenis murni dari form submission
                    $jenisBaris = $value['jenis'] ?? 'kbm';
                    
                    if ($jenisBaris === 'kbm' && empty($value['id_mapel'])) {
                        $jenisBaris = 'non_kbm';
                    }

                    $isKbm = $jenisBaris === 'kbm';
                    $kegiatanKustom = $value['kegiatan_kustom'] ?? null;
                    
                    if (!$isKbm && empty($kegiatanKustom)) {
                        $kegiatanKustom = ($jenisBaris === 'istirahat') ? 'Istirahat' : 'Kegiatan Non-KBM';
                    }

                    JadwalPelajaran::create([
                        'id_kelas'        => $id_kelas,
                        'id_tahun_ajaran' => $request->id_tahun_ajaran,
                        'id_semester'     => $request->id_semester,
                        'hari'            => $value['hari'],
                        'jam_mulai'       => $value['jam_mulai'],
                        'jam_selesai'     => $value['jam_selesai'],
                        'jenis'           => $jenisBaris, 
                        'id_mapel'        => $isKbm ? $value['id_mapel'] : null,
                        'id_guru'         => $isKbm ? $value['id_guru'] : null,
                        'kegiatan_kustom' => !$isKbm ? $kegiatanKustom : null,
                    ]);
                }
            });

            return redirect()->route('admin.jadwal.index', ['tab' => 'pelajaran'])
                             ->with('success', 'Jadwal Berhasil Diperbarui');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    public function destroyPelajaran($id_kelas)
    {
        try {
            $deleted = JadwalPelajaran::where('id_kelas', $id_kelas)->delete();
            return redirect()->route('admin.jadwal.index', ['tab' => 'pelajaran'])->with('success', 'Seluruh Jadwal Kelas Berhasil Dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }

    /* --- JADWAL UJIAN --- */

    public function createUjian()
    {
        $mapel = Mapel::all();
        $pengawas = Guru::with('user')->get();
        $ruangan = Kelas::all(); 
        
        $semesterAktif = Semester::where('status', 'Aktif')->first();
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        
        $jenis_ujian = JenisUjian::all();
        $kelas = Kelas::all();
        $semester = Semester::all();
        $tahun_ajaran = TahunAjaran::all();

        return view('admin.jadwal.create_ujian', compact(
            'mapel', 'pengawas', 'ruangan', 'semesterAktif', 
            'tahunAjaranAktif', 'jenis_ujian', 'kelas', 'semester', 'tahun_ajaran'
        ));
    }

    public function storeUjian(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'id_kelas' => 'required',
            'id_jenis_ujian' => 'required',
            'details' => 'required|array',
            'details.*.tanggal' => 'required|date',
            'details.*.id_mapel' => 'required',
            'details.*.jam_mulai' => 'required',
            'details.*.jam_selesai' => 'required',
            'details.*.id_pengawas' => 'required',
            'details.*.hari' => 'required',
        ]);

        $ta = TahunAjaran::where('status', 'Aktif')->first();
        $smstr = Semester::where('status', 'Aktif')->first();

        if (!$ta || !$smstr) {
            return back()->withInput()->with('error', 'Gagal Simpan: Tahun Ajaran atau Semester Aktif belum ditentukan di pengaturan database.');
        }

        try {
            DB::transaction(function () use ($request, $ta, $smstr) {
                $ujian = JadwalUjian::create([
                    'judul'           => $request->judul,
                    'id_kelas'        => $request->id_kelas,
                    'id_tahun_ajaran' => $ta->id_tahun_ajaran ?? $ta->id, 
                    'id_semester'     => $smstr->id_semester ?? $smstr->id,
                    'id_jenis_ujian'  => $request->id_jenis_ujian,
                ]);

                foreach ($request->details as $item) {
                    DetailUjian::create([
                        'id_jadwal_ujian' => $ujian->id_jadwal_ujian ?? $ujian->id,
                        'tanggal'         => $item['tanggal'],
                        'jam_mulai'       => $item['jam_mulai'],
                        'jam_selesai'     => $item['jam_selesai'],
                        'id_mapel'        => $item['id_mapel'],
                        'id_pengawas'     => $item['id_pengawas'],
                        'ruangan'         => $item['ruangan'] ?? '-',
                        'hari'            => $item['hari'],
                    ]);
                }
            });

            return redirect()->route('admin.jadwal.index', ['tab' => 'ujian'])->with('success', 'Jadwal Ujian Berhasil Diterbitkan');
            
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function showUjian($id)
    {
        $ujian = JadwalUjian::with(['kelas.guru.user', 'details.mapel', 'details.pengawas.user', 'semester', 'tahunAjaran', 'jenisUjian'])
                ->findOrFail($id);
        return view('admin.jadwal.show_ujian', compact('ujian'));
    }

    public function editUjian($id)
    {
        $ujian = JadwalUjian::with('details')->findOrFail($id);
        $kelas = Kelas::all();
        $jenis_ujian = JenisUjian::all();
        $semester = Semester::all();
        $tahun_ajaran = TahunAjaran::all();
        $mapel = Mapel::all();
        $pengawas = Guru::with('user')->get();
        $ruangan = Kelas::select('nama_kelas')->distinct()->get();

        return view('admin.jadwal.edit_ujian', compact('ujian', 'kelas', 'jenis_ujian', 'semester', 'tahun_ajaran', 'mapel', 'pengawas', 'ruangan'));
    }

    public function updateUjian(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'id_kelas' => 'required',
            'details' => 'required|array',
            'details.*.hari' => 'required',
            'details.*.tanggal' => 'required|date',
            'details.*.jam_mulai' => 'required',
            'details.*.jam_selesai' => 'required',
            'details.*.id_mapel' => 'required',
            'details.*.id_pengawas' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $ujian = JadwalUjian::findOrFail($id);
                $ujian->update([
                    'judul' => $request->judul,
                    'id_kelas' => $request->id_kelas,
                ]);

                $ujian->details()->delete();

                foreach ($request->details as $row) {
                    $ujian->details()->create([
                        'hari' => $row['hari'],
                        'tanggal' => $row['tanggal'],
                        'jam_mulai' => $row['jam_mulai'],
                        'jam_selesai' => $row['jam_selesai'],
                        'id_mapel' => $row['id_mapel'],
                        'id_pengawas' => $row['id_pengawas'],
                        'ruangan' => $row['ruangan'],
                    ]);
                }
            });

            return redirect()->route('admin.jadwal.index', ['tab' => 'ujian'])->with('success', 'Jadwal Ujian berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroyUjian($id)
    {
        try {
            DB::transaction(function () use ($id) {
                DetailUjian::where('id_jadwal_ujian', $id)->delete();
                JadwalUjian::findOrFail($id)->delete();
            });
            return redirect()->route('admin.jadwal.index', ['tab' => 'ujian'])->with('success', 'Jadwal ujian berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data.');
        }
    }
}