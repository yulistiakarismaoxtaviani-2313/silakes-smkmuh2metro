<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PrestasiController extends Controller
{
    public function index()
{
    $prestasi = Prestasi::where('id_siswa', Auth::user()->siswa->id_siswa)
                        ->latest()
                        ->get();

    return view('siswa.prestasi.index', compact('prestasi'));
}

    public function create()
    {
        // Mengambil data siswa yang login melalui relasi user
        $siswa = Auth::user()->siswa; 
        return view('siswa.prestasi.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'kategori'      => 'required|in:Akademik,Non-Akademik',
            'nama_lomba'    => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'cabang_lomba'  => 'required|string|max:255',
            'tingkat'       => 'required',
            'juara'         => 'required',
            'tanggal_lomba' => 'required|date',
            'sertifikat'    => 'required|image|mimes:jpeg,png,jpg|max:20048',
        ]);

        try {
            // 2. Proses Upload File
            $filename = null;
            if ($request->hasFile('sertifikat')) {
                $file = $request->file('sertifikat');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                
                // Pindahkan ke folder yang sudah kamu buat manual
                $file->move(public_path('storage/sertifikat'), $filename);
            }

            // 3. Simpan ke Database (Nama kolom disesuaikan dengan screenshot DB kamu)
            Prestasi::create([
                'id_siswa'            => Auth::user()->siswa->id_siswa,
                'nama_lomba'          => $request->nama_lomba,          // Kolom 3 di DB
                'cabang_lomba'        => $request->cabang_lomba,        // Kolom 4 di DB
                'penyelenggara_lomba' => $request->penyelenggara,       // Kolom 5 di DB
                'tingkat'             => $request->tingkat,             // Kolom 6 di DB
                'kategori'            => $request->kategori,            // Kolom 7 di DB
                'peringkat'           => $request->juara,               // Kolom 8 di DB (Juara 1, dll)
                'tanggal'             => $request->tanggal_lomba,       // Kolom 9 di DB
                'file_bukti'          => $filename,                     // Kolom 10 di DB
                'status_validasi'     => 'Pending',                     // Kolom 11 di DB
            ]);

            return redirect()->route('siswa.prestasi.index')->with('success', 'Prestasi berhasil dikirim!');

        } catch (\Exception $e) {
            // Jika gagal, hapus file yang terlanjur diupload (opsional)
            // Lalu tampilkan error spesifik agar bisa didebug
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function show($id)
{
    // Mengambil data berdasarkan id_prestasi
    $prestasi = Prestasi::where('id_prestasi', $id)
                ->where('id_siswa', Auth::user()->siswa->id_siswa)
                ->firstOrFail();

    return view('siswa.prestasi.show', compact('prestasi'));
}


    public function destroy($id)
{
    // Cari data prestasi milik siswa yang sedang login
    $prestasi = Prestasi::where('id_prestasi', $id)
                        ->where('id_siswa', Auth::user()->siswa->id_siswa) // Pastikan ambil id_siswa dari relasi siswa
                        ->firstOrFail();

    // 1. Hapus File Fisik di Storage (Jika ada)
    if ($prestasi->file_bukti && Storage::disk('public')->exists('sertifikat/' . $prestasi->file_bukti)) {
        Storage::disk('public')->delete('sertifikat/' . $prestasi->file_bukti);
    }
    
    // 2. Hapus Data di Tabel Database
    $prestasi->delete();

    // 3. Kembali dengan pesan sukses
    return redirect()->route('siswa.prestasi.index')->with('success', 'Data prestasi berhasil dihapus selamanya.');
}
}