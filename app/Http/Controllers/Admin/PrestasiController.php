<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PrestasiExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    public function index(Request $request)
{
    // 1. Ambil data untuk isi dropdown filter
    $daftar_kelas = \App\Models\Kelas::orderBy('nama_kelas', 'asc')->get();
    
    // Ambil daftar tingkat unik dari tabel prestasi (biar dinamis)
    $daftar_tingkat = Prestasi::distinct()->pluck('tingkat')->filter();

    // 2. Query utama dengan filter
    $query = Prestasi::with(['siswa.user', 'siswa.kelas']);

    // Filter Nama/NIS
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('siswa', function($q) use ($search) {
            $q->where('nis', 'like', "%{$search}%")
              ->orWhereHas('user', function($qu) use ($search) {
                  $qu->where('name', 'like', "%{$search}%");
              });
        });
    }

    // Filter Kelas
    if ($request->filled('kelas')) {
        $query->whereHas('siswa', function($q) use ($request) {
            $q->where('id_kelas', $request->kelas);
        });
    }

    // Filter Tingkat (Lomba)
    if ($request->filled('tingkat')) {
        $query->where('tingkat', $request->tingkat);
    }

    // Filter Status Validasi
    if ($request->filled('status')) {
        $query->where('status_validasi', $request->status);
    }

    $prestasi = $query->latest()->paginate(10)->withQueryString(); // withQueryString agar filter tidak hilang saat pindah halaman

    // 3. Statistik
    $stats = [
        'total' => Prestasi::count(),
        'akademik' => Prestasi::where('kategori', 'Akademik')->count(),
        'non_akademik' => Prestasi::where('kategori', 'Non-Akademik')->count(),
    ];

    $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first()?->tahun_ajaran ?? 'N/A';
    return view('admin.prestasi.index', compact('prestasi', 'stats', 'daftar_kelas', 'daftar_tingkat', 'tahunAjaranAktif'));
}

    public function show($id)
{
    // Ambil data prestasi beserta siswa dan user-nya (pemilik asli data)
    $prestasi = Prestasi::with('siswa.user') 
                ->where('id_prestasi', $id)
                ->firstOrFail();

    // Pastikan diarahkan ke view admin
    return view('admin.prestasi.show', compact('prestasi'));
}

    public function validasi(Request $request, $id)
{
    // 1. Cari berdasarkan id_prestasi (Primary Key kamu)
    $prestasi = Prestasi::where('id_prestasi', $id)->firstOrFail();
    
    // 2. Update data sesuai nama kolom di tabel (id_prestasi, status_validasi, dll)
    $prestasi->update([
        'bebas_spp'       => $request->bebas_spp,     // Sesuai kolom nomor 13
        'status_validasi' => $request->status,        // Sesuai kolom nomor 11
        'keterangan'      => $request->keterangan,   // Sesuai kolom nomor 14
        'divalidasi_oleh' => auth()->user()->id,      // Sesuai kolom nomor 12 (opsional: catat siapa adminnya)
    ]);

    return redirect()->route('admin.prestasi.index')
                     ->with('success', 'Prestasi siswa berhasil divalidasi.');
}

public function downloadPdf(Request $request)
{
    $query = Prestasi::with([
        'siswa.user',
        'siswa.kelas'
    ]);

    // Filter Kelas
    if ($request->filled('kelas')) {
        $query->whereHas('siswa', function ($q) use ($request) {
            $q->where('id_kelas', $request->kelas);
        });
    }

    // Filter Status
    if ($request->filled('status')) {
        $query->where('status_validasi', $request->status);
    }

    // Filter Search
    if ($request->filled('search')) {
        $search = $request->search;

        $query->whereHas('siswa.user', function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%");
        });
    }

    $prestasi = $query->get();

    $pdf = Pdf::loadView(
        'admin.prestasi.rekap-pdf',
        compact('prestasi')
    )->setPaper('A4', 'landscape');

    return $pdf->download('rekap-prestasi.pdf');
}

public function downloadExcel(Request $request)
{
    return Excel::download(
        new PrestasiExport($request),
        'rekap-prestasi.xlsx'
    );
}
}