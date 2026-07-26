<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Kelas; // Sesuaikan dengan nama model kelasmu
use App\Models\TahunAjaran; // Sesuaikan dengan nama model TA-mu
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengumuman::query();

        // Mulai kueri
    $query = Pengumuman::query();

    // Fungsi Search yang sudah ada
    if ($request->has('search')) {
        $query->where('judul', 'like', '%' . $request->search . '%');
    }

    // Fungsi Filter Kategori
    if ($request->filled('kategori')) {
        $query->where('kategori', $request->kategori);
    }

    // Fungsi Filter Target
    if ($request->filled('target')) {
        $query->where('target', $request->target);
    }

    // Fungsi Filter Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

        $pengumuman = $query->latest()->paginate(10);
        
        // Data untuk Statistik (Dinamis)
        $totalPengumuman = Pengumuman::count();
        $pengumumanTerbaru = Pengumuman::where('created_at', '>=', now()->subDays(7))->count();
        $pengumumanHariIni = Pengumuman::whereDate('created_at', today())->count();


        $tahun_aktif = TahunAjaran::where('status', 'aktif')->first();
        return view('admin.pengumuman.index', compact(
            'pengumuman', 
            'totalPengumuman', 
            'pengumumanTerbaru', 
            'pengumumanHariIni',
            'tahun_aktif'
        ));
    }

    // INI FUNGSI YANG HILANG (Penyebab Error 500)
    public function create()
    {
        // Ambil data tahun ajaran dan kelas untuk dropdown di form
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        $kelas = Kelas::all(); 

        return view('admin.pengumuman.create', compact('tahunAktif', 'kelas'));
    }

    public function store(Request $request)
    {
    
        $request->validate([
            'judul' => 'required',
            'kategori' => 'required',
            'isi' => 'required',
            'id_tahun_ajaran' => 'required',
            'target' => 'required',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            'target' => 'required|in:semua,siswa,guru,wali_kelas,kelas',
        ]);

        $data = $request->all();

        // Handle Upload File
        if ($request->hasFile('file_lampiran')) {
            $file = $request->file('file_lampiran');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pengumuman'), $nama_file);
            $data['file_lampiran'] = $nama_file;
        }

        Pengumuman::create($data);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman Berhasil Diterbitkan');
    }

    /**
     * Menampilkan detail pengumuman
     */
    public function show($id)
    {
        $pengumuman = Pengumuman::with(['tahunAjaran', 'kelas'])->findOrFail($id);
        return view('admin.pengumuman.show', compact('pengumuman'));
    }

    // Fungsi untuk menampilkan halaman form edit
public function edit($id)
{
    $pengumuman = Pengumuman::findOrFail($id);
    
    // Ambil data kelas jika ingin mengubah target ke kelas spesifik
    $kelas = \App\Models\Kelas::all(); 
    
    return view('admin.pengumuman.edit', compact('pengumuman', 'kelas'));
}

// Fungsi untuk menyimpan perubahan data
public function update(Request $request, $id)
{
    $pengumuman = Pengumuman::findOrFail($id);

    $request->validate([
        'judul' => 'required|string|max:255',
        'isi' => 'required',
        'kategori' => 'required',
        'target' => 'required',
        'status' => 'required',
    ]);

    $data = $request->all();

    // Logika jika ada upload file baru
    if ($request->hasFile('file_lampiran')) {
        // Hapus file lama jika ada
        if ($pengumuman->file_lampiran && file_exists(public_path('uploads/pengumuman/' . $pengumuman->file_lampiran))) {
            unlink(public_path('uploads/pengumuman/' . $pengumuman->file_lampiran));
        }

        $file = $request->file('file_lampiran');
        $nama_file = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/pengumuman'), $nama_file);
        $data['file_lampiran'] = $nama_file;
    }

    $pengumuman->update($data);

    return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui!');
}

   /**
     * Menghapus pengumuman dan filenya
     */
    public function destroy($id)
    {
        // Temukan data pengumuman
        $pengumuman = Pengumuman::findOrFail($id);

        // Hapus file fisik jika ada di storage
        if ($pengumuman->file_lampiran) {
            // Pastikan path-nya sesuai dengan folder saat kamu menyimpan file (misal: public/pengumuman/)
            $filePath = 'public/pengumuman/' . $pengumuman->file_lampiran;
            
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        }

        // Hapus data dari database
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus!');
    }
}