<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Imports\KelasImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar kelas dengan fitur filter dan pencarian.
     */
   public function index(Request $request)
    {
        // 1. Tambahkan withCount('siswa') agar $item->siswa_count bisa terbaca di tabel
        $query = Kelas::with(['guru.user', 'programKeahlian', 'tahunAjaran'])
                      ->withCount('siswa'); 

        // Filter Search (Nama Kelas)
        if ($request->filled('search')) {
            $query->where('nama_kelas', 'LIKE', "%{$request->search}%");
        }

        // Filter Tingkat
        if ($request->filled('tingkat') && $request->tingkat != 'Pilih Tingkat') {
            $query->where('tingkat', $request->tingkat);
        }

        // Filter Program Keahlian (Jurusan)
        if ($request->filled('jurusan') && $request->jurusan != 'Pilih Jurusan') {
            $query->where('id_program_keahlian', $request->jurusan);
        }

        // Filter Status
        if ($request->filled('status') && $request->status != 'Status') {
            $query->where('status', $request->status);
        }

        $kelas = $query->latest()->paginate(10)->withQueryString();
        
        // 2. Ambil data statistik untuk Card di atas
        $tahun_aktif = TahunAjaran::where('status', 'aktif')->first();
        $total_siswa = \App\Models\Siswa::count(); // Hitung semua siswa di database
        $total_wali = Kelas::where('status', 'aktif')->distinct('id_guru')->count('id_guru');
        
        // Data pendukung untuk dropdown filter
        $data_jurusan = DB::table('program_keahlian')->get();

        // 3. Masukkan semua variabel ke compact
        return view('admin.kelas.index', compact(
            'kelas', 
            'data_jurusan', 
            'tahun_aktif', 
            'total_siswa', 
            'total_wali'
        ));
    }

    /**
     * Menampilkan form tambah kelas.
     */
    public function create()
    {
        $data_guru = Guru::with('user')->get()->sortBy('user.nama');
        $data_jurusan = DB::table('program_keahlian')->get();
        
        return view('admin.kelas.create', compact('data_guru', 'data_jurusan'));
    }

    /**
     * Menyimpan data kelas baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas'          => 'required|string|max:255|unique:kelas,nama_kelas',
            'id_guru'             => 'required|exists:guru,id_guru',
            'id_program_keahlian' => 'required',
            'tingkat'             => 'required|in:X,XI,XII',
            'status'              => 'required|in:aktif,nonaktif',
        ]);

        try {
            $ta = TahunAjaran::where('status', 'aktif')->first();
            $sem = Semester::where('status', 'aktif')->first();

            if (!$ta || !$sem) {
                return back()->withErrors(['error' => 'Data Tahun Ajaran atau Semester aktif belum tersedia!'])->withInput();
            }

            Kelas::create([
                'nama_kelas'          => strtoupper($request->nama_kelas),
                'id_guru'             => $request->id_guru,
                'id_program_keahlian' => $request->id_program_keahlian,
                'tingkat'             => $request->tingkat,
                'status'              => $request->status,
                'id_tahun_ajaran'     => $ta->id_tahun_ajaran,
                'id_semester'         => $sem->id_semester,
            ]);

            return redirect()->route('admin.kelas.index')->with('success', 'Data Kelas Berhasil Disimpan!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Menampilkan detail kelas dan daftar siswanya.
     */
    public function show($id)
    {
        $kelas = Kelas::with(['guru.user', 'programKeahlian', 'siswa.user'])->findOrFail($id);
        
        // Urutkan siswa berdasarkan nama user
        $siswa = $kelas->siswa->sortBy(function($s) {
            return $s->user->nama ?? '';
        });

        return view('admin.kelas.show', compact('kelas', 'siswa'));
    }

    /**
     * Menampilkan form edit kelas.
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $data_jurusan = DB::table('program_keahlian')->get();
        
        // Ambil guru dan urutkan berdasarkan nama user
        $guru = Guru::with('user')->get()->sortBy(function($g) {
            return $g->user->nama ?? '';
        });

        return view('admin.kelas.edit', compact('kelas', 'guru', 'data_jurusan'));
    }

    /**
     * Memperbarui data kelas.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas'          => 'required|string|max:255|unique:kelas,nama_kelas,' . $id . ',id_kelas',
            'id_guru'             => 'required|exists:guru,id_guru',
            'id_program_keahlian' => 'required',
            'tingkat'             => 'required|in:X,XI,XII',
            'status'              => 'required|in:aktif,nonaktif',
        ]);

        try {
            $kelas->update([
                'nama_kelas'          => strtoupper($request->nama_kelas),
                'id_guru'             => $request->id_guru,
                'id_program_keahlian' => $request->id_program_keahlian,
                'tingkat'             => $request->tingkat,
                'status'              => $request->status,
            ]);

            return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()])->withInput();
        }
    }

public function destroy($id)
{
    try {
        DB::beginTransaction();

        $kelas = Kelas::findOrFail($id);

        // 1. Ambil semua id_siswa yang ada di kelas ini
        $siswaIds = \App\Models\Siswa::where('id_kelas', $kelas->id_kelas)->pluck('id_siswa');

        // 2. Hapus profil_siswa berdasarkan id_siswa yang ditemukan
        \App\Models\ProfilSiswa::whereIn('id_siswa', $siswaIds)->delete();

        // 3. Hapus data siswa-nya
        \App\Models\Siswa::where('id_kelas', $kelas->id_kelas)->delete();

        // 4. Baru hapus kelasnya
        $kelas->delete();

        DB::commit();

        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Data kelas berhasil dihapus.');
                         
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
    }
}


public function import(Request $request)
{
    $request->validate([
        'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120'
    ]);

    try {
        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\KelasImport, $request->file('file_excel'));
        
        return back()->with('success', 'Data berhasil diimport! (Baris yang nama guru/jurusannya tidak ditemukan telah dilewati)');
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Import Gagal: ' . $e->getMessage());
        return back()->with('error', 'Gagal import: Pastikan format file sesuai. Error: ' . $e->getMessage());
    }
}
}