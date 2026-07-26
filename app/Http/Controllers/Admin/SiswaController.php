<?php

namespace App\Http\Controllers\Admin;

use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $tahunDipilih = $request->get('tahun');
        $data_kelas = Kelas::all();
        $data_tahun_ajaran = TahunAjaran::all();

        $query = Siswa::with('user', 'kelas', 'profil', 'tahunAjaran');

        // 1. Logika Filter Tahun (Termasuk pilihan 'semua')
        if ($tahunDipilih === 'semua') {
            $tahunAktif = (object) [
                'id_tahun_ajaran' => 'semua',
                'tahun_ajaran' => 'Semua Tahun'
            ];
            // Jika semua, kita tidak menambahkan query where id_tahun_ajaran
        } else {
            if ($tahunDipilih && $tahunDipilih != 'Pilih Tahun') {
                $tahunAktif = TahunAjaran::find($tahunDipilih);
            } else {
                $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
            }

            if ($tahunAktif) {
                $query->where('id_tahun_ajaran', $tahunAktif->id_tahun_ajaran);
            }
        }

        // 2. Statistik (Mengikuti filter tahun yang dipilih)
        if ($tahunAktif && $tahunAktif->id_tahun_ajaran === 'semua') {
            $totalSiswa = Siswa::count();
            $totalAktif = Siswa::where('status', 'aktif')->count();
            $totalNonaktif = Siswa::where('status', '!=', 'aktif')->count();
        } else {
            $idTahun = $tahunAktif->id_tahun_ajaran ?? 0;
            $totalSiswa = Siswa::where('id_tahun_ajaran', $idTahun)->count();
            $totalAktif = Siswa::where('id_tahun_ajaran', $idTahun)->where('status', 'aktif')->count();
            $totalNonaktif = Siswa::where('id_tahun_ajaran', $idTahun)->where('status', '!=', 'aktif')->count();
        }

        // 3. Filter Tambahan (Menggunakan filled agar lebih akurat)
        if ($request->filled('kelas') && $request->kelas != 'Pilih Kelas') {
            $query->where('id_kelas', $request->kelas);
        }

        if ($request->filled('status') && $request->status != 'status') {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_kelamin') && $request->jenis_kelamin != 'Jenis Kelamin') {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('nama', 'LIKE', "%{$search}%");
                  });
            });
        }

        $siswa = $query->orderBy('nis', 'asc')->paginate(10)->withQueryString();

        return view('admin.siswa.index', compact(
            'siswa', 'totalSiswa', 'totalAktif', 'totalNonaktif', 
            'data_kelas', 'tahunAktif', 'data_tahun_ajaran'
        ));
    }

    public function create()
    {
        $data_kelas = Kelas::all();
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        return view('admin.siswa.create', compact('data_kelas', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis'           => 'required|unique:siswa,nis',
            'nama'          => 'required|string|max:255',
            'id_kelas'      => 'required', 
            'jenis_kelamin' => 'required|in:L,P',
            'status'        => 'required|in:aktif,nonaktif',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $namaFoto = null;
            if ($request->hasFile('foto')) {
                $namaFoto = $request->nis . '_' . time() . '.' . $request->foto->extension();
                $tujuan = public_path('storage/profil/');
                if (!file_exists($tujuan)) {
                    mkdir($tujuan, 0777, true);
                }
                $request->foto->move($tujuan, $namaFoto);
            }

            $user = User::create([
                'nama'     => strtoupper($request->nama),
                'email'    => $request->nama . '@sekolah.com', 
                'username' => $request->nis,
                'password' => Hash::make($request->nis), 
                'role'     => 'siswa',
                'photo'    => $namaFoto ?? '', 
            ]);

            $userId = $user->id_user ?? $user->id;

            Siswa::create([
                'id_user'       => $userId,
                'nis'           => $request->nis,
                'jenis_kelamin' => $request->jenis_kelamin,
                'status'        => $request->status,
                'id_kelas'      => $request->id_kelas,
                'foto'          => $namaFoto,
                // Secara otomatis set tahun ajaran ke yang sedang aktif saat input
                'id_tahun_ajaran' => TahunAjaran::where('status', 'aktif')->first()->id_tahun_ajaran ?? null,
            ]);

            DB::commit();
            return redirect()->route('admin.siswa.index')->with('success', 'Data Siswa Berhasil Disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Gagal simpan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $siswa = Siswa::with(['user', 'kelas', 'tahunAjaran'])->findOrFail($id);
        return view('admin.siswa.show', compact('siswa')); 
    }

    public function edit($id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);
        $data_kelas = Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'data_kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'status'        => 'required|in:aktif,nonaktif',
            'id_kelas'      => 'required',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();
            $siswa = Siswa::findOrFail($id);
            $user  = User::findOrFail($siswa->id_user);

            if ($request->hasFile('foto')) {
                if ($siswa->foto && file_exists(public_path('storage/profil/' . $siswa->foto))) {
                    unlink(public_path('storage/profil/' . $siswa->foto));
                }
                $namaFoto = $siswa->nis . '_' . time() . '.' . $request->foto->extension();
                $request->foto->move(public_path('storage/profil/'), $namaFoto);
                $siswa->foto = $namaFoto;
                $user->photo = $namaFoto;
            }

            $user->update(['nama' => strtoupper($request->nama)]);

            $siswa->update([
                'jenis_kelamin' => $request->jenis_kelamin,
                'status'        => $request->status,
                'id_kelas'      => $request->id_kelas,
                'foto'          => $siswa->foto,
            ]);

            DB::commit();
            return redirect()->route('admin.siswa.index')->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
{
    try {
        // Matikan pengecekan Foreign Key sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::beginTransaction();

        $siswa = Siswa::findOrFail($id);
        
        // 1. Hapus profil
        DB::table('profil_siswa')->where('id_siswa', $id)->delete();

        // 2. Hapus foto
        if ($siswa->foto && file_exists(public_path('storage/profil/' . $siswa->foto))) {
            unlink(public_path('storage/profil/' . $siswa->foto));
        }

        // 3. Hapus siswa
        $siswa->delete();

        // 4. Hapus user
        User::where('id_user', $siswa->id_user)->delete();

        DB::commit();
        
        // Aktifkan kembali pengecekan Foreign Key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        return redirect()->route('admin.siswa.index')->with('success', 'Data berhasil dihapus!');

    } catch (\Exception $e) {
        DB::rollback();
        // Pastikan kembali Foreign Key aktif jika terjadi error
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
    }
}

public function resetPassword($id)
{
    $siswa = Siswa::findOrFail($id);

    $user = User::where('id_user', $siswa->id_user)->firstOrFail();

    // Reset password menjadi NIS
    $user->update([
        'password' => Hash::make($siswa->nis)
    ]);

    return redirect()->back()->with(
        'success',
        'Password berhasil direset. Password default adalah NIS siswa.'
    );
}

    public function import(Request $request)
{
    $request->validate(['file_excel' => 'required|mimes:xlsx,xls,csv']);
    
    try {
        // Ambil ID Tahun Ajaran yang sedang aktif
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        $id_tahun = $tahunAktif ? $tahunAktif->id_tahun_ajaran : null;

        // Kirim $id_tahun ke dalam constructor class SiswaImport
        Excel::import(new SiswaImport($id_tahun), $request->file('file_excel'));
        
        return back()->with('success', 'Data siswa berhasil diimport!');
    } catch (\Exception $e) {
        return back()->with('error', 'Ada kesalahan: ' . $e->getMessage());
    }
}
}